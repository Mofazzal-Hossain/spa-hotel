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
            <div class="mobile-sidebar-overlay"></div>
            <div class="tf-sidebar">
                <button id="closeMobileSidebar" class="close-btn"><i class="ri-close-large-line"></i></button>
                <?php if (is_active_sidebar('tf_archive_booking_sidebar')) { ?>
                    <div id="tf__booking_sidebar" class="tf-booking-sidebar">
                        <div class="tf-filter-title">
                            <div class="tf-section-title"><?php echo esc_html__("Filter", "spa-hotel-toolkit"); ?></div>
                            <button class="sht-sidebar-reset"><?php echo esc_html__("Reset", "spa-hotel-toolkit"); ?></button>
                        </div>
                        <?php dynamic_sidebar('tf_archive_booking_sidebar'); ?>
                    </div>
                    <?php
                    if (!empty($current_term) && isset($current_term->taxonomy) && isset($current_term->slug)) :
                    ?>
                        <input type="hidden" class="tf-archive-taxonomy" name="taxonomy" value="<?php echo esc_attr($current_term->taxonomy); ?>">
                        <input type="hidden" class="tf-archive-slug" name="term" value="<?php echo esc_attr($current_term->slug); ?>">
                    <?php endif;
                    ?>
                <?php } ?>
            </div>

            <!--Available rooms start -->
            <div class="sht-hotels-wrapper tf-archive-hotels">
                <div class="sht-archive-view">
                    <button class="list-view active"><?php echo esc_html__('List ', 'spa-hotel-toolkit'); ?></button>
                    <button class="map-view"><?php echo esc_html__('Map ', 'spa-hotel-toolkit'); ?></button>
                </div>
                <div class="mobile-filter-sidebar">
                    <button id="openMobileSidebar">
                        <span><?php echo esc_html__('Filter', 'spa-hotel-toolkit'); ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M3.33325 4.16675H16.6666M3.33325 10.0001H16.6666M3.33325 15.8334H16.6666" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
                <div class="sht-hotel-view list-view">
                    <!-- map wrapper -->
                    <div class="sht-map-wrapper">
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
                                    <div id="map-marker" data-marker="<?php echo esc_url(SHT_HOTEL_TOOLKIT_ASSETS . 'images/cluster-marker.png'); ?>"></div>
                                    <div class="tf-hotel-archive-map-wrap">
                                        <div id="spa-hotel-archive-map"></div>
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
                    <div class="sht-hotel-content-inner">
                        <!-- Hotels content -->
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
                        <!-- child term posts -->
                        <?php if (!empty($child_terms) && !is_wp_error($child_terms)) :

                            $child_terms_with_posts = [];

                            foreach ($child_terms as $child) {
                                $child_posts_query = new WP_Query([
                                    'post_type'      => 'tf_hotel',
                                    'post_status'    => 'publish',
                                    'posts_per_page' => 1, // just check existence
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
                                    $child_terms_with_posts[] = $child;
                                }
                                wp_reset_postdata();
                            } ?>

                            <?php if (!empty($child_terms_with_posts)): ?>
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

                                            if ($child_posts_query->have_posts()) : ?>
                                                <div class="sht-archive-child-content">
                                                    <?php
                                                    while ($child_posts_query->have_posts()) {
                                                        $child_posts_query->the_post();
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
                                                        // Include your single-item template for each post
                                                        include SHT_HOTEL_TOOLKIT_PATH . 'inc/templates/archive/template-parts/single-item.php';
                                                    } ?>
                                                </div>
                                            <?php endif;
                                            wp_reset_postdata(); ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="sht-no-result">
                        <svg xmlns="http://www.w3.org/2000/svg" width="57" height="56" viewBox="0 0 57 56" fill="none">
                            <path d="M28.5 5.25C24.0005 5.25 19.602 6.58426 15.8608 9.08407C12.1196 11.5839 9.20364 15.1369 7.48175 19.294C5.75986 23.451 5.30933 28.0252 6.18715 32.4383C7.06496 36.8514 9.23169 40.905 12.4133 44.0867C15.595 47.2683 19.6486 49.435 24.0617 50.3129C28.4748 51.1907 33.049 50.7402 37.2061 49.0183C41.3631 47.2964 44.9161 44.3804 47.4159 40.6392C49.9157 36.898 51.25 32.4995 51.25 28C51.2436 21.9683 48.8447 16.1854 44.5797 11.9204C40.3146 7.65528 34.5317 5.25637 28.5 5.25ZM28.5 47.25C24.6927 47.25 20.9709 46.121 17.8053 44.0058C14.6396 41.8906 12.1723 38.8841 10.7153 35.3667C9.25834 31.8492 8.87713 27.9786 9.61989 24.2445C10.3627 20.5104 12.196 17.0804 14.8882 14.3882C17.5804 11.696 21.0104 9.86265 24.7445 9.11988C28.4787 8.37712 32.3492 8.75833 35.8667 10.2153C39.3841 11.6723 42.3906 14.1396 44.5058 17.3053C46.621 20.4709 47.75 24.1927 47.75 28C47.7442 33.1036 45.7142 37.9966 42.1054 41.6054C38.4966 45.2142 33.6036 47.2442 28.5 47.25ZM18 23.625C18 23.1058 18.154 22.5983 18.4424 22.1666C18.7308 21.7349 19.1408 21.3985 19.6205 21.1998C20.1001 21.0011 20.6279 20.9492 21.1371 21.0504C21.6463 21.1517 22.1141 21.4017 22.4812 21.7688C22.8483 22.136 23.0983 22.6037 23.1996 23.1129C23.3009 23.6221 23.2489 24.1499 23.0502 24.6295C22.8515 25.1092 22.5151 25.5192 22.0834 25.8076C21.6517 26.096 21.1442 26.25 20.625 26.25C19.9288 26.25 19.2611 25.9734 18.7689 25.4812C18.2766 24.9889 18 24.3212 18 23.625ZM39 23.625C39 24.1442 38.8461 24.6517 38.5576 25.0834C38.2692 25.515 37.8592 25.8515 37.3796 26.0502C36.8999 26.2489 36.3721 26.3008 35.8629 26.1996C35.3537 26.0983 34.886 25.8483 34.5189 25.4812C34.1517 25.114 33.9017 24.6463 33.8004 24.1371C33.6992 23.6279 33.7511 23.1001 33.9498 22.6205C34.1485 22.1408 34.485 21.7308 34.9166 21.4424C35.3483 21.154 35.8558 21 36.375 21C37.0712 21 37.7389 21.2766 38.2312 21.7688C38.7234 22.2611 39 22.9288 39 23.625ZM38.7638 37.625C38.8904 37.8242 38.9754 38.0469 39.0137 38.2798C39.052 38.5127 39.0428 38.7509 38.9867 38.9802C38.9305 39.2094 38.8286 39.4249 38.687 39.6138C38.5454 39.8026 38.367 39.9608 38.1627 40.0789C37.9583 40.197 37.7322 40.2726 37.4979 40.3011C37.2636 40.3295 37.026 40.3103 36.7993 40.2445C36.5726 40.1788 36.3616 40.0678 36.1789 39.9184C35.9962 39.769 35.8457 39.5841 35.7363 39.375C34.1022 36.5509 31.5341 35 28.5 35C25.4659 35 22.8978 36.5531 21.2638 39.375C21.1544 39.5841 21.0038 39.769 20.8211 39.9184C20.6384 40.0678 20.4274 40.1788 20.2007 40.2445C19.974 40.3103 19.7364 40.3295 19.5021 40.3011C19.2678 40.2726 19.0417 40.197 18.8373 40.0789C18.633 39.9608 18.4547 39.8026 18.3131 39.6138C18.1715 39.4249 18.0695 39.2094 18.0134 38.9802C17.9572 38.7509 17.948 38.5127 17.9863 38.2798C18.0246 38.0469 18.1096 37.8242 18.2363 37.625C20.4872 33.7334 24.2278 31.5 28.5 31.5C32.7722 31.5 36.5128 33.7312 38.7638 37.625Z" fill="#6E655E"></path>
                        </svg>
                        <span>No results found!</span>
                    </div>

                    <div id="map-datas" style="display: none"><?php echo array_filter($locations) ? wp_json_encode(array_values($locations)) : wp_json_encode([]); ?></div>

                </div>
                <!-- Available rooms end -->
            </div>
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
        $dates[] = gmdate('Y/m/d', $current);
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
