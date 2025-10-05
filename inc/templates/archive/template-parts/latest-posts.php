<?php
// Don't load directly
defined('ABSPATH') || exit;
$args = array(
    'post_type'   => 'post',
    'post_status' => 'publish',
);
$query = new \WP_Query($args);
?>

<div class="tf-archive-latest-posts sht-sec-space">
    <div class="tf-container">
        <div class="spa-heading-wrap">
            <div class="spa-subtitle"><?php echo esc_html__('Wellness Guides', 'spa-hotel-toolkit'); ?></div>
            <h2 class="spa-title"><?php echo esc_html__('Expert Spa Travel Guides & Wellness Tips', 'spa-hotel-toolkit'); ?></h2>
            <p class="spa-desc">
                <?php echo esc_html__("Insider recommendations from spa experts to help you plan the perfect wellness getaway", 'spa-hotel-toolkit'); ?>
            </p>
        </div>
        <?php include SHT_HOTEL_TOOLKIT_PATH . 'inc/common/spa-latest-posts-contents.php'; ?>
    </div>
</div>