<?php
define('CABLING_SECRET', "35onoi2=-7#%g03kl");
define('CABLING_INVENTORY', "inventory");
define('CABLING_BACKLOG', "backlog");
define('CABLING_SHIPMENT', "shipment");

//add SVG to allowed file uploads
function add_file_types_to_uploads($file_types)
{

    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg';
    return array_merge($file_types, $new_filetypes);
}

add_action('upload_mimes', 'add_file_types_to_uploads');

//redirect user when access download file
function cabling_redirect_download_single()
{
    if (is_singular('download-file')) {
        $file = get_field('file_upload');
        $file_url = wp_get_attachment_url($file);
        wp_safe_redirect($file_url);
        exit();
    } elseif (is_singular('application')) {
        $url = get_field('redirect_url');
        if (!empty($url)) {
            wp_safe_redirect($url);
            exit();
        }
    }
}

add_action('template_redirect', 'cabling_redirect_download_single');

function cabling_show_back_btn()
{
    echo '<a href="#" class="backbutton box-shadow">' . __('Back to: ', 'cabling') . '<span class="back-text"></span></a>';
}

function cabling_add_theme_popup()
{
    if (is_user_logged_in()) {
        get_template_part('template-parts/modal/popup', 'create-customer');
    } else {
        get_template_part('template-parts/modal/popup', 'login');
    }
    get_template_part('template-parts/loading');
    get_template_part('template-parts/sidebar', 'navigation');
    get_template_part('template-parts/modal/popup', 'customer');
    get_template_part('template-parts/modal/popup', 'email_share');
    get_template_part('template-parts/modal/popup', 'pdf');
}

add_action('wp_footer', 'cabling_add_theme_popup');

function cabling_get_custom_email_html($link, $heading, $mailer, $type, $content = '')
{
    return wc_get_template_html($type, array(
        'link_verify' => $link,
        'email_heading' => $heading,
        'sent_to_admin' => false,
        'plain_text' => false,
        'email' => $mailer,
        'content' => $content,
    ));

}

function cabling_process_register_form()
{
    if (isset($_POST['register-nounce']) && wp_verify_nonce($_POST['register-nounce'], 'cabling-register')) {
        $verify_recaptcha = cabling_verify_recaptcha($_POST['g-recaptcha-response']);

        if (empty($verify_recaptcha)) {
            wc_add_notice(__('reCAPTCHA verification failed. Please try again!', 'cabling'), 'error');
            return;
        }
        $recipient = $_POST['register_email'];

        $register = home_url('/register/');
        if (!email_exists($recipient)) {
            $email = urlencode($recipient);
            $hash = MD5($recipient . CABLING_SECRET);

            $arg = json_encode(array('email' => $email, 'code' => $hash));

            $verify_link = add_query_arg(array(
                'code' => base64_encode($arg),
            ), $register);

            set_transient($recipient, $hash, DAY_IN_SECONDS);

            // load the mailer class
            $mailer = WC()->mailer();
            $mailer->recipient = $recipient;
            $type = 'emails/pre-register.php';
            $subject = __("Hi! Please verify your account!", 'cabling');
            $content = cabling_get_custom_email_html($verify_link, $subject, $mailer, $type);
            $headers = "Content-Type: text/html\r\n";

            $mailer->send($recipient, $subject, $content, $headers);

            wc_add_notice(sprintf(__('A confirmation email has been sent to your mailbox <strong>%s</strong><br> Please check your email box and continue your registration within 24 hours', 'cabling'), $recipient), 'success');
        } else {
            wc_add_notice(sprintf(__('The email <strong>%s</strong> was registered, please try with others.', 'cabling'), $recipient), 'error');
        }
    }

    if (
            (isset($_POST['verify-nounce']) && wp_verify_nonce($_POST['verify-nounce'], 'cabling-verify'))
            || (isset($_POST['customer-nounce']) && wp_verify_nonce($_POST['customer-nounce'], 'cabling-customer'))) {

        $verify_recaptcha = cabling_verify_recaptcha($_POST['g-recaptcha-response']);

        if (empty($verify_recaptcha)) {
            wc_add_notice(__('reCAPTCHA verification failed. Please try again!', 'cabling'), 'error');
            return;
        }

        $data = $_POST;
        $user_data = array(
            'has_approve' => 'false',
            'customer_level' => '1',
            'first_name' => $data['first-name'],
            'last_name' => $data['last-name'],
            'billing_first_name' => $data['first-name'],
            'shipping_first_name' => $data['first-name'],
            'billing_last_name' => $data['last-name'],
            'shipping_last_name' => $data['last-name'],
            'billing_phone' => $data['billing_phone'],
            'billing_phone_code' => $data['billing_phone_code'],
            'billing_company' => $data['company-name'],
            'shipping_company' => $data['company-name'],
            'billing_address_1' => $data['company-address'],
            'shipping_address_1' => $data['company-address'],
            'billing_country' => $data['billing_country'],
            'shipping_country' => $data['billing_country'],
            /*'billing_city' => $data['billing_city'],
            'shipping_city' => $data['billing_city'],
            'billing_postcode' => $data['billing_postcode'],
            'shipping_postcode' => $data['billing_postcode'],*/
            'company_name_responsible' => $data['company-name-responsible'],
            'company-sector' => $data['company-sector'],
            'billing_vat' => $data['billing_vat'],
            'job_title' => $data['job-title'],
            'user_department' => $data['department'],
            'user_title' => $data['user-title'],
            // JM 20230914
            'display_name' => $data['first-name'] . ' ' . $data['last-name'],
            'nickname' => $data['first-name'] . ' ' . $data['last-name'],
        );

        if (!empty($data['existing-customer']) && !empty($data['client-number'])) {
            $user_data['client-number'] = $data['client-number'];
        }

        $customer_id = wc_create_new_customer(
            $data['user_email'],
            $data['user_email'],
            $data['password'],
            [
                'meta_input' => $user_data
            ]
        );

        if ($customer_id) {

            //JM 20230914
            $user_id = wp_update_user(array('ID' => $customer_id, 'display_name' => $data['first-name'] . ' ' . $data['last-name']));


            delete_transient(urldecode($data['user_email']));

            //send email to customer
            if (isset($data['verify-nounce'])) {
                $mailer = WC()->mailer();
                $mailer->recipient = $recipient;
                $verify_link = '';
                $type = 'emails/register-verify.php';
                $recipient = get_option('admin_email');
                $subject = __("New account need to verify!", 'cabling');
                $content = cabling_get_custom_email_html($verify_link, $subject, $mailer, $type);
                $headers = "Content-Type: text/html\r\n";

                $mailer->send($recipient, $subject, $content, $headers);
                $redirect = add_query_arg('create-complete', 'true', home_url('/register/'));
                wp_redirect($redirect);
                exit();
            }
        }

    }
}

add_action('init', 'cabling_process_register_form');

function cabling_modify_user_table($column)
{
    unset($column['posts']);
    $column['sap_customer'] = 'SAP No.';
    $column['customer_level'] = 'Level';
    $column['customer_type'] = 'Account Type';
    $column['has_approve'] = 'Verify';

    return $column;
}

add_filter('manage_users_columns', 'cabling_modify_user_table');

