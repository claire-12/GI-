<?php

class CRMService
{
    public function __construct()
    {
        add_filter('wpcf7_submission_result', [$this, 'crm_action_after_form_submission'], 10, 2);
        add_filter('saved_request_a_quote', [$this, 'crm_action_after_saved_request_a_quote']);
    }

    private function getAccountID()
    {

    }

    public function crm_action_after_form_submission($result, $submission)
    {
        try {
            // Retrieve the posted data
            $posted_data = $submission->get_posted_data();
            $name_title = get_name_title($posted_data['your-title'][0]);
            $productofinterest = get_product_of_interests($posted_data['your-product'][0]);
            if (!empty($productofinterest) && !empty($name_title)) {
                $form = array(
                    'email' => $posted_data['your-email'],
                    'company' => $posted_data['your-company-sector'],
                    'lastname' => $posted_data['your-name'],
                    'mobile' => $posted_data['your-phone'],
                    'jobtitle' => (string)$name_title,
                    'message' => $posted_data['your-message'],
                    'product' => (string)$productofinterest,
                );

                $crm = new CRMController();
                $lead = $crm->processContactUsSubmit($form);

                wp_mail('dangminhtuan0207@gmail.com', 'crm_action_after_form_submission', json_encode($lead));

                if ($lead) {
                    $user = get_user_by('email', $posted_data['your-email']);
                    if ($user) {
                        update_user_meta($user->ID, 'AccountID', $lead->AccountPartyID ?? '');
                        update_user_meta($user->ID, 'ExternalID', $lead->ExternalID ?? '');
                        update_user_meta($user->ID, 'ContactID', $lead->ContactID ?? '');
                    } else {
                        update_option('AccountID_' . base64_encode($posted_data['your-email']), $lead->AccountID ?? '');
                        update_option('ExternalID_' . base64_encode($posted_data['your-email']), $lead->ExternalID ?? '');
                        update_option('ContactID_' . base64_encode($posted_data['your-email']), $lead->ContactID ?? '');
                    }
                }
                //var_dump($form, $lead);
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
            $quote['jobtitle'] = is_array($name_title) ? array_key_first($name_title) : $name_title;
            $quote['product'] = $product;

            $crm = new CRMController();
            $lead = $crm->processRequestAQuoteSubmit($quote);

            wp_mail('dangminhtuan0207@gmail.com', 'crm_action_after_saved_request_a_quote', json_encode($quote). '####' .json_encode($lead));

            if (!empty($lead->leadid)) {
                $user = get_user_by('email', $quote['email']);
                if ($user) {
                    update_user_meta($user->ID, 'leadid', $lead->leadid);
                    if (!empty($lead->leadtype)) {
                        update_user_meta($user->ID, 'leadtype', $lead->leadtype);
                    }
                    if (!empty($lead->contactid)) {
                        update_user_meta($user->ID, 'ContactID', $lead->contactid);
                    }
                    if (!empty($lead->accountid)) {
                        update_user_meta($user->ID, 'AccountID', $lead->accountid);
                    }
                } else {
                    update_option('leadid_' . base64_encode($quote['email']), $lead->leadid);
                    if (!empty($lead->leadtype)) {
                        update_option('leadtype_' . base64_encode($quote['email']), $lead->leadtype);
                    }
                    if (!empty($lead->contactid)) {
                        update_option('ContactID_' . base64_encode($quote['email']), $lead->contactid);
                    }
                    if (!empty($lead->accountid)) {
                        update_option('AccountID_' . base64_encode($quote['email']), $lead->accountid);
                    }
                }
            }
        } catch (Exception $e) {
            wp_mail('dangminhtuan0207@gmail.com', 'crm_action_after_form_submission', $e->getMessage() . '###' . $e->getTraceAsString());
        }
    }
}

new CRMService();
