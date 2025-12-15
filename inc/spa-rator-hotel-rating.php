<?php

// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\Classes\Helper;
use \Tourfic\Classes\Hotel\Pricing;

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

// Save SpaRator score
function sht_save_sparator_score($post_id)
{
    static $running = false;
    if ($running) return;

    $running = true;

    $badge = sht_sparator_rating_badge($post_id);

    if (preg_match('/SpaRator\s([\d\.]+)/', $badge['text'], $matches)) {
        $rating = floatval($matches[1]);
    } else {
        $rating = 0;
    }

    update_post_meta($post_id, 'sparator_score', $rating);

    $running = false;
}

// Update SpaRator score
add_action('updated_post_meta', function ($meta_id, $post_id, $meta_key, $meta_value) {

    if (get_post_type($post_id) !== 'tf_hotel') {
        return;
    }

    if ($meta_key !== 'tf_hotels_opt') {
        return;
    }

    sht_save_sparator_score($post_id);

}, 10, 4);

// Save post meta
add_action('save_post_tf_hotel', function ($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;

    sht_save_sparator_score($post_id);

});


// add_action('admin_init', function () {

//     if (!current_user_can('manage_options')) return;

//     $hotels = get_posts([
//         'post_type'      => 'tf_hotel',
//         'posts_per_page' => -1,
//         'fields'         => 'ids',
//     ]);

//     foreach ($hotels as $hotel_id) {
//         sht_save_sparator_score($hotel_id);
//     }

// });
