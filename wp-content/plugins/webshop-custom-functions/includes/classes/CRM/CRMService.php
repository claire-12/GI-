<?php

class CRMService
{
    public function __construct()
    {
        add_action('init', [$this, 'confirm_email']);
        add_action('wp_login', [$this, 'check_user_sap_number'], 10, 2);
        add_filter('wpcf7_submission_result', [$this, 'crm_action_after_form_submission'], 10, 2);
        add_action('saved_request_a_quote', [$this, 'crm_action_after_saved_request_a_quote']);
        add_action('saved_user_keep_informed', [$this, 'crm_action_after_saved_user_keep_informed']);
        add_action('saved_user_confirm_keep_informed', [$this, 'crm_action_after_saved_user_keep_informed']);
        add_action('gi_created_new_customer', [$this, 'crm_action_after_gi_created_new_customer']);
    }

    /**
     * @param $email
     * @param $data
     * @return void
     */
    private function saveCRMData($email, $data): void
    {
        $user = get_user_by('email', $email);
        foreach ($data as $key => $value) {
            if (empty($value)) {
                continue;
            }

            if ($user) {
                update_user_meta($user->ID, $key, $value);
            } else {
                update_option($key . base64_encode($email), $value);
            }
        }
    }

    private function getMaterialCode(string $material): string
    {
        $materials = CRMConstant::MATERIAL;

        return array_search($material, $materials);
    }

    private function getDepartmentCode(string $department): string
    {
        $departments = CRMConstant::FUNCTION_FIELD;

        $key = array_search($department, $departments);

        return $key ?? '0001';
    }

    private function requestQuoteCRM($data)
    {
        $crm = new CRMController();
        $lead = $crm->processRequestAQuoteSubmit($data);
        if (!empty($lead->leadid)) {
            $dataCRM = array(
                'leadid' => $lead->leadid,
                'leadtype' => $lead->leadtype,
                'ContactID' => $lead->contactid,
                'AccountID' => $lead->accountid,
            );
            $this->saveCRMData($data['email'], $dataCRM);
            $this->notify_quote_customer($data['email'], $data);

            return true;
        }
        return false;
    }

    private function requestContactCRM($data)
    {
        $crm = new CRMController();
        $lead = $crm->processContactUsSubmit($data);

        if ($lead) {
            $dataCRM = array(
                'AccountID' => $lead->AccountPartyID,
                'ExternalID' => $lead->ExternalID,
                'ContactID' => $lead->ContactID,
            );
            $this->saveCRMData($data['your-email'], $dataCRM);

            $this->notify_contact_customer($data['your-email'], $data);
        }
        return $lead;
    }

    public function crm_action_after_form_submission($result, $submission)
    {
        $posted_data = $submission->get_posted_data();
        $result['userExistByEmail'] = $this->userExistByEmail($posted_data['your-email']);
        if ($result['status'] === 'mail_sent') {
            try {
                $product = get_product_of_interests($posted_data['your-product'][0]);
                if (!empty($product)) {
                    $posted_data['mobile'] = sprintf('+%s%s', $posted_data['user_telephone_code'], remove_zero_number($posted_data['user_telephone']));
                    $posted_data['product'] = (string)$product;
                    $posted_data['brand'] = $this->getPageBrand();

                    if (is_user_logged_in()){
                        $lead = $this->requestContactCRM($posted_data);
                        $result['lead'] = $lead;

                        if (empty($lead)) {
                            $result['status'] = 'wpcf7invalid';
                        }
                    } else {
                        $result['message'] = 'Thanks for reaching out to us. We follow tough standards in how we manage your data at Datwyler. That’s why you’ll now receive an e-mail from us to confirm your request. If you don’t receive a message, please check your junk folder.';
                        $this->send_confirm_email($posted_data['your-email'], $posted_data, 'contact');
                    }
                }
                //var_dump($result,is_user_logged_in());
            } catch (Exception $e) {
                $result['status'] = 'wpcf7invalid';
                //wp_mail('michael.santos@infolabix.com', 'crm_action_after_form_submission', $e->getMessage() . '###' . $e->getTraceAsString());
            }
        }

        return $result;
    }

