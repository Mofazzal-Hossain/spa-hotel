<?php

// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\Classes\Helper;
use \Tourfic\Classes\Hotel\Pricing;
use \Tourfic\Classes\Room\Room;

$tf_defult_views = !empty(Helper::tf_data_types(Helper::tfopt('tf-template'))['hotel_archive_view']) ? Helper::tf_data_types(Helper::tfopt('tf-template'))['hotel_archive_view'] : 'list';
$tf_map_settings = !empty(Helper::tfopt('google-page-option')) ? Helper::tfopt('google-page-option') : "default";
$tf_map_api = !empty(Helper::tfopt('tf-googlemapapi')) ? Helper::tfopt('tf-googlemapapi') : '';
$dates = ! empty($_GET['check-in-out-date']) ? $_GET['check-in-out-date'] : '';
$split_dates = explode(' - ', $dates);

$checkin = isset($split_dates[0]) ? $split_dates[0] : '';
$checkout = isset($split_dates[1]) ? $split_dates[1] : '';

$current_term = get_queried_object();
$child_terms = [];
if ($current_term && isset($current_term->term_id)) {
    // Get all child terms of current parent term
    $child_terms = get_terms([
        'taxonomy'   => 'hotel_location',
        'parent'     => $current_term->term_id,
        'hide_empty' => false,
    ]);
}




?>