function cabling_modify_user_table_row($val, $column_name, $user_id)
{
    $customer_level = get_customer_level($user_id);
    switch ($column_name) {
        case 'customer_level' :
            return "<strong>$customer_level</strong>";
        case 'customer_type' :
            $customer_type = get_customer_type_label($user_id);

            return "<strong>$customer_type</strong>";
        case 'sap_customer' :
            $sap_no = get_user_meta($user_id, 'sap_customer', true);

            return $sap_no ?? '-';
        case 'has_approve' :
            $has_approve_val = get_user_meta($user_id, 'has_approve', true);

            if ('true' !== $has_approve_val) {
                $content = '<a onclick="cabling_verify_user(this);return false;" data-user="' . $user_id . '" class="cabling_verify button-primary">Verify</a><br>';
            } else {
                $content = '<strong>Verified</strong>';
            }

            return $content;
        default:
    }
    return $val;
}

add_filter('manage_users_custom_column', 'cabling_modify_user_table_row', 10, 3);

/**
 * Searching Meta Data in Admin
 */
add_action('pre_user_query', 'cabling_pre_user_search');
function cabling_pre_user_search($user_search)
{
    global $wpdb;
    if (!isset($_GET['s'])) return;

    $search_array = array("sap_customer", "first_name", "last_name");

    $user_search->query_from .= " INNER JOIN {$wpdb->usermeta} ON {$wpdb->users}.ID={$wpdb->usermeta}.user_id AND (";
    for ($i = 0; $i < count($search_array); $i++) {
        if ($i > 0) $user_search->query_from .= " OR ";
        $user_search->query_from .= "{$wpdb->usermeta}.meta_key='" . $search_array[$i] . "'";
    }
    $user_search->query_from .= ")";
    $custom_where = $wpdb->prepare("{$wpdb->usermeta}.meta_value LIKE '%s'", "%" . $_GET['s'] . "%");
    $user_search->query_where = str_replace('WHERE 1=1 AND (', "WHERE 1=1 AND ({$custom_where} OR ", $user_search->query_where);

}

// Add product variations ACF rule
add_filter('acf/location/rule_values/post_type', 'acf_location_rule_values_variation');
function acf_location_rule_values_variation($choices)
{
    $choices['product_variation'] = 'Product Variation';
    return $choices;
}

$GLOBALS['wc_loop_variation_id'] = null;

function is_field_group_for_variation($field_group, $variation_data, $variation_post)
{
    return (preg_match('/Variation/i', $field_group['title']) == true);
}

add_action('woocommerce_product_after_variable_attributes', function ($loop_index, $variation_data, $variation_post) {
    $GLOBALS['wc_loop_variation_id'] = $variation_post->ID;

    foreach (acf_get_field_groups() as $field_group) {
        if (is_field_group_for_variation($field_group, $variation_data, $variation_post)) {
            acf_render_fields($variation_post->ID, acf_get_fields($field_group));
        }
    }

    $GLOBALS['wc_loop_variation_id'] = null;
}, 10, 3);

add_filter('acf/prepare_field', function ($field) {
    if (!$GLOBALS['wc_loop_variation_id']) {
        return $field;
    }

    $field['name'] = preg_replace('/^acf\[/', 'acf_variation[' . $GLOBALS['wc_loop_variation_id'] . '][', $field['name']);

    return $field;
}, 10, 1);

function rdv__after__render_field($field)
{
    echo "<script>
            (function($) {
                acf.doAction('append', $('#post'));
            })(jQuery);
          </script>";
}

add_action('acf/render_field/type=repeater', 'rdv__after__render_field', 10, 1);

add_action('woocommerce_save_product_variation', function ($variation_id, $loop_index) {
    if (!isset($_POST['acf_variation'][$variation_id])) {
        return;
    }

    if (!empty($_POST['acf_variation'][$variation_id]) && is_array($fields = $_POST['acf_variation'][$variation_id])) {
        foreach ($fields as $key => $val) {
            update_field($key, $val, $variation_id);
        }
    }

}, 10, 2);

add_filter('acf/validate_post_id', 'caner_custom_option_key', 10, 2);
function caner_custom_option_key($post_id, $_post_id)
{
    if ($_post_id == 'options_language') {
        $post_id = 'options';
    }
    return $post_id;
}

//get current country assosiate with language
function cabling_get_current_country()
{
    $code = $_SESSION['cabling_country'] ?? 0;
    $language_list = get_field('field_5fa25bc46d4f4', 'options_language');
    if ($code && !empty($language_list)) {
        foreach ($language_list as $country) {
            if ($code && $country['country']) {
                foreach ($country['country'] as $c) {
                    if ($c['code'] == $code)
                        return $c;
                }
            }
        }
    }
    return;
}

function cabling_get_country()
{
    if (isset($_SESSION['cabling_country'])) {
        $country_code = $_SESSION['cabling_country'];
    } elseif (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $country_code = get_user_meta($user_id, 'billing_country', true);
    } else {
        // Get the country by IP
        $location = (class_exists('WC_Geolocation') ? WC_Geolocation::geolocate_ip() : array('country' => ''));
        // Base fallback
        if (empty($location['country'])) {
            $location = wc_format_country_state_string(apply_filters('woocommerce_customer_default_location', get_option('woocommerce_default_country')));
        }
        $country_code = ($location['country'] ?? '');
    }

    $countries = array(
        'INT' => 'International',
        'AF' => 'Afghanistan',
        'AX' => 'Åland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'PW' => 'Belau',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BQ' => 'Bonaire, Saint Eustatius and Saba',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'BN' => 'Brunei',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo-Brazzaville',
        'CD' => 'Congo',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CW' => 'Cura&ccedil;ao',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island and McDonald Islands',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'CI' => 'Ivory Coast',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Laos',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'North Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'KP' => 'North Korea',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PS' => 'Palestinian Territory',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russia',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin (French part)',
        'SX' => 'Saint Martin (Dutch part)',
        'PM' => 'Saint Pierre and Miquelon',
        'VC' => 'Saint Vincent and the Grenadines',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome and Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'KR' => 'South Korea',
        'SS' => 'South Sudan',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard and Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syria',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UM' => 'United States Minor Outlying Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VA' => 'Vatican',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'VG' => 'British Virgin Islands',
        'VI' => 'US Virgin Islands',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'WS' => 'Samoa',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe',
    );
    $country_name = $countries[$country_code] ?? '';

    return array(
        'name' => $country_name,
        'code' => $country_code,
    );
}

function my_mce_buttons_2($buttons)
{
    /**
     * Add in a core button that's disabled by default
     */
    $buttons[] = 'subscript';
    $buttons[] = 'superscript';

    return $buttons;
}

add_filter('mce_buttons_2', 'my_mce_buttons_2');

