<?php
// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\Classes\Helper;
use \Tourfic\Classes\Hotel\Pricing;

?>
<div class="sht-hotel-single-item">
    <div class="sht-rator-badge <?php echo esc_attr($rating_badge['class']); ?>">
        <?php echo esc_html($rating_badge['text']); ?>
    </div>
    <!-- destination thumbnail -->
    <div class="sht-hotel-item-thumbnail">
        <a href="<?php echo esc_url(get_the_permalink()) ?>">
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