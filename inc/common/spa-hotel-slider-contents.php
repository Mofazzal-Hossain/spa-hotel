<?php
// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\Classes\Hotel\Pricing;
use \Tourfic\Classes\Helper;
use \Tourfic\Classes\Room\Room;

if (empty($query) || ! $query instanceof WP_Query) {
    return;
}
?>
<div class="sht-hotels-wrapper">
    <div class="sht-hotels-content swiper sht-hotels-slider">
        <div class="swiper-wrapper">
            <?php if ($query->have_posts()):
                $index = 1;
            ?>
                <?php while ($query->have_posts()):
                    $query->the_post();
                    $post_id = get_the_ID();

                    // Get hotel data
                    $meta = get_post_meta($post_id, 'tf_hotels_opt', true);

                    // Get hotel address
                    if (! empty($meta['map']) && Helper::tf_data_types($meta['map'])) {
                        $address = ! empty(Helper::tf_data_types($meta['map'])['address']) ? Helper::tf_data_types($meta['map'])['address'] : '';
                    }
                    // Get featured
                    $featured = ! empty($meta['featured']) ? $meta['featured'] : '';
                    $featured_badge_text = !empty($meta['featured_text']) ? esc_html($meta['featured_text']) : '';

                    // Get hotel features
                    $features = ! empty(get_the_terms($post_id, 'hotel_feature')) ? get_the_terms($post_id, 'hotel_feature') : '';
                    $features_count = 6;

                    $tf_booking_type = '1';
                    $tf_booking_url  = $tf_booking_query_url = $tf_booking_attribute = '';
                    if (function_exists('is_tf_pro') && is_tf_pro()) {
                        $tf_booking_type      = ! empty($meta['booking-by']) ? $meta['booking-by'] : 1;
                        $tf_booking_url       = ! empty($meta['booking-url']) ? esc_url($meta['booking-url']) : '#';
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


                    $rating_badge = sht_sparator_rating_badge($post_id);
                ?>
                    <!-- single destination -->
                    <div class="sht-hotel-single-item swiper-slide">
                        <div class="sht-rator-badge <?php echo esc_attr($rating_badge['class']); ?>">
                            <?php echo esc_html($rating_badge['text']); ?>
                        </div>
                        <!-- destination thumbnail -->
                        <div class="sht-hotel-item-thumbnail">
                            <a href="<?php echo esc_url($tf_booking_url); ?>">
                                <?php $sht_hotel_image = !empty(get_the_post_thumbnail_url(get_the_ID())) ? esc_url(get_the_post_thumbnail_url(get_the_ID())) : esc_url(site_url() . '/wp-content/plugins/elementor/assets/images/placeholder.png');                                    ?>
                                <img src="<?php echo esc_url($sht_hotel_image); ?>" alt="post thumbnail">
                            </a>
                        </div>
                        <!-- destination content -->
                        <div class="sht-hotel-item-content">
                            <h3 class="sht-hotel-item-title">
                                <a href="<?php echo esc_url(get_the_permalink()) ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>

                            <!-- destination location -->
                            <?php if (!empty($address)): ?>
                                <span class="sht-hotel-item-location">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M16.6666 8.33317C16.6666 12.494 12.0508 16.8273 10.5008 18.1657C10.3564 18.2743 10.1806 18.333 9.99992 18.333C9.81925 18.333 9.64348 18.2743 9.49909 18.1657C7.94909 16.8273 3.33325 12.494 3.33325 8.33317C3.33325 6.56506 4.03563 4.86937 5.28587 3.61913C6.53612 2.36888 8.23181 1.6665 9.99992 1.6665C11.768 1.6665 13.4637 2.36888 14.714 3.61913C15.9642 4.86937 16.6666 6.56506 16.6666 8.33317Z" stroke="#7C7C7C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M9.99992 10.8332C11.3806 10.8332 12.4999 9.71388 12.4999 8.33317C12.4999 6.95246 11.3806 5.83317 9.99992 5.83317C8.61921 5.83317 7.49992 6.95246 7.49992 8.33317C7.49992 9.71388 8.61921 10.8332 9.99992 10.8332Z" stroke="#7C7C7C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <span><?php echo esc_html($address); ?></span>
                                </span>
                            <?php endif; ?>

                            <div class="sht-hotels-price-info">
                                <div class="sht-hotel-item-price">
                                    <?php echo wp_kses_post($price_html); ?>
                                    <div class="price-per-label"><?php echo esc_html($price_multi_text); ?></div>
                                </div>

                                <?php if ($featured &&  ! empty($featured_badge_text)) : ?>
                                    <div class="sht-badge sht-badge-secondary"><?php echo esc_html($featured_badge_text); ?></div>
                                <?php endif; ?>

                            </div>
                            <!-- Features -->
                            <?php if ($features) : ?>
                                <div class="sht-hotel-item-features">
                                    <ul>
                                        <?php foreach ($features as $tfkey => $feature) {
                                            $feature_meta = get_term_meta($feature->term_taxonomy_id, 'tf_hotel_feature', true);
                                            $feature_icon = '';

                                            if (! empty($feature_meta)) {
                                                $f_icon_type = ! empty($feature_meta['icon-type']) ? $feature_meta['icon-type'] : '';
                                            }

                                            if (! empty($f_icon_type) && $f_icon_type == 'fa') {
                                                $feature_icon = ! empty($feature_meta['icon-fa'])
                                                    ? '<i class="' . esc_attr($feature_meta['icon-fa']) . '"></i>'
                                                    : '';
                                            } elseif (! empty($f_icon_type) && $f_icon_type == 'c') {
                                                $feature_icon = ! empty($feature_meta['icon-c'])
                                                    ? '<img src="' . esc_url($feature_meta['icon-c']) . '" style="min-width: ' . intval($feature_meta['dimention']) . 'px; height: ' . intval($feature_meta['dimention']) . 'px;" />'
                                                    : '';
                                            }

                                            if (empty($feature_icon)) {
                                                $feature_icon = '<i class="fa fa-check"></i>';
                                            }

                                            if ($tfkey < $features_count) { ?>
                                                <li class="sht-hotel-item-feature-lists">
                                                    <span><?php echo wp_kses_post($feature_icon); ?></span>
                                                    <span><?php echo esc_html($feature->name); ?></span>
                                                </li>
                                        <?php }
                                        } ?>

                                    </ul>
                                </div>
                            <?php endif; ?>
                            <div class="sht-hotel-item-description">
                                <?php
                                if (strlen(get_the_content()) > 300) {
                                    echo esc_html(wp_strip_all_tags(Helper::tourfic_character_limit_callback(get_the_content(), 300)));
                                } else {
                                    the_content();
                                }
                                ?>
                            </div>

                            <!-- destination button -->
                            <div class="sht-hotel-item-buttons">
                                <a href="<?php echo esc_url($tf_booking_url); ?>" class="sht-btn">
                                    <?php echo esc_html__('Book Now', 'spa-hotel-toolkit'); ?>
                                </a>
                                <a href="<?php echo esc_url(get_the_permalink()); ?>" class="sht-btn sht-btn-fill">
                                    <?php echo esc_html__('More info', 'spa-hotel-toolkit'); ?>
                                </a>
                            </div>

                        </div>
                    </div>
            <?php $index++;
                endwhile;
            endif; ?>

        </div>
        <!-- slider controls -->
        <div class="sht-slider-controls">
            <button class="sht-arrow sht-prev" type="button" aria-label="Previous">
                <span class="sht-arrow-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M12.5 15L7.5 10L12.5 5" stroke="#DDB892" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            </button>

            <div class="sht-pagination"></div> <!-- bullets will appear here -->

            <button class="sht-arrow sht-next" type="button" aria-label="Next">
                <span class="sht-arrow-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="8" height="12" viewBox="0 0 8 12" fill="none">
                        <path d="M1.5 11L6.5 6L1.5 1" stroke="#DDB892" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            </button>
        </div>
    </div>
</div>
<?php wp_reset_postdata(); ?>