function cabling_get_contact_wordwide($type, $country, $level = '')
{
    $contact_args = array(
        'post_type' => 'datwyler_contact',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'tax_query' => array(
            array(
                'taxonomy' => 'contact_type',
                'field' => 'term_id',
                'terms' => $type->term_id
            )
        ),
        'meta_query' => array(
            array(
                'key' => 'contact_country',
                'value' => $country,
                'compare' => 'REGEXP'
            )
        ),
    );

    if ($type->term_id == 4983 || $type->term_id == 5914) {
        $contact_args['meta_key'] = 'zipcode';
        $contact_args['orderby'] = 'meta_value';
    }

    $contacts = new WP_Query($contact_args);

    if ($contacts->have_posts()): ?>
        <div id="<?php echo 'ww_contact_' . $type->term_id ?>" class="card mb-3 <?php echo $level; ?>"
             style="border: none">
            <div class="card-header" id="<?php echo 'ct_' . $type->term_id ?>">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse"
                            data-target="#<?php echo 'ct_' . $type->term_id ?>-content" aria-expanded="true"
                            aria-controls="<?php echo 'ct_' . $type->term_id ?>-content">
                        <?php echo $type->name ?>
                        <svg class="icon-up" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                            <path d="M177 159.7l136 136c9.4 9.4 9.4 24.6 0 33.9l-22.6 22.6c-9.4 9.4-24.6 9.4-33.9 0L160 255.9l-96.4 96.4c-9.4 9.4-24.6 9.4-33.9 0L7 329.7c-9.4-9.4-9.4-24.6 0-33.9l136-136c9.4-9.5 24.6-9.5 34-.1z"/>
                        </svg>
                        <svg class="icon-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                            <path d="M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9l22.6-22.6c9.4-9.4 24.6-9.4 33.9 0l96.4 96.4 96.4-96.4c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9l-136 136c-9.2 9.4-24.4 9.4-33.8 0z"/>
                        </svg>
                    </button>
                </h5>
            </div>
            <div id="<?php echo 'ct_' . $type->term_id ?>-content" class="collapse"
                 aria-labelledby="<?php echo 'ct_' . $type->term_id ?>" data-parent="#accordion">
                <div class="card-body">
                    <?php while ($contacts->have_posts()): $contacts->the_post(); ?>
                        <?php $contact_id = get_the_ID(); ?>
                        <div data-order="<?php echo get_post_field('menu_order', $contact_id) ?>"
                             class="row pb-3 mb-3 border-bottom border-dark contact-item contact-<?php echo $contact_id ?>">
                            <div class="col-xs-12 col-md-3">
                                <?php if ($image_id = get_field('image', $contact_id)): ?>
                                    <?php echo wp_get_attachment_image($image_id, 'full'); ?>
                                <?php else: ?>
                                    <a href="mailto:<?php echo get_post_meta($contact_id, 'email', true) ?>">
                                        <?php echo get_the_title(); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <?php if ($image_id): ?>
                                    <!--<strong><?php /*echo get_the_title(get_the_ID()); */ ?></strong><br>-->
                                    <div><?php echo get_post_meta($contact_id, 'first_name', true); ?></div>
                                <?php endif; ?>
                                <?php if ($job_title = get_post_meta($contact_id, 'job_title', true)): ?>
                                    <div><?php echo $job_title; ?></div>
                                <?php endif; ?>
                                <?php if ($position = get_post_meta($contact_id, 'position', true)): ?>
                                    <span><?php echo $position; ?></span><br>
                                <?php endif; ?>
                                <?php if ($address = get_post_meta($contact_id, 'address', true)): ?>
                                    <span><?php echo $address; ?></span><br>
                                <?php endif; ?>
                                <?php if ($zipcode = get_post_meta($contact_id, 'zipcode', true)): ?>
                                    <?php
                                    $city = get_post_meta($contact_id, 'city', true);
                                    $city = !empty($city) ? $city : '';
                                    $region = get_post_meta($contact_id, 'region', true);
                                    $region = !empty($region) ? $region : '';
                                    ?>
                                    <span><?php printf(__('%s %s %s'), $zipcode, $city, $region) ?></span><br>
                                <?php endif; ?>
                                <?php if ($country_ct = get_post_meta($contact_id, 'country', true)): ?>
                                    <span><?php echo $country_ct ?></span><br>
                                <?php endif; ?>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <div class="more-if">
                                    <?php if ($phone = get_field('phone')): ?>
                                        <span><?php printf(__('Tel.: %s', 'cabling'), $phone) ?></span>
                                    <?php endif; ?>
                                    <?php if ($fax = get_field('fax')): ?>
                                        <span><?php printf(__('Fax.: %s', 'cabling'), $fax) ?></span>
                                    <?php endif; ?>
                                    <?php if ($mobile = get_field('mobile')): ?>
                                        <span><?php printf(__('Mobile.: %s', 'cabling'), $mobile) ?></span>
                                    <?php endif; ?>
                                    <?php if ($image_id): ?>
                                        <span>
									<a href="mailto:<?php echo get_post_meta($contact_id, 'email', true) ?>">
										<?php echo get_post_meta($contact_id, 'email', true) ?>
									</a>
									</span><br>
                                        <span>
									<a href="mailto:<?php echo get_post_meta($contact_id, 'email_2', true) ?>">
										<?php echo get_post_meta($contact_id, 'email_2', true) ?>
									</a>
									</span>
                                    <?php endif; ?>
                                    <?php if ($www = get_post_meta($contact_id, 'www', true)): ?>
                                        <span>
									<a href="<?php echo $www ?>">
										<?php echo $www ?>
									</a>
									</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php wp_reset_postdata();
    return;
}