    public function crm_action_after_saved_request_a_quote($quote)
    {
        $success = false;
        try {
            $name_title = $quote['user_title'] ? get_name_title($quote['user_title']) : '0001';
            $product = get_product_of_interests($quote['product-of-interest']);

            $quote['jobtitle'] = is_array($name_title) ? array_key_first($name_title) : $name_title;
            $quote['product'] = $product;
            $quote['brand'] = $this->getPageBrand();
            $quote['application'] = $quote['o_ring']['desired-application'] ?? '';
            $quote['mobile'] = sprintf('+%s%s', $quote['billing_phone_code'], $quote['billing_phone']);
            if (!empty($quote['o_ring']['material'])) {
                $quote['material'] = $this->getMaterialCode($quote['o_ring']['material']);
            }
            $quote['file_path'] = [];
            if (!empty($quote['files'])) {
                $filArray = explode(',', $quote['files']);
                foreach ($filArray as $file) {
                    $filepath = wp_get_attachment_url($file);
                    $quote['file_path'][] = $filepath;
                }
            }

            //if (is_user_logged_in()) {
            if ( is_user_logged_in_by_email($quote['email']) ) {
                if ($this->requestQuoteCRM($quote)) {
                    $success = true;
                }

            } else {
                $success = true;
                $this->send_confirm_email($quote['email'], $quote, 'request_quote');
            }
        } catch (\Exception $e) {
            wp_mail('michael.santos@infolabix.com', 'crm_action_after_form_submission', $e->getMessage() . '###' . $e->getTraceAsString());
        }

        if ($success) {
            $message = '<div class="alert alert-success woo-notice" role="alert">' . __('Request a quote successfully', 'cabling') . '</div>';
            wp_send_json_success($message);
        }

        $message = '<div class="alert alert-danger woo-notice" role="alert">' . __('There was an error while processing the request. Please try again later!', 'cabling') . '</div>';
        wp_send_json_error($message);
    }

    public function crm_action_after_gi_created_new_customer($data)
    {
        try {
            $data['department'] = '0001';
            if (!empty($data['company-sector'])) {
                $data['department'] = $this->getDepartmentCode($data['company-sector']);
            }

            $crm = new CRMController();
            $lead = $crm->processAccountCreationLead($data);

            if ($lead) {
                $dataCRM = array(
                    'AccountID' => $lead->AccountPartyID,
                    'ExternalID' => $lead->ExternalID,
                    'ContactID' => $lead->ContactID,
                );
                $this->saveCRMData($data['user_email'], $dataCRM);
            }
        } catch (Exception $e) {
            wp_mail('michael.santos@infolabix.com', 'crm_action_after_gi_created_new_customer', $e->getMessage() . '###' . $e->getTraceAsString());
        }
    }

    public function crm_action_after_saved_user_keep_informed($data)
    {
        try {
            $data['options'] = [];
            if (!empty($data['category'])) {
                $news = get_terms(array(
                    'taxonomy' => 'news-category',
                    'include' => $data['category'],
                    'fields' => 'slugs',
                ));

                $blog = get_terms(array(
                    'taxonomy' => 'category',
                    'include' => $data['category'],
                    'fields' => 'slugs',
                ));

                if ($news) {
                    $news = array_map(function ($slug) {
                        return "n_" . $slug;
                    }, $news);
                    $data['options'] = array_merge($data['options'], $news);
                }

                if ($blog) {
                    $blog = array_map(function ($slug) {
                        return "b_" . $slug;
                    }, $blog);
                    $data['options'] = array_merge($data['options'], $blog);
                }
            }
            $data['brand'] = $this->getPageBrand();
            $crm = new CRMController();
            $crm->processKMILeadCreation($data);
        } catch (Exception $e) {
            wp_mail('michael.santos@infolabix.com', 'crm_action_after_form_submission', $e->getMessage() . '###' . $e->getTraceAsString());
        }
    }

