<?php

// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\Classes\Helper;

if (! function_exists('sht_sparator_rating_badge')) {

    /**
     * Get SpaRator badge based on manual meta ratings
     *
     * @param int $post_id
     * @return array ['text' => string, 'class' => string]
     */
    function sht_sparator_rating_badge($post_id)
    {
        // Get r-base (5 or 10)
        $tf_settings_base = !empty(Helper::tfopt('r-base')) ? intval(Helper::tfopt('r-base')) : 10;
        $original_base = 10;

        // Get hotel meta
        $hotel_meta = get_post_meta($post_id, 'tf_hotels_opt', true);

        // Get field definitions
        $tf_hotel_review = !empty(Helper::tf_data_types(Helper::tfopt('r-hotel')))
            ? Helper::tf_data_types(Helper::tfopt('r-hotel'))
            : [];

        $total_sum = 0;
        $total_count = 0;

        // Loop through all fields and collect manual ratings
        foreach ($tf_hotel_review as $field) {

            if (empty($field['r-field-type'])) continue;

            $label = $field['r-field-type'];
            $slug  = sanitize_title($label);
            $meta_key = "rator-{$slug}-score";

            // Get manual stored value
            $value = isset($hotel_meta[$meta_key]) ? floatval($hotel_meta[$meta_key]) : 0;

            if (is_numeric($value)) {

                // Convert original 10-based value into selected base
                if ($tf_settings_base != $original_base) {
                    $value = ($value / $original_base) * $tf_settings_base;
                }

                $total_sum += $value;
                $total_count++;
            }
        }

        // Final average rating
        $rating = $total_count ? ($total_sum / $total_count) : 0;
        $percent = $tf_settings_base ? ($rating / $tf_settings_base) * 100 : 0;

        // Determine badge text + class
        if ($percent >= 90) {
            $text  = sprintf(__('SpaRator %.1f: Exceptional', 'spa-hotel-toolkit'), $rating);
            $class = 'exceptional';

        } elseif ($percent >= 80) {
            $text  = sprintf(__('SpaRator %.1f: Outstanding', 'spa-hotel-toolkit'), $rating);
            $class = 'outstanding';

        } elseif ($percent >= 70) {
            $text  = sprintf(__('SpaRator %.1f: Excellent', 'spa-hotel-toolkit'), $rating);
            $class = 'excellent';

        } elseif ($percent >= 60) {
            $text  = sprintf(__('SpaRator %.1f: Very Good', 'spa-hotel-toolkit'), $rating);
            $class = 'very-good';

        } else {
            $text  = sprintf(__('SpaRator %.1f: Good', 'spa-hotel-toolkit'), $rating);
            $class = 'good';
        }

        return [
            'text'  => $text,
            'class' => $class
        ];
    }
}

    