function cabling_get_download_file($type, $download_page, $country)
{
    if (!empty($country)) {
        $country_term = get_term_by('name', $country, 'filter_country');
        if ($country_term) {
            //$country_name = $country_term->name;
            if (!empty($country_term->parent)) {
                $region = get_term_by('term_id', $country_term->parent, 'filter_country');
                $region_name = $region->name;
            }
        }
    }

    $dl_args = array(
        'post_type' => 'download-file',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'download_type',
                'field' => 'term_id',
                'terms' => $type->term_id
            )
        ),
        'meta_query' => array(
            array(
                'key' => 'parent_type',
                'value' => $download_page,
                //'compare' => 'REGEXP'
            )
        ),
    );

    //get by country
    $country_filter = array(
        'relation' => 'OR',
    );
    if (!empty($country_term))
        $country_filter[] = array(
            'taxonomy' => 'filter_country',
            'field' => 'name',
            'terms' => $country_term->name,
        );

    //get by region
    if (!empty($region_name))
        $country_filter[] = array(
            'taxonomy' => 'filter_country',
            'field' => 'name',
            'terms' => $region_name,
        );
    if (empty($country_term) && empty($region))
        $country_filter[] = array(
            'taxonomy' => 'filter_country',
            'field' => 'name',
            'terms' => 'International',
        );

    if (count($country_filter) > 1)
        $dl_args['tax_query'][] = $country_filter;
    //var_dump($country_term->name,$region_name);
    $downloads = new WP_Query($dl_args);

    if ($downloads->have_posts()): ?>
        <div class="card mb-3" style="border: none" data-country="<?php ?>">
            <div class="card-header" id="<?php echo 'dl' . $type->term_id ?>">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse"
                            data-target="#<?php echo 'dl' . $type->term_id ?>-content" aria-expanded="true"
                            aria-controls="<?php echo 'dl' . $type->term_id ?>-content">
                        <?php echo $type->name ?>
                        <svg class="icon-up" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                            <path d="M177 159.7l136 136c9.4 9.4 9.4 24.6 0 33.9l-22.6 22.6c-9.4 9.4-24.6 9.4-33.9 0L160 255.9l-96.4 96.4c-9.4 9.4-24.6 9.4-33.9 0L7 329.7c-9.4-9.4-9.4-24.6 0-33.9l136-136c9.4-9.5 24.6-9.5 34-.1z"/>
                        </svg>
                        <svg class="icon-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                            <path d="M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9l22.6-22.6c9.4-9.4 24.6-9.4 33.9 0l96.4 96.4 96.4-96.4c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9l-136 136c-9.2 9.4-24.4 9.4-33.8 0z"/>
                        </svg>
                    </button>
                </h5>
            </div>
            <div id="<?php echo 'dl' . $type->term_id ?>-content" class="collapse"
                 aria-labelledby="<?php echo 'dl' . $type->term_id ?>" data-parent="#accordion">
                <div class="card-body1">
                    <?php while ($downloads->have_posts()): $downloads->the_post(); ?>
                        <?php
                        $file = get_field('file_upload');
                        $download_description = get_field('download_description');
                        $attachment_filesize = size_format(filesize(get_attached_file($file)), 0);
                        $file_url = wp_get_attachment_url($file);
                        $filetype = wp_check_filetype($file_url);
                        ?>
                        <div data-order="<?php echo get_post_field('menu_order', get_the_ID()) ?>"
                             data-file="<?php the_ID() ?>" class="download-content">
                            <div class="row">
                                <?php if (has_post_thumbnail(get_the_ID())): ?>
                                    <div class="col-md-2 col-xs-12">
                                        <div class="featured-img">
                                            <?php echo get_the_post_thumbnail(get_the_ID(), 'thumbnail') ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (empty($download_description)): ?>
                                    <div class="col-xs-12 col-md-10">
                                        <div class="dl-content download-block">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?>
                                            </a>
                                            <div class="content">
                                                <?php the_content(); ?>
                                                <?php if ($filetype['ext']): ?>
                                                    <a href="<?php the_permalink(); ?>" class="download-link">
                                                        <?php printf('%s [%s]', $filetype['ext'], $attachment_filesize) ?>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="col-xs-12 col-md-12">
                                        <div class="dl-content">
                                            <?php echo $download_description ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php wp_reset_postdata();
    return;
}

function cabling_get_download_faq()
{
    $dl_args = array(
        'post_type' => 'faq',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    );
    $faqs = new WP_Query($dl_args);

    if ($faqs->have_posts()): ?>
        <?php while ($faqs->have_posts()): $faqs->the_post(); ?>
            <div data-order="<?php echo get_post_field('menu_order', get_the_ID()) ?>" class="card mb-3"
                 style="border: none">
                <div class="card-header" id="<?php echo 'faq' . get_the_ID() ?>">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse"
                                data-target="#<?php echo 'faq' . get_the_ID() ?>-content" aria-expanded="true"
                                aria-controls="<?php echo 'faq' . get_the_ID() ?>-content">
                            <?php the_title() ?>
                            <svg class="icon-up" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path d="M177 159.7l136 136c9.4 9.4 9.4 24.6 0 33.9l-22.6 22.6c-9.4 9.4-24.6 9.4-33.9 0L160 255.9l-96.4 96.4c-9.4 9.4-24.6 9.4-33.9 0L7 329.7c-9.4-9.4-9.4-24.6 0-33.9l136-136c9.4-9.5 24.6-9.5 34-.1z"/>
                            </svg>
                            <svg class="icon-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path d="M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9l22.6-22.6c9.4-9.4 24.6-9.4 33.9 0l96.4 96.4 96.4-96.4c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9l-136 136c-9.2 9.4-24.4 9.4-33.8 0z"/>
                            </svg>
                        </button>
                    </h5>
                </div>
                <div id="<?php echo 'faq' . get_the_ID() ?>-content" class="collapse"
                     aria-labelledby="<?php echo 'faq' . get_the_ID() ?>" data-parent="#accordion">
                    <div class="card-body">
                        <div class="faq-content">
                            <?php the_content() ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
    <?php wp_reset_postdata();
    return;
}

function custom_searchwp_query($query)
{
    $post__not_in = get_query_var('post__not_in');
    if ($post__not_in && is_array($post__not_in))
        $query['where'][] = 'swpwcposts.ID NOT IN (' . implode(',', $post__not_in) . ')';

    return $query;
}

add_filter('searchwp\query', 'custom_searchwp_query', 10, 1);

add_filter('icl_ls_languages', 'wpml_ls_filter');
function wpml_ls_filter($languages)
{
    global $sitepress;
    if ($_SERVER["QUERY_STRING"]) {
        if (strpos(basename($_SERVER['REQUEST_URI']), $_SERVER["QUERY_STRING"]) !== false) {
            foreach ($languages as $lang_code => $language) {
                $languages[$lang_code]['url'] = $languages[$lang_code]['url'] . '?' . $_SERVER["QUERY_STRING"];
            }
        }
    }
    return $languages;
}

function new_row_list_table($columns)
{
    $columns["title_tag"] = "Title tag";
    return $columns;
}

add_filter('manage_edit-post_columns', 'new_row_list_table');
add_filter('manage_edit-page_columns', 'new_row_list_table');
add_filter('manage_edit-company_news_columns', 'new_row_list_table');
add_filter('manage_edit-company_press_columns', 'new_row_list_table');
add_filter('manage_edit-service_columns', 'new_row_list_table');
add_filter('manage_edit-application_columns', 'new_row_list_table');
add_filter('manage_edit-download_columns', 'new_row_list_table');
add_filter('manage_edit-download-file_columns', 'new_row_list_table');
add_filter('manage_edit-reference_columns', 'new_row_list_table');
add_filter('manage_edit-event_columns', 'new_row_list_table');
add_filter('manage_edit-training_columns', 'new_row_list_table');


function value_new_row_list_table($colname, $cptid)
{
    if ($colname == 'title_tag')
        echo get_field('tag_title', $cptid);
}

add_action('manage_post_posts_custom_column', 'value_new_row_list_table', 10, 2);
add_action('manage_page_posts_custom_column', 'value_new_row_list_table', 10, 2);
add_action('manage_company_news_posts_custom_column', 'value_new_row_list_table', 10, 2);
add_action('manage_company_press_posts_custom_column', 'value_new_row_list_table', 10, 2);
add_action('manage_service_posts_custom_column', 'value_new_row_list_table', 10, 2);
add_action('manage_application_posts_custom_column', 'value_new_row_list_table', 10, 2);
add_action('manage_download_posts_custom_column', 'value_new_row_list_table', 10, 2);
add_action('manage_download-file_posts_custom_column', 'value_new_row_list_table', 10, 2);
add_action('manage_reference_posts_custom_column', 'value_new_row_list_table', 10, 2);
add_action('manage_event_posts_custom_column', 'value_new_row_list_table', 10, 2);
add_action('manage_training_posts_custom_column', 'value_new_row_list_table', 10, 2);

