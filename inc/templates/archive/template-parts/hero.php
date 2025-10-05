<?php

// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\Classes\Helper;

$tf_template = Helper::tf_data_types(Helper::tfopt('tf-template'));
$tf_hotel_arc_banner = ! empty($tf_template['hotel_archive_design_1_bannar'])
    ? $tf_template['hotel_archive_design_1_bannar']
    : '';

$banner_style = $tf_hotel_arc_banner ? 'style="background-image: url(' . esc_url($tf_hotel_arc_banner) . ');"' : '';
?>

<div class="tf-hotel-archive-banner" <?php echo $banner_style; ?>>
    <div class="tf-container">
        <div class="tf-banner-content">
            <h1><?php echo esc_html__('Hotels', 'spa-hotel-toolkit'); ?></h1>
        </div>
        <div class="tf-spa-hero-search">
            <?php echo do_shortcode("[tf_search_form type='hotel' fullwidth='true' classes='tf-hero-search-form' advanced='disabled' design='4']"); ?>
        </div>
    </div>
</div>