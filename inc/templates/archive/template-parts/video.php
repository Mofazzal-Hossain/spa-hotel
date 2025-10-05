<?php

// Don't load directly
defined('ABSPATH') || exit;
$term_id = get_queried_object_id();
$location_meta  = get_term_meta($term_id, 'tf_hotel_location', true);
$sec_subtitle = ! empty($location_meta['sec-subtitle']) ? $location_meta['sec-subtitle'] : esc_html__('Youtube', 'spa-hotel-toolkit');
$sec_title = ! empty($location_meta['sec-title']) ? $location_meta['sec-title'] : esc_html__('Videos', 'spa-hotel-toolkit');

?>
<div class="tf-video-wrapper sht-sec-space">
    <div class="tf-container">
        <div class="spa-heading-wrap">
            <?php if (!empty($sec_subtitle)): ?>
                <div class="spa-subtitle">
                    <?php echo esc_html($sec_subtitle); ?>
                </div>
            <?php endif; ?>
            <?php if ($sec_title): ?>
                <h2 class="spa-title">
                    <?php echo esc_html($sec_title); ?>
                </h2>
            <?php endif; ?>
        </div>
    </div>
</div>