function add_post_object_date($title, $post, $field, $post_id)
{
    $tag = " - [" . get_field('tag_title', $post->ID) . "]";
    if (!empty(get_field('tag_title', $post->ID))) {
        $title = "$title $tag";
    } else {
        $title = "$title";
    }

    return $title;
}

add_filter('acf/fields/post_object/result', 'add_post_object_date', 10, 4);


function custom_meta_box_markup($object)
{
    global $wpdb;
    $rsContactPages = $wpdb->get_results("SELECT ID from {$wpdb->prefix}posts WHERE post_name = 'contact' AND post_parent = 0");
    $contactPages = [];
    if ($rsContactPages) {
        foreach ($rsContactPages as $page) {
            $contactPages[] = $page->ID;
        }
    }

    if (in_array($object->post_parent, $contactPages)) :
        wp_nonce_field(basename(__FILE__), "meta-box-nonce");
        ?>
        <style type="text/css">
            #group-sugestion {
                position: absolute;
                left: 0;
                top: 100%;
                min-width: 300px;
                max-width: 100%;
                max-height: 300px;
                overflow: auto;
                box-shadow: 0 0 5px rgba(0, 0, 0, .3);
                background: #fff;
                padding: 15px;
            }

            #group-sugestion ul {
                margin: 0 -15px;
            }

            #group-sugestion ul li {
                cursor: pointer;
                padding: 5px 15px;
                margin: 0;
            }

            #group-sugestion ul li:hover {
                background: #f1f1f1;
            }
        </style>
        <div class="acf-field" style="z-index: 9999">
            <input type="text" id="group-selection" name="contact_group" placeholder="Group Name"
                   value="<?php echo get_post_meta($object->ID, "contact_group", true); ?>">
            <div id="group-sugestion" style="display: none">Loading...</div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var xhr = null;
                var timeout = null;
                $(document).on('input', '#group-selection', function () {
                    var _this = $(this);
                    if (xhr != null) {
                        xhr.abort();
                    }
                    if (timeout != null) {
                        clearTimeout(timeout);
                    }
                    timeout = setTimeout(function () {
                        $('#group-sugestion').html('<span>Loading...</span>').show();
                        xhr = $.ajax({
                            url: ajaxurl,
                            type: "POST",
                            data: {
                                action: 'get_contact_group',
                                group_name: _this.val()
                            },
                            success: function (response) {
                                $('#group-sugestion').html(response);
                                $('#group-sugestion li').click(function () {
                                    _this.val($(this).text());
                                });
                            }
                        });
                    }, 1000);
                });

                $(document).click(function () {
                    if ($('#group-sugestion').length) {
                        $('#group-sugestion').html('<span>Loading...</span>').hide();
                    }
                });
            });
        </script>
    <?php
    else :
        ?>
        <style type="text/css">#contact-group-meta-box {
                display: none
            }</style>
    <?php
    endif;
}

function add_custom_meta_box()
{
    add_meta_box("contact-group-meta-box", "Group", "custom_meta_box_markup", "page", "normal", "high", null);
}

add_action("add_meta_boxes", "add_custom_meta_box");

function save_custom_meta_box($post_id, $post, $update)
{
    global $wpdb;
    $rsContactPages = $wpdb->get_results("SELECT ID from {$wpdb->prefix}posts WHERE post_name = 'contact' AND post_parent = 0");
    $contactPages = [];
    if ($rsContactPages) {
        foreach ($rsContactPages as $page) {
            $contactPages[] = $page->ID;
        }
    }

    if (!in_array($post->post_parent, $contactPages))
        return $post_id;

    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if (!current_user_can("edit_post", $post_id))
        return $post_id;

    if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    if (!empty($_POST["contact_group"])) {
        update_post_meta($post_id, "contact_group", trim($_POST["contact_group"]));
    }

}

add_action("save_post", "save_custom_meta_box", 10, 3);

function get_verification_user_link($user_id): ?string
{
    $verification_key = md5(uniqid());
    update_user_meta($user_id, 'verification_key', $verification_key);

    return add_query_arg(array(
        'verify-customer' => true,
        'key' => $verification_key,
        'data' => $user_id,
    ), home_url(''));
}

function get_reset_password_user_link($user_id): ?string
{
    $user_data = get_user_by('ID', $user_id);

    $reset_key = get_password_reset_key($user_data);
    $reset_password_link = esc_url(add_query_arg(
        array(
            'key' => $reset_key,
            'id' => $user_id,
            'custom_action' => base64_encode('verify_customer_cabling'),
        ),
        wc_get_endpoint_url('lost-password', '', wc_get_page_permalink('myaccount'))
    ));

    return $reset_password_link ?? '';
}

function send_email_reset_password($user_id)
{
    $user_data = get_user_by('ID', $user_id);

    if ($user_data) {
        $reset_key = get_password_reset_key($user_data);
        $reset_password_link = esc_url(add_query_arg(
            array(
                'key' => $reset_key,
                'id' => $user_id,
            ),
            wc_get_endpoint_url('lost-password', '', wc_get_page_permalink('myaccount'))
        ));

        $mailer = WC()->mailer();
        $mailer->recipient = $user_data->user_email;
        $type = 'emails/verify-child-account.php';
        $subject = __("Hi! Please set your password", 'cabling');
        $content = cabling_get_custom_email_html($reset_password_link, $subject, $mailer, $type);
        $headers = "Content-Type: text/html\r\n";

        $mailer->send($user_data->user_email, $subject, $content, $headers);
    }
}

function send_email_verified_success($user_id)
{
    $user_data = get_user_by('ID', $user_id);

    if ($user_data) {
        $mailer = WC()->mailer();
        $mailer->recipient = $user_data->user_email;
        $type = 'emails/verified-level-2-account.php';
        $blogname = get_bloginfo('name');
        $subject = get_field('subject_email_verified', 'options');
        $subject = str_replace('!!site_name!!', $blogname, $subject);
        $my_account_link = wc_get_account_endpoint_url('dashboard');
        $content = cabling_get_custom_email_html($my_account_link, $subject, $mailer, $type);
        $headers = "Content-Type: text/html\r\n";

        $mailer->send($user_data->user_email, $subject, $content, $headers);
    }
}

function custom_reset_password_redirect($redirect_to, $request, $user)
{
    // Check if the user has reset their password successfully
    if (!is_wp_error($user) && isset($_POST['action']) && $_POST['action'] == 'resetpass') {

        // Redirect the user to a custom page after a successful password reset
        $redirect_to = wc_get_account_endpoint_url('dashboard');
    }
    return $redirect_to;
}

add_filter('login_redirect', 'custom_reset_password_redirect', 10, 3);

// Adjust the password reset link expiration time to 30 minutes (in seconds)
// // JM 20230913 removed user_id as it was giving an error
function custom_password_reset_expiration($expiration)
{ //, $user_id) {
    return 1800; // 30 minutes in seconds
}

add_filter('password_reset_expiration', 'custom_password_reset_expiration', 10, 2);


function custom_user_filter($query)
{
    if (is_admin() && !empty($_GET['customer_level'])) {
        $filter_value = sanitize_text_field($_GET['customer_level']);
        if ($filter_value === '2') {
            $query->set('meta_query', array(
                'relation' => 'OR',
                array(
                    'key' => 'customer_level',
                    'value' => '2',
                    'compare' => '=',
                ),
                array(
                    'key' => 'has_approve',
                    'value' => 'true',
                    'compare' => '=',
                ),
            ));
        } else {
            $query->set('meta_query', array(
                array(
                    'key' => 'customer_level',
                    'value' => '2',
                    'compare' => '!=',
                ),
            ));
        }
    }
}

add_action('pre_get_users', 'custom_user_filter');

add_filter("views_users", function ($view) {
    $level = $_GET['customer_level'] ?? '';
    $view['customer_level_1'] = "<a href='users.php?customer_level=1' class='" . ($level === '1' ? 'current' : '') . "'>Customer Level 1</a>";
    $view['customer_level_2'] = "<a href='users.php?customer_level=2' class='" . ($level === '2' ? 'current' : '') . "''>Customer Level 2</a>";

    return $view;
}, 10, 1);

function remove_zero_number($inputString)
{
    if (str_starts_with($inputString, "0")) {
        $inputString = substr($inputString, 1);
    }
    return $inputString;
}

function get_user_telephone_number($user): string
{
    $phone_code = get_user_meta($user, 'user_telephone_code', true);
    $phone = get_user_meta($user, 'user_telephone', true);

    return sprintf('+%s%s', $phone_code, remove_zero_number($phone));
}

function get_user_phone_number($user): string
{
    $phone_code = get_user_meta($user, 'billing_phone_code', true);
    $phone = get_user_meta($user, 'billing_phone', true);

    return sprintf('+%s%s', $phone_code, remove_zero_number($phone));
}

add_filter('login_form_middle', 'cabling_login_form_middle');
function cabling_login_form_middle($content)
{
    return '<p class="form-group">
				<div class="g-recaptcha" data-sitekey="' . get_field('gcapcha_sitekey_v2', 'option') . '"></div>
			</p>';
}

function cabling_reset_form_middle()
{
    echo '<p class="form-group">
				<div id="recaptcha" class="g-recaptcha" data-sitekey="' . get_field('gcapcha_sitekey_v2', 'option') . '"></div>
			</p>';
}

add_filter('woocommerce_lostpassword_form', 'cabling_reset_form_middle');
add_action('woocommerce_login_form', 'cabling_reset_form_middle');

// Log password changes
function execute_on_profile_password_reset_event($user, $new_pass)
{
    $data = json_encode(array(
        'user_pass' => true
    ));
    log_customer_change($user->ID, $user->ID, $data);
}

add_action("password_reset", "execute_on_profile_password_reset_event", 10, 2);
function execute_on_profile_check_passwords_event($bool, $user)
{
    $user_id = get_current_user_id() ?: $user['ID'];
    $data = json_encode(array(
        'user_pass' => true
    ));

    log_customer_change($user_id, $user['ID'], $data);
    return $bool;
}

add_filter("send_password_change_email", "execute_on_profile_check_passwords_event", 10, 2);
function comments_open_for_blog($open, $post_id)
{
    return in_array(get_post_type($post_id), ['post']) ? true : $open;
}

add_filter("comments_open", "comments_open_for_blog", 10, 2);

// Add the reCAPTCHA widget to the comment form
function add_recaptcha_to_comment_form($fields)
{
    if (isset($fields['url'])) {
        unset($fields['url']);
    }
    $fields['recaptcha'] = '<div class="g-recaptcha" data-sitekey="' . get_field('gcapcha_sitekey_v2', 'option') . '"></div>';

    return $fields;
}

add_filter('comment_form_default_fields', 'add_recaptcha_to_comment_form');

// Verify reCAPTCHA on comment submission
function verify_recaptcha_on_comment_submit($comment_data)
{
    $verify_recaptcha = cabling_verify_recaptcha($_POST['g-recaptcha-response']);

    if (empty($verify_recaptcha)) {
        wp_die(__('reCAPTCHA verification failed. Please try again!', 'cabling'));
    }


    return $comment_data;
}

add_filter('preprocess_comment', 'verify_recaptcha_on_comment_submit');
function get_edit_user_link_custom()
{
    $my_account_link = wc_get_account_endpoint_url('dashboard');

    return esc_url($my_account_link);
}

//add_filter('get_edit_user_link', 'get_edit_user_link_custom');

/**
 * Add a "Password Change Log" section to the user profile.
 *
 * @param WP_User $user The user object.
 */
