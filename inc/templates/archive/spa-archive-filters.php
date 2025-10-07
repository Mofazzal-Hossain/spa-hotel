<?php
defined('ABSPATH') || exit;

use \Tourfic\Classes\Helper;
use \Tourfic\Classes\Hotel\Pricing;
use \Tourfic\Classes\Room\Room;
use \Tourfic\App\TF_Review;

// Register AJAX actions
add_action('wp_ajax_sht_archive_filter', 'sht_archive_filter_ajax');
add_action('wp_ajax_nopriv_sht_archive_filter', 'sht_archive_filter_ajax');

function sht_archive_filter_ajax()
{

    // Check nonce
    if (empty($_POST['_nonce']) || ! wp_verify_nonce(sanitize_text_field($_POST['_nonce']), 'tf_ajax_nonce')) {
        wp_send_json_error(['message' => 'Invalid nonce']);
    }

    // Initialize WP_Query args
    $args = [
        'post_type'      => 'tf_hotel',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    ];

    // Get filters
    $features   = !empty($_POST['features']) ? explode(',', sanitize_text_field($_POST['features'])) : [];
    $facilities = !empty($_POST['facilities']) ? explode(',', sanitize_text_field($_POST['facilities'])) : [];
    $ratings    = !empty($_POST['ratings']) ? explode(',', sanitize_text_field($_POST['ratings'])) : [];
    $score      = !empty($_POST['score']) ? explode(',', sanitize_text_field($_POST['score'])) : [];
    $taxonomy = !empty($_POST['taxonomy']) ? sanitize_text_field($_POST['taxonomy']) : '';
    $term     = !empty($_POST['term']) ? sanitize_text_field($_POST['term']) : '';


    $tax_query = [];

    // taxonomy filter
    if ($taxonomy != 'undefined' && $term != 'undefined') {
        if ($taxonomy && $term) {
            $tax_query[] = [
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $term,
            ];
        }
    }

    // features filter
    if ($features) {
        $tax_query[] = [
            'taxonomy' => 'hotel_feature',
            'field'    => 'term_id',
            'terms'    => $features,
            'operator' => 'IN',
        ];
    }


    // facilities filter
    if ($facilities) {
        $tax_query[] = [
            'taxonomy' => 'hotel_facilities',
            'field'    => 'term_id',
            'terms'    => $facilities,
            'operator' => 'IN',
        ];
    }

    // WP_Query args
    $args = [
        'post_type'      => 'tf_hotel',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    ];


    if (!empty($score) || !empty($ratings)) {
        $filtered_ids = [];

        // Get all hotels
        $hotels = get_posts([
            'post_type'      => 'tf_hotel',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        ]);

        foreach ($hotels as $post_id) {
            $comments = get_comments([
                'post_id' => $post_id,
                'status'  => 'approve',
                'type'    => 'comment',
            ]);

            $tf_overall_rate = [];
            $total_rating = 0;
            TF_Review::tf_calculate_comments_rating($comments, $tf_overall_rate, $total_rating);
            $rating = floatval($total_rating);

            if (!empty($score)) {
                foreach ($score as $min) {
                    if ($min == 9) {
                        if ($rating >= $min && $rating <= ($min + 1)) {
                            $filtered_ids[] = $post_id;
                            break;
                        }
                    } else {
                        if ($rating >= $min && $rating < ($min + 1)) {
                            $filtered_ids[] = $post_id;
                            break;
                        }
                    }
                }
            }

            if (!empty($ratings)) {
                // Check if post has no rating
                if ($rating <= 0 && in_array('no-rating', $ratings, true)) {
                    $filtered_ids[] = $post_id;
                    continue;
                }

                // For numeric rating filters (1–10)
                foreach ($ratings as $selected) {
                    if ($selected === 'no-rating') {
                        continue;
                    }

                    $selected = intval($selected);

                    $upper_limit = ($selected == 10) ? 10.0 : ($selected + 0.9999);

                    if ($rating >= $selected && $rating <= $upper_limit) {
                        $filtered_ids[] = $post_id;
                        break;
                    }
                }
            }
        }

        // Apply filtered posts to query
        $args['post__in'] = !empty($filtered_ids) ? $filtered_ids : [0];
    }


    // Apply tax_query if any filters exist
    if ($tax_query) {
        $args['tax_query'] = $tax_query;
    }


    $loop = new WP_Query($args);
    $total_posts = $loop->found_posts;

    if ($loop->have_posts()) {
        $posts_html = [];
        $count = 0;
        while ($loop->have_posts()) {
            $loop->the_post();
            $post_id = get_the_ID();
            $meta = get_post_meta(get_the_ID(), 'tf_hotels_opt', true);

            $count++;
            $map = !empty($meta['map']) ? Helper::tf_data_types($meta['map']) : '';

            // Get hotel address
            if (! empty($meta['map']) && Helper::tf_data_types($meta['map'])) {
                $address = ! empty(Helper::tf_data_types($meta['map'])['address']) ? Helper::tf_data_types($meta['map'])['address'] : '';
            }
            // Get featured
            $featured = ! empty($meta['featured']) ? $meta['featured'] : '';
            $featured_badge_text = !empty($meta['featured_text']) ? esc_html($meta['featured_text']) : '';

            // Get hotel features
            $features = ! empty(get_the_terms($post_id, 'hotel_feature')) ? get_the_terms($post_id, 'hotel_feature') : '';
            $features_count = 4;

            $tf_booking_type = '1';
            $tf_booking_url  = $tf_booking_query_url = $tf_booking_attribute = '';
            if (function_exists('is_tf_pro') && is_tf_pro()) {
                $tf_booking_type      = ! empty($meta['booking-by']) ? $meta['booking-by'] : 1;
                $tf_booking_url       = ! empty($meta['booking-url']) ? esc_url($meta['booking-url']) : '';
            }
            if (2 == $tf_booking_type && ! empty($tf_booking_url)) {
                $external_search_info = array(
                    '{adult}'    => ! empty($adult) ? $adult : 1,
                    '{child}'    => ! empty($child) ? $child : 0,
                    '{checkin}'  => ! empty($check_in) ? $check_in : gmdate('Y-m-d'),
                    '{checkout}' => ! empty($check_out) ? $check_out : gmdate('Y-m-d', strtotime('+1 day')),
                    '{room}'     => ! empty($room_selected) ? $room_selected : 1,
                );
                if (! empty($tf_booking_attribute)) {
                    $tf_booking_query_url = str_replace(array_keys($external_search_info), array_values($external_search_info), $tf_booking_query_url);
                    if (! empty($tf_booking_query_url)) {
                        $tf_booking_url = $tf_booking_url . '/?' . $tf_booking_query_url;
                    }
                }
            }

            // rooms
            $rooms = Room::get_hotel_rooms($post_id);
            $room_id = ! empty($rooms) ? $rooms[0]->ID : '';
            $room_meta = get_post_meta($room_id, 'tf_room_opt', true);
            $price_multi_day = ! empty($room_meta['price_multi_day']) ? $room_meta['price_multi_day'] : 0;
            if ($price_multi_day == 1) {
                $price_multi_text = 'night';
            } else {
                $price_multi_text = 'day';
            }
            $min_price_arr = Pricing::instance(get_the_ID())->get_min_price();
            $min_sale_price = !empty($min_price_arr['min_sale_price']) ? $min_price_arr['min_sale_price'] : 0;
            $min_regular_price = !empty($min_price_arr['min_regular_price']) ? $min_price_arr['min_regular_price'] : 0;
            $min_discount_type = !empty($min_price_arr['min_discount_type']) ? $min_price_arr['min_discount_type'] : 'none';
            $min_discount_amount = !empty($min_price_arr['min_discount_amount']) ? $min_price_arr['min_discount_amount'] : 0;

            if ($min_regular_price != 0) {
                $price_html = wc_format_sale_price($min_regular_price, $min_sale_price);
            } else {
                $price_html = wp_kses_post(wc_price($min_sale_price)) . " ";
            }

            $rating_badge = sht_sparator_rating_badge($post_id);

            // Capture template HTML
            ob_start();
            include SHT_HOTEL_TOOLKIT_PATH . 'inc/templates/archive/template-parts/single-item.php';
            $posts_html[] = ob_get_clean();
        }
        wp_reset_postdata();
        // error_log(print_r($posts_html, true));
        wp_send_json_success([
            'posts' => $posts_html,
            'count' => $total_posts,
        ]);
    } else {
        wp_send_json_error(['message' => 'No hotels found']);
    }
}
