<?php

defined('ABSPATH') || exit;

if (!function_exists('ph_fedex_process_order_meta')) {

    /**
     * Since HPO's WC_Meta_Data object comes while retriving from storage handler, hence we are processing the data before using it.
     *
     * @param mixed $meta_data
     * @param int $order_id
     * @return mixed
     */
    function ph_fedex_process_order_meta($meta_data, $order_id)
    {
        if (empty($meta_data) || !is_array($meta_data)) {
            return $meta_data;
        }

        $processed_meta_data = [];

        foreach ($meta_data as $meta) {

            if (!$meta instanceof WC_Meta_Data) {
                return $meta_data;
            }

            $processed_meta_data[] = $meta->value;
        }

        return $processed_meta_data;
    }

    add_filter('ph_wc_fedex_order_metadata', 'ph_fedex_process_order_meta', 10, 2);
}