function add_password_change_log_section($user)
{
    // Check if the current user can edit this user's profile.
    if (current_user_can('edit_user', $user->ID)) {

        // Retrieve password change logs for this user.
        $logs = get_customer_log($user->ID);

        if (!empty($logs)) {
            echo '<h3>Password Change Log</h3>';
            echo '<table class="form-table">';
            foreach ($logs as $key => $log) {
                echo '<tr>';
                echo '<th>' . ($key + 1) . '.</th>';
                echo '<td>by UserId ' . $log->change_by_id . ' at ' . esc_html($log->change_date) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
    }
}

add_action('show_user_profile', 'add_password_change_log_section');
function bbp_new_topic_pre_insert_hook_callback($data)
{
    if (current_user_can('manage_options'))
        return $data;

    $data['post_status'] = bbp_get_pending_status_id();

    return $data;
}

add_filter('bbp_new_topic_pre_insert', 'bbp_new_topic_pre_insert_hook_callback', 10, 1);
add_filter('bbp_new_reply_pre_insert', 'bbp_new_topic_pre_insert_hook_callback', 10, 1);

function bbp_template_notices_callback()
{
    if (current_user_can('manage_options'))
        return;
    ob_start(); ?>
    <div class="bbp-template-notice">
        <ul>
            <li><?php esc_html_e('Your topic/reply will be moderated before being published.', 'cabling'); ?></li>
        </ul>
    </div>
    <?php
    echo ob_get_clean();
}

add_action('bbp_template_notices', 'bbp_template_notices_callback');
function bbp_new_topic_redirect_to_callback($redirect_url, $redirect_to, $topic_id)
{
    if (current_user_can('manage_options'))
        return $redirect_url;
    $forum_id = bbp_get_topic_forum_id($topic_id);
    return bbp_get_forum_permalink($forum_id);
}

add_filter('bbp_new_topic_redirect_to', 'bbp_new_topic_redirect_to_callback', 10, 3);
function bbp_get_forum_post_type_labels_callback($labels)
{
    $labels['name'] = esc_attr__('Areas', 'cabling');
    $labels['menu_name'] = esc_attr__('Forum Areas', 'cabling');
    $labels['singular_name'] = esc_attr__('Area', 'cabling');

    return $labels;
}

add_filter('bbp_get_forum_post_type_labels', 'bbp_get_forum_post_type_labels_callback');

function bbp_get_topic_post_type_labels_callback($labels)
{
    $labels['menu_name'] = esc_attr__('Forum Topics', 'cabling');

    return $labels;
}

add_filter('bbp_get_topic_post_type_labels', 'bbp_get_topic_post_type_labels_callback');
function bbp_get_reply_post_type_labels_callback($labels)
{
    $labels['menu_name'] = esc_attr__('Forum Replies', 'cabling');

    return $labels;
}

add_filter('bbp_get_reply_post_type_labels', 'bbp_get_reply_post_type_labels_callback');

function cabling_show_footer_brands()
{
    $brands = get_terms(array(
        'taxonomy' => 'product-brand',
        'hide_empty' => 0
    ));
    $html = '';
    if ($brands) {
        $html .= '<div class="d-flex just">';
        foreach ($brands as $brand) {
            $logo = get_field('taxonomy_image', $brand);
            $logo_img = empty($logo) ? '' : wp_get_attachment_image($logo, 'full');
            $html .= '<div class="brand">' . $logo_img . '</div>';
        }
        $html .= '</div>';
    }
    return $html;
}

add_action('bbp_template_before_single_forum', 'cabling_bbp_template_before_single_forum_callback');
add_action('bbp_template_before_single_reply', 'cabling_bbp_template_before_single_forum_callback');
add_action('bbp_template_before_single_topic', 'cabling_bbp_template_before_single_forum_callback');
function cabling_bbp_template_before_single_forum_callback()
{
    if (function_exists('bbp_get_template_part'))
        bbp_get_template_part('form', 'search');
}

function cabling_verify_recaptcha($recaptcha_response = '')
{
    $recaptcha_secret = get_field('gcapcha_secret', 'option');

    $verification_url = "https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response";
    $verification_response = json_decode(file_get_contents($verification_url));

    return $verification_response->success;
}

add_filter('excerpt_length', function () {
    return 30;
});

function cabling_comment_callback($comment, $args, $depth)
{
    $tag = ('div' === $args['style']) ? 'div' : 'li';

    $commenter = wp_get_current_commenter();
    $show_pending_links = !empty($commenter['comment_author']);

    if ($commenter['comment_author_email']) {
        $moderation_note = __('Your comment is awaiting moderation.');
    } else {
        $moderation_note = __('Your comment is awaiting moderation. This is a preview; your comment will be visible after it has been approved.');
    }
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class($comment->post_parent ? 'parent' : '', $comment); ?>>
    <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
        <footer class="comment-meta">
            <div class="comment-author vcard">
                <?php
                if (0 != $args['avatar_size']) {
                    echo get_avatar($comment, $args['avatar_size']);
                }
                ?>
                <?php
                $comment_author = get_comment_author_link($comment);

                if ('0' == $comment->comment_approved && !$show_pending_links) {
                    $comment_author = get_comment_author($comment);
                }

                printf(
                /* translators: %s: Comment author link. */
                    __('%s <span class="says">says:</span>'),
                    sprintf('<b class="fn">%s</b>', $comment_author)
                );
                ?>
                <div class="comment-time"><?php printf(__('%s ago', 'cabling'), human_time_diff(get_comment_time('U'), current_time('timestamp'))); ?></div>
                <?php
                if ('1' == $comment->comment_approved || $show_pending_links) {
                    comment_reply_link(
                        array_merge(
                            $args,
                            array(
                                'add_below' => 'div-comment',
                                'depth' => $depth,
                                'max_depth' => $args['max_depth'],
                                'before' => '<div class="reply">',
                                'after' => '</div>',
                            )
                        )
                    );
                }
                ?>
            </div><!-- .comment-author -->

            <div class="comment-metadata">
                <?php
                edit_comment_link(__('Edit'), ' <span class="edit-link">', '</span>');
                ?>
            </div><!-- .comment-metadata -->

            <?php if ('0' == $comment->comment_approved) : ?>
                <em class="comment-awaiting-moderation"><?php echo $moderation_note; ?></em>
            <?php endif; ?>
        </footer><!-- .comment-meta -->

        <div class="comment-content">
            <?php comment_text(); ?>
        </div><!-- .comment-content -->
    </article><!-- .comment-body -->
    <?php
}

function comment_form_defaults_custom($args)
{
    $required_indicator = ' ' . wp_required_field_indicator();
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $html5 = 'html5' === $args['format'];

    // Define attributes in HTML5 or XHTML syntax.
    $required_attribute = ($html5 ? ' required' : ' required="required"');

    $args['title_reply'] = __('LEAVE A COMMENT', 'cabling');
    $args['title_reply_to'] = __('LEAVE A COMMENT TO %s', 'cabling');
    $args['submit_button'] = '<input name="%1$s" type="submit" id="%2$s" class="%3$s btn-red" value="%4$s" />';
    $args['comment_field'] = sprintf(
        '<p class="comment-form-comment">%s %s</p>',
        sprintf(
            '<label for="comment" class="hidden">%s%s</label>',
            _x('Comment', 'noun'),
            $required_indicator
        ),
        sprintf(
            '<textarea id="comment" name="comment" cols="45" rows="8" placeholder="%s" maxlength="65525"' . $required_attribute . '></textarea>',
            _x('Type your message here', 'cabling'),
        )
    );
    $args['fields']['email'] = sprintf(
        '<p class="comment-form-email">%s %s</p>',
        sprintf(
            '<label for="email" class="hidden">%s%s</label>',
            __('Email'),
            ($req ? $required_indicator : '')
        ),
        sprintf(
            '<input id="email" name="email" %s value="%s" size="30" maxlength="100" aria-describedby="email-notes" autocomplete="email" %s placeholder="%s" />',
            ($html5 ? 'type="email"' : 'type="text"'),
            esc_attr($commenter['comment_author_email']),
            ($req ? $required_attribute : ''),
            _x('Email Address*', 'cabling'),
        )
    );
    $args['fields']['author'] = sprintf(
        '<p class="comment-form-author">%s %s</p>',
        sprintf(
            '<label for="author" class="hidden">%s%s</label>',
            __('Name'),
            ($req ? $required_indicator : '')
        ),
        sprintf(
            '<input id="author" name="author" type="text" value="%s" size="30" maxlength="245" autocomplete="name" %s placeholder="%s" />',
            esc_attr($commenter['comment_author']),
            ($req ? $required_attribute : ''),
            _x('Name*', 'cabling'),
        )
    );

    return $args;
}

add_filter('comment_form_defaults', 'comment_form_defaults_custom');

/**
 * @param int $post_id
 * @param string $custom_post_type
 * @param string $taxonomy
 * @param string $tag_taxonomy
 * @return array|null
 */
function cabling_get_post_related(int $post_id = 0, string $custom_post_type = 'post', string $taxonomy = 'category', string $tag_taxonomy = 'post_tag'): ?array
{
    $related_args = array(
        'post_type' => $custom_post_type,
        'post__not_in' => array($post_id),
        'tax_query' => array(
            'relation' => 'OR',
        ),
        'posts_per_page' => 4,
        'orderby' => 'rand',
    );
    $terms = get_the_terms($post_id, $taxonomy);
    $tags = get_the_terms($post_id, $tag_taxonomy);

    if ($terms) {
        $term_ids = wp_list_pluck($terms, 'term_id');
        $related_args['tax_query'][] = array(
            'taxonomy' => $taxonomy,
            'field' => 'id',
            'terms' => $term_ids,
        );
    }

    if ($tags) {
        $tag_ids = wp_list_pluck($tags, 'term_id');
        $related_args['tax_query'][] = array(
            'taxonomy' => $tag_taxonomy,
            'field' => 'id',
            'terms' => $tag_ids,
        );
    }

    $related_query = get_posts($related_args);

    return $related_query ?? null;
}


/**
 * @param bool|int $post_id
 * @param string $taxonomy
 * @return string
 */
function getPostCategory(bool|int $post_id, string $taxonomy = 'category'): string
{
    $categories = get_the_terms($post_id, $taxonomy);
    $cat = '';

    if ($categories) {
        $list = array();
        foreach ($categories as $category) {
            $list[] = $category->name;
        }
        $cat = implode(', ', $list);
    }
    return $cat;
}

function custom_pre_get_posts($query): void
{
    if ($query->is_main_query()) {
        if ($query->is_home()) {
            $query->set('category_name', 'blog');
            $query->set('posts_per_page', 6);
            if (isset($_GET['order'])) {
                $order = $_GET['order'] === 'oldest' ? 'asc' : 'desc';
                $query->set('order', $order);
            }
        }
    }
}

add_action('pre_get_posts', 'custom_pre_get_posts');

function get_post_type_name(string $post_type): ?string
{
    return match ($post_type) {
        'post' => __('Blog', 'cabling'),
        'gi_learn' => __('Learn', 'cabling'),
        'company_news' => __('News', 'cabling'),
        'compound' => __('Compounds', 'cabling'),
        default => __('Post', 'cabling'),
    };
}

add_filter('bbp_show_lead_topic', '__return_true');

function get_topic_related()
{
    $topic_id = bbp_get_topic_id();

    $tags = bbp_get_topic_tag_list($topic_id);
    $forum_id = bbp_get_topic_forum_id($topic_id);

    if ($tags) {

        $args = array(
            'post_type' => bbp_get_topic_post_type(),
            'post_status' => 'publish',
            'posts_per_page' => 8,
            'post__not_in' => array($topic_id),
            'post_parent' => $forum_id,
        );

        $related_topics_query = new WP_Query($args);

        // Check if there are related topics
        if ($related_topics_query->have_posts()) {
            echo '<div class="related-topics alignfull">';
            echo '<div class="container">';
            echo '<h2 class="pre-heading heading-center">' . __('RELATED CONVERSATIONS', 'cabling') . '</h2>';
            echo '<div class="row">';
            while ($related_topics_query->have_posts()) {
                $related_topics_query->the_post();
                $id = get_the_ID(); ?>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="bbp-forum-box">
                        <a class="bbp-forum-title" href="<?php bbp_topic_permalink($id); ?>"
                           title="<?php bbp_topic_title($id); ?>">
                            <h4><?php bbp_topic_title($id); ?></h4>
                        </a>
                        <div class="bbp-forum-count">
                            <i class="fa-light fa-messages me-2"></i>
                            <?php printf(__('%s Conversations', 'cabling'), bbp_get_topic_reply_count($id)); ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        // Restore original post data
        wp_reset_postdata();
    }

}

function get_filter_arg_forums(): array
{
    $args = array();
    if (!empty($_GET['filter'])) {
        switch ($_GET['filter']) {
            case 'alphabetical':
                $args['orderby'] = 'title';
                $args['order'] = 'asc';
                break;
            case 'popular':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_bbp_total_topic_count';
                $args['order'] = 'desc';
                break;
            case 'latest':
                $args['orderby'] = 'meta_value';
                $args['meta_key'] = '_bbp_last_active_time';
                $args['order'] = 'desc';
                break;
            case 'featured':
                $args['meta_key'] = 'is_featured';
                $args['meta_value'] = 'yes';
                break;
        }
    }
    if (!empty($_GET['category'])) {
        $args['p'] = $_GET['category'];
    }

    return $args;
}

function getCompoundRelated($postId = 0): array
{
    return get_posts(array(
        'post_type' => 'compound',
        'posts_per_page' => -1,
        'exclude' => [$postId],
        'fields' => 'ids',
    ));
}

function get_compound_product($term_ids): array
{
    $args = array(
        'post_type' => 'compound',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'compound_certification',
                'field' => 'id',
                'terms' => is_array($term_ids) ? $term_ids : [$term_ids],
            )
        ),
    );
    return get_posts($args);
}

function checkFilterHasSize($attributes): bool
{
    $size = array(
        'nominal_size_id',
        'nominal_size_od',
        'nominal_size_width',
        'inches_id',
        'inches_od',
        'inches_id_tol',
        'inches_width',
        'inches_width_tol',
        'milimeters_id',
        'milimeters_od',
        'milimeters_id_tol',
        'milimeters_width_tol',
    );
    foreach ($attributes as $key => $attribute){
        if(in_array($key, $size) && !empty($attribute)){
            return true;
        }
    }

    return false;
}

/**
 * José Martins 2024-02-14
 * Retrieves post by slug
 */
function get_post_id_by_slug( $slug, $post_type = "post" ) {
    $query = new WP_Query(
        array(
            'name'   => $slug,
            'post_type'   => $post_type,
            'numberposts' => 1,
            'fields'      => 'ids',
        ) );
    $posts = $query->get_posts();
    return array_shift( $posts );
}


function custom_autofill_data( $scanned_tag, $replace ) {
    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();

        switch ( $scanned_tag['name'] ){
            case 'your-name':
                $scanned_tag['values'] = [$current_user->display_name];
                break;
            case 'your-email':
                $scanned_tag['values'] = [$current_user->user_email];
                break;
            case 'your-title':
                $scanned_tag['values'] = [get_user_meta($current_user->ID, 'user_title', true)];
                break;
            case 'your-company-sector':
                $scanned_tag['values'] = [get_user_meta($current_user->ID, 'billing_company', true)];
                break;
            case 'your-phone':
                 $scanned_tag['values'] = [get_user_phone_number($current_user->ID)];
                break;
        }
        return $scanned_tag;
    }

    // If user is not logged in, return the original form
    return $scanned_tag;
}

add_filter( 'wpcf7_form_tag', 'custom_autofill_data', 10 , 2 );

function my_custom_action_after_form_submission($contact_form) {
    // Get the submitted form data
    $submission = WPCF7_Submission::get_instance();
    if ($submission) {
        // Retrieve the posted data
        $posted_data = $submission->get_posted_data();

        // Get specific fields from the posted data
        $pre_name = $posted_data['your-pre-name'];
        $name = $posted_data['your-name'];
        $title = $posted_data['your-title'];
        $company_sector = $posted_data['your-company-sector'];
        $email = $posted_data['your-email'];
        $phone = $posted_data['your-phone'];
        $product = $posted_data['your-product'];
        $message = $posted_data['your-message'];

        // Now you can perform custom actions with this data
        // For example, you can send an email, save data to the database, etc.
        // Here, we're just logging the submitted data to the WordPress debug log
        error_log('Submitted form data:');
        error_log('Pre Name: ' . $pre_name);
        error_log('Name: ' . $name);
        error_log('Title: ' . $title);
        error_log('Company Sector: ' . $company_sector);
        error_log('Email: ' . $email);
        error_log('Phone: ' . $phone);
        error_log('Product: ' . $product);
        error_log('Message: ' . $message);
    }
}
add_action('wpcf7_submission_result', 'my_custom_action_after_form_submission');