    private function send_confirm_email(string $email, array $data, $type)
    {
        $data_confirm = base64_encode(json_encode($data));
        $token = generate_confirmation_token();

        $expiration_time = time() + (24 * 60 * 60); // 24 hours in seconds
        set_transient('confirmation_token_' . $email, $token, $expiration_time);
        set_transient('confirmation_data_' . $email, $data_confirm, $expiration_time);
        $verify_link = add_query_arg([
            'action' => 'confirm-email',
            'token' => $token,
            'email' => $email,
            'type' => $type,
        ], home_url('/'));

        if ($type === 'request_quote') {
            //$subject = sprintf(__('[%s] Confirmation: Please Confirm Your Email', 'cabling'), get_bloginfo('name'));
            $subject = __('Datwyler Sealing Solutions: Confirming Your Request for Quotation', 'cabling');
            $options = array(
                'link' => $verify_link,
                'subject' => $subject,
                'template' => 'template-parts/emails/confirm_request_quote.php',
            );
        } elseif ($type === 'contact') {

            //$subject = sprintf(__('[%s] Confirmation: Please Confirm Your Email', 'cabling'), get_bloginfo('name'));
            $subject = __('Datwyler Sealing Solutions: Confirming Your Contact Us Request', 'cabling');
            $options = array(
                'link' => $verify_link,
                'subject' => $subject,
                'template' => 'template-parts/emails/confirm_contact.php',
            );
        }


        GIEmail::send($email, $options);
    }

    private function notify_quote_customer(string $email, array $data)
    {
        //$subject = sprintf(__('[%s] Request a quote', 'cabling'), get_bloginfo('name'));
        $subject = __('Datwyler Sealing Solutions: Confirming Your Request for Quotation', 'cabling');


        $options = array(
            'subject' => $subject,
            'data' => $data,
            'template' => 'template-parts/emails/request-a-quote.php',
        );

        GIEmail::send($email, $options);
    }

    private function notify_contact_customer(string $email, array $data)
    {
        $subject = __('Datwyler Sealing Solutions: Confirming Your Contact Us Request', 'cabling');

        $options = array(
            'subject' => $subject,
            'data' => $data,
            'template' => 'template-parts/emails/request-a-contact.php',
        );

        GIEmail::send($email, $options);
    }

    public function confirm_email()
    {
        if (isset($_GET['action']) && $_GET['action'] == 'confirm-email') {
            $token = $_GET['token'];
            $email = $_GET['email'];
            $type = $_GET['type'];

            $transient_token = get_transient('confirmation_token_' . $email);

            if ($transient_token && $transient_token === $token) {
                $confirmation_data = get_transient('confirmation_data_' . $email);
                $data = json_decode(base64_decode($confirmation_data), true);

                $success = false;
                if ($type === 'request_quote') {
                    $success = $this->requestQuoteCRM($data);
                } elseif ($type === 'contact') {
                    $success = $this->requestContactCRM($data);
                }

                if (empty($success)) {
                    wp_redirect(home_url('/something-went-wrong/'));
                } else {
                    delete_transient('confirmation_token_' . $email);
                    delete_transient('confirmation_data_' . $email);

                    wp_redirect(home_url('/your-subscription-has-been-confirmed/'));
                }
            } else {
                wp_redirect(home_url('/'));
            }
            exit();
        }
    }

    public static function check_user_sap_number($username, $user)
    {
        $sapNumber = get_user_meta($user->ID, 'sap_customer', true);
        if (empty($sapNumber)) {
            $crm = new CRMController();
            $lead = $crm->getContactByUserEmail($user->data->user_email);

            if (!empty($lead->ExternalID)) {
                update_user_meta($user->ID, 'sap_customer', $lead->ExternalID);
            }
        }
    }

    private function getPageBrand(): string
    {
        $brandId = get_field('brand');
        if (!empty($brandId)) {
            $brand = get_term($brandId, 'product-brand');
            if ($brand) {
                return $brand->slug;
            }
        }
        return 'N/A';
    }

    /**
     * Check if an email exists in the WordPress database.
     *
     * @param string $email The email address to check.
     * @return bool True if the email exists, false otherwise.
     */
    private function userExistByEmail(string $email): bool
    {
        global $wpdb;

        $query = $wpdb->prepare("
            SELECT COUNT(*)
            FROM $wpdb->users
            WHERE user_email = %s
        ", $email);

        $count = $wpdb->get_var($query);

        return $count > 0;
    }

}

new CRMService();
