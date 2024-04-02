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
        }
        $this->notify_quote_customer($data['email'], $data);
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
    }

    public function crm_action_after_form_submission($result, $submission)
    {
        if ($result['status'] != 'mail_sent') {
            return $result;
        }

        try {
            $posted_data = $submission->get_posted_data();
            $product = get_product_of_interests($posted_data['your-product'][0]);
            if (!empty($product)) {
                $posted_data['mobile'] = sprintf('+%s%s', $posted_data['user_telephone_code'], remove_zero_number($posted_data['user_telephone']));
                $posted_data['product'] = (string)$product;

                if (is_user_logged_in()) {
                    $this->requestContactCRM($posted_data);
                } else {
                    $result['message'] = 'Thanks for reaching out to us. We follow tough standards in how we manage your data at Datwyler. That’s why you’ll now receive an e-mail from us to confirm your request. If you don’t receive a message, please check your junk folder.';
                    $this->send_confirm_email($posted_data['your-email'], $posted_data, 'contact');
                }
            }
        } catch (Exception $e) {
            wp_mail('dangminhtuan0207@gmail.com', 'crm_action_after_form_submission', $e->getMessage() . '###' . $e->getTraceAsString());
        }

        return $result;
    }

    public function crm_action_after_saved_request_a_quote($quote)
    {
        try {
            $name_title = $quote['user_title'] ? get_name_title($quote['user_title']) : '0001';
            $product = get_product_of_interests($quote['product-of-interest']);
            $brandId = get_field('brand');
            $quote['jobtitle'] = is_array($name_title) ? array_key_first($name_title) : $name_title;
            $quote['product'] = $product;
            $quote['brand'] = 'N/A';
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
            if (!empty($brandId)) {
                $brand = get_term($brandId, 'product-brand');
                if ($brand) {
                    $quote['brand'] = $brand->slug;
                }
            }

            if (is_user_logged_in()) {
                $this->requestQuoteCRM($quote);
            } else {
                $this->send_confirm_email($quote['email'], $quote, 'request_quote');
            }
        } catch (Exception $e) {
            wp_mail('dangminhtuan0207@gmail.com', 'crm_action_after_form_submission', $e->getMessage() . '###' . $e->getTraceAsString());
        }
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
            wp_mail('dangminhtuan0207@gmail.com', 'crm_action_after_gi_created_new_customer', $e->getMessage() . '###' . $e->getTraceAsString());
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
            $crm = new CRMController();
            $crm->processKMILeadCreation($data);
        } catch (Exception $e) {
            wp_mail('dangminhtuan0207@gmail.com', 'crm_action_after_form_submission', $e->getMessage() . '###' . $e->getTraceAsString());
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

        $subject = sprintf(__('[%s] Confirmation: Please Confirm Your Email', 'cabling'), get_bloginfo('name'));

        $options = array(
            'link' => $verify_link,
            'subject' => $subject,
            'template' => 'template-parts/emails/confirm.php',
        );

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

                if ($type === 'request_quote') {
                    $this->requestQuoteCRM($data);
                } elseif ($type === 'contact') {
                    $this->requestContactCRM($data);
                }

                delete_transient('confirmation_token_' . $email);
                delete_transient('confirmation_data_' . $email);

                wp_redirect(home_url('/your-subscription-has-been-confirmed/'));
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

}

new CRMService();
