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
    $place_location = !empty($_POST['placeLocation']) ? sanitize_text_field($_POST['placeLocation']) : '';
    $checkin     = !empty($_POST['checkin']) ? sanitize_text_field($_POST['checkin']) : '';
    $checkout     = !empty($_POST['checkout']) ? sanitize_text_field($_POST['checkout']) : '';

    //Map Template only
    $mapFilter = !empty($_POST['mapFilter']) ? sanitize_text_field($_POST['mapFilter']) : false;
    $mapCoordinates = !empty($_POST['mapCoordinates']) ? explode(',', sanitize_text_field($_POST['mapCoordinates'])) : [];
    if (!empty($mapCoordinates) && count($mapCoordinates) === 4) {
        list($minLat, $minLng, $maxLat, $maxLng) = $mapCoordinates;
    }

    $tax_query = [];

    // taxonomy filter
    if (!empty($taxonomy) && $taxonomy != 'undefined' && !empty($term) && $term != 'undefined') {
        if (!empty($place_location) && $place_location === $term) {
            $tax_query[] = [
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $term,
            ];
        }else{
            $tax_query[] = [
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => '',
                'include_children' => false,
            ];
        }
    } elseif (!empty($place_location)) {
        $tax_query[] = [
            'taxonomy' => 'hotel_location',
            'field'    => 'slug',
            'terms'    => $place_location,
            'include_children' => false,
        ];
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


            $rooms_meta = [];
            if (! empty($rooms)) {
                foreach ($rooms as $single_room) {
                    $rooms_meta[$single_room->ID] = get_post_meta($single_room->ID, 'tf_room_opt', true);
                }
            }
            $avil_by_date = array_column($rooms_meta, 'avil_by_date');
            $has_availability_control = in_array('1', $avil_by_date, true);
            if ($has_availability_control) {
                $availability_dates = array_column($rooms_meta, 'avail_date');

                $availability = [];
                foreach ((array)$availability_dates as $av) {
                    if (!empty($av)) {
                        $decoded = json_decode($av, true);
                        if (is_array($decoded)) {
                            $availability = array_merge($availability, $decoded);
                        }
                    }
                }
                if (!filter_room_available($checkin, $checkout, $availability)) {
                    continue;
                }
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
            if (!empty($map)) {
                $lat = $map['latitude'];
                $lng = $map['longitude'];
                ob_start();

                include SHT_HOTEL_TOOLKIT_PATH . 'inc/templates/archive/template-parts/single-item.php';
                $infoWindowtext = ob_get_clean();

                $locations[$count] = [
                    'id' => get_the_ID(),
                    'url'      => get_the_permalink(),
                    'lat' => (float)$lat,
                    'lng' => (float)$lng,
                    'price' => base64_encode($price_html),
                    'content' => base64_encode($infoWindowtext)
                ];
            }

            // Capture template HTML
            ob_start();
            include SHT_HOTEL_TOOLKIT_PATH . 'inc/templates/archive/template-parts/single-item.php';
            $posts_html[] = ob_get_clean();
        }
        wp_reset_postdata();
        wp_send_json_success([
            'posts' => $posts_html,
            'count' => $total_posts,
        ]);
    } else {
        wp_send_json_error(['message' => 'No hotels found']);
    }
}


function get_filter_date_range($start, $end)
{
    $dates = [];
    $current = strtotime($start);
    $endTime = strtotime($end);

    while ($current <= $endTime) {
        $dates[] = date('Y/m/d', $current);
        $current = strtotime('+1 day', $current);
    }
    return $dates;
}

function filter_room_available($checkin, $checkout, $availability)
{
    if (empty($checkin) || empty($checkout) || empty($availability)) {
        return false;
    }
    $dates = get_filter_date_range($checkin, $checkout);
    foreach ($dates as $date) {
        if (empty($availability[$date]) || $availability[$date]['status'] !== 'available') {
            return false;
        }
    }

    return true;
}