<div class="tf-archive-details sht-sec-space">
    <div class="tf-container">
        <div class="tf-archive-details-content">
            <div class="tf-sidebar">
                <?php if (is_active_sidebar('tf_archive_booking_sidebar')) { ?>
                    <div id="tf__booking_sidebar" class="tf-booking-sidebar">
                        <div class="tf-filter-title">
                            <div class="tf-section-title"><?php echo esc_html__("Filter", "spa-hotel-toolkit"); ?></div>
                            <button class="sht-sidebar-reset"><?php echo esc_html__("Reset", "spa-hotel-toolkit"); ?></button>
                        </div>
                        <?php dynamic_sidebar('tf_archive_booking_sidebar'); ?>
                    </div>
                    <?php
                    $queried_object = get_queried_object();
                    if (!empty($queried_object) && isset($queried_object->taxonomy) && isset($queried_object->slug)) :
                    ?>
                        <input type="hidden" class="tf-archive-taxonomy" name="taxonomy" value="<?php echo esc_attr($queried_object->taxonomy); ?>">
                        <input type="hidden" class="tf-archive-slug" name="term" value="<?php echo esc_attr($queried_object->slug); ?>">
                    <?php endif;
                    ?>
                <?php } ?>
            </div>

            <!--Available rooms start -->
            <div class="sht-hotels-wrapper tf-archive-hotels archive_ajax_result <?php echo $tf_defult_views == "list" ? esc_attr('tf-layout-list') : esc_attr('tf-layout-grid'); ?>">
                <div class="sht-archive-view">
                    <button class="list-view active"><?php echo esc_html__('List ', 'spa-hotel-toolkit'); ?></button>
                    <button class="map-view"><?php echo esc_html__('Map ', 'spa-hotel-toolkit'); ?></button>
                </div>
                <div class="sht-list-view active">
                    <div class="sht-hotels-content">
                        <?php
                        $count = 0;
                        $locations = [];
                        while ($hotels->have_posts()) {
                            $hotels->the_post();

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
                                if (!is_room_available($checkin, $checkout, $availability)) {
                                    continue;
                                }
                            }

                            $rating_badge = sht_sparator_rating_badge($post_id);

                            if (!empty($map)) {
                                $lat = $map['latitude'];
                                $lng = $map['longitude'];
                                ob_start();
                        ?>

                                <?php include SHT_HOTEL_TOOLKIT_PATH . 'inc/templates/archive/template-parts/single-item.php'; ?>
                            <?php
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


                            include SHT_HOTEL_TOOLKIT_PATH . 'inc/templates/archive/template-parts/single-item.php';
                            ?>

                        <?php
                        }
                        wp_reset_query();
                        ?>
                    </div>
                    <div id="map-datas" style="display: none"><?php echo array_filter($locations) ? wp_json_encode(array_values($locations)) : wp_json_encode([]); ?></div>

                    <!-- child term posts -->
                    <div class="tf-archive-children-wrapper">
                        <?php

                        if (!empty($child_terms) && !is_wp_error($child_terms)) {
                            // Loop through each child term
                            foreach ($child_terms as $child) : ?>
                                <div class="tf-archive-children">
                                    <div class="spa-heading-wrap">
                                        <h4 class="spa-title"><?php echo esc_html(esc_html($child->name)); ?></h4>
                                        <?php if (!empty($child->description)): ?>
                                            <p><?php echo esc_html(esc_html($child->description)); ?></p>
                                        <?php endif; ?>
                                    </div>

                            <?php
                                $child_posts_query = new WP_Query([
                                    'post_type'      => 'tf_hotel',
                                    'post_status'    => 'publish',
                                    'posts_per_page' => -1,
                                    'tax_query'      => [
                                        [
                                            'taxonomy' => 'hotel_location',
                                            'field'    => 'term_id',
                                            'terms'    => $child->term_id,
                                            'include_children' => false,
                                        ],
                                    ],
                                ]);

                                if ($child_posts_query->have_posts()) {
                                    echo '<div class="sht-archive-child-content">';
                                    while ($child_posts_query->have_posts()) {
                                        $child_posts_query->the_post();

                                        // Include your single-item template for each post
                                        include SHT_HOTEL_TOOLKIT_PATH . 'inc/templates/archive/template-parts/single-item.php';
                                    }
                                    echo '</div>';
                                } else {
                                    echo '<p>No hotels found in this location.</p>';
                                }
                                wp_reset_postdata();
                                echo '</div>';
                            endforeach;
                        } ?>
                                </div>
                    </div>

                    <div class="sht-map-view">
                        <?php if ($tf_map_settings == "googlemap") : ?>

                            <?php if (empty($tf_map_api)) : ?>
                                <div class="tf-notice tf-mt-24 tf-mb-30 tf-center">
                                    <?php if (current_user_can('administrator')) : ?>
                                        <p>
                                            <?php echo esc_html__('Google Maps is selected but the API key is missing. Please configure the API key.', 'spa-hotel-toolkit'); ?>
                                            <a href="<?php echo esc_url(admin_url('admin.php?page=tf_settings#tab=map_settings')); ?>" target="_blank">
                                                <?php esc_html_e('Map Settings', 'spa-hotel-toolkit'); ?>
                                            </a>
                                        </p>
                                    <?php else : ?>
                                        <p>
                                            <?php esc_html_e('Access is restricted as Google Maps API key is not configured. Please contact the site administrator.', 'spa-hotel-toolkit'); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            <?php else : ?>
                                <div class="tf-map-view-inner">
                                    <a href="" class="tf-mobile-list-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M1.33398 7.59935C1.33398 6.82717 1.49514 6.66602 2.26732 6.66602H13.734C14.5062 6.66602 14.6673 6.82717 14.6673 7.59935V8.39935C14.6673 9.17153 14.5062 9.33268 13.734 9.33268H2.26732C1.49514 9.33268 1.33398 9.17153 1.33398 8.39935V7.59935Z"
                                                stroke="#FEF9F6" stroke-linecap="round" />
                                            <path d="M1.33398 2.26634C1.33398 1.49416 1.49514 1.33301 2.26732 1.33301H13.734C14.5062 1.33301 14.6673 1.49416 14.6673 2.26634V3.06634C14.6673 3.83852 14.5062 3.99967 13.734 3.99967H2.26732C1.49514 3.99967 1.33398 3.83852 1.33398 3.06634V2.26634Z"
                                                stroke="#FEF9F6" stroke-linecap="round" />
                                            <path d="M1.33398 12.9333C1.33398 12.1612 1.49514 12 2.26732 12H13.734C14.5062 12 14.6673 12.1612 14.6673 12.9333V13.7333C14.6673 14.5055 14.5062 14.6667 13.734 14.6667H2.26732C1.49514 14.6667 1.33398 14.5055 1.33398 13.7333V12.9333Z"
                                                stroke="#FEF9F6" stroke-linecap="round" />
                                        </svg>
                                        <span><?php echo esc_html__('List view', 'spa-hotel-toolkit') ?></span>
                                    </a>
                                    <div id="map-marker" data-marker="<?php echo esc_url(TF_ASSETS_URL . 'app/images/cluster-marker.png'); ?>"></div>
                                    <div class="tf-hotel-archive-map-wrap">
                                        <div id="tf-hotel-archive-map"></div>
                                    </div>
                                    <div class="tf-map-posts-wrapper">
                                        <div class="sht-hotels-content">
                                            <?php
                                            while ($hotels->have_posts()) :
                                                $hotels->the_post();
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
                                                    if (!is_room_available($checkin, $checkout, $availability)) {
                                                        continue;
                                                    }
                                                }

                                                $rating_badge = sht_sparator_rating_badge($post_id);

                                                include SHT_HOTEL_TOOLKIT_PATH . 'inc/templates/archive/template-parts/single-item.php';
                                            endwhile;
                                            wp_reset_query();
                                            ?>
                                        </div>
                                        <!-- child term posts -->
                                        <?php if (!empty($child_terms) && !is_wp_error($child_terms)) : ?>
                                            <div class="tf-archive-children-wrapper">
                                                <?php

                                                // Loop through each child term
                                                foreach ($child_terms as $child) : ?>
                                                    <div class="tf-archive-children">
                                                        <div class="spa-heading-wrap">
                                                            <h4 class="spa-title"><?php echo esc_html(esc_html($child->name)); ?></h4>
                                                            <?php if (!empty($child->description)): ?>
                                                                <p><?php echo esc_html(esc_html($child->description)); ?></p>
                                                            <?php endif; ?>
                                                        </div>

                                                    <?php
                                                    $child_posts_query = new WP_Query([
                                                        'post_type'      => 'tf_hotel',
                                                        'post_status'    => 'publish',
                                                        'posts_per_page' => -1,
                                                        'tax_query'      => [
                                                            [
                                                                'taxonomy' => 'hotel_location',
                                                                'field'    => 'term_id',
                                                                'terms'    => $child->term_id,
                                                                'include_children' => false,
                                                            ],
                                                        ],
                                                    ]);

                                                    if ($child_posts_query->have_posts()) {
                                                        echo '<div class="sht-archive-child-content">';
                                                        while ($child_posts_query->have_posts()) {
                                                            $child_posts_query->the_post();

                                                            // Include your single-item template for each post
                                                            include SHT_HOTEL_TOOLKIT_PATH . 'inc/templates/archive/template-parts/single-item.php';
                                                        }
                                                        echo '</div>';
                                                    } else {
                                                        echo '<p>No hotels found in this location.</p>';
                                                    }
                                                    wp_reset_postdata();
                                                    echo '</div>';
                                                endforeach; ?>
                                                    </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            <?php endif; ?>

                        <?php else : ?>
                            <div class="tf-notice tf-mt-24 tf-mb-30">
                                <?php if (current_user_can('administrator')) : ?>
                                    <p>
                                        <?php echo esc_html__('Google Maps is not selected. Please configure it.', 'spa-hotel-toolkit'); ?>
                                        <a href="<?php echo esc_url(admin_url('admin.php?page=tf_settings#tab=map_settings')); ?>" target="_blank">
                                            <?php esc_html_e('Map Settings', 'spa-hotel-toolkit'); ?>
                                        </a>
                                    </p>
                                <?php else : ?>
                                    <p>
                                        <?php esc_html_e('Access is restricted as Google Maps is not enabled. Please contact the site administrator.', 'spa-hotel-toolkit'); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
                <!-- Available rooms end -->
            </div>
        </div>
    </div>


    <?php

    function get_date_range($start, $end)
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

    function is_room_available($checkin, $checkout, $availability)
    {
        if (empty($checkin) || empty($checkout) || empty($availability)) {
            return false;
        }
        $dates = get_date_range($checkin, $checkout);
        foreach ($dates as $date) {
            if (empty($availability[$date]) || $availability[$date]['status'] !== 'available') {
                return false;
            }
        }

        return true;
    }
