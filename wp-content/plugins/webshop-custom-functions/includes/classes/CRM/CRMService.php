<?php

class CRMService
{
    public function __construct()
    {
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

    public function crm_action_after_form_submission($result, $submission)
    {
        if ($result['status'] != 'mail_sent'){
            return $result;
        }
        try {
            $posted_data = $submission->get_posted_data();
            $name_title = get_name_title($posted_data['your-title'][0]);
            $product = get_product_of_interests($posted_data['your-product'][0]);
            if (!empty($product) && !empty($name_title)) {
                $posted_data['mobile'] = sprintf('+%s%s', $posted_data['user_telephone_code'], remove_zero_number($posted_data['user_telephone']));
                $posted_data['product'] = (string)$product;

                $crm = new CRMController();
                $lead = $crm->processContactUsSubmit($posted_data);
                //print_r($lead);

                wp_mail('dangminhtuan0207@gmail.com', 'crm_action_after_form_submission', json_encode($lead));

                if ($lead) {
                    $dataCRM = array(
                        'AccountID' => $lead->AccountPartyID,
                        'ExternalID' => $lead->ExternalID,
                        'ContactID' => $lead->ContactID,
                    );
                    $this->saveCRMData($posted_data['your-email'], $dataCRM);
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
            $name_title = get_name_title($quote['user_title']);
            $product = get_product_of_interests($quote['product-of-interest']);
            $brandId = get_field('brand');
            $quote['jobtitle'] = is_array($name_title) ? array_key_first($name_title) : $name_title;
            $quote['product'] = $product;
            $quote['brand'] = 'N/A';
            $quote['application'] = $quote['o_ring']['desired-application'] ?? '';
            if (!empty($quote['o_ring']['material'])) {
                $quote['material'] = $this->getMaterialCode($quote['o_ring']['material']);
            }
            if (!empty($quote['files'])) {
                $filAray = explode(',', $quote['files']);
                $filepath = wp_get_attachment_url($filAray[0]);
                $quote['file'] = $filepath;
            }
            if (!empty($brandId)){
                $brand = get_term($brandId, 'product-brand');
                if ($brand){
                    $quote['brand'] = $brand->slug;
                }
            }

            $crm = new CRMController();
            $lead = $crm->processRequestAQuoteSubmit($quote);

            wp_mail('dangminhtuan0207@gmail.com', 'crm_action_after_saved_request_a_quote', json_encode($quote) . '####' . json_encode($lead));

            if (!empty($lead->leadid)) {
                $dataCRM = array(
                    'leadid' => $lead->leadid,
                    'leadtype' => $lead->leadtype,
                    'ContactID' => $lead->contactid,
                    'AccountID' => $lead->accountid,
                );
                $this->saveCRMData($quote['email'], $dataCRM);
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

            wp_mail('dangminhtuan0207@gmail.com', 'crm_action_after_gi_created_new_customer', json_encode($data) . '####' . json_encode($lead));

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
            $lead = $crm->processKMILeadCreation($data);

            wp_mail('dangminhtuan0207@gmail.com', 'crm_action_after_saved_user_keep_informed', json_encode($data) . '####' . json_encode($lead));
        } catch (Exception $e) {
            wp_mail('dangminhtuan0207@gmail.com', 'crm_action_after_form_submission', $e->getMessage() . '###' . $e->getTraceAsString());
        }
    }
}

new CRMService();
