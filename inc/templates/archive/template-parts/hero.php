<?php

// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\Classes\Helper;


$tf_template = Helper::tf_data_types(Helper::tfopt('tf-template'));
$tf_hotel_arc_banner = ! empty($tf_template['hotel_archive_design_1_bannar'])
    ? $tf_template['hotel_archive_design_1_bannar']
    : '';

?>

<?php if (! empty($tf_hotel_arc_banner)) : ?>
	<div class="tf-hotel-archive-banner" style="background-image: url('<?php echo esc_url($tf_hotel_arc_banner); ?>');">
<?php else : ?>
	<div class="tf-hotel-archive-banner">
<?php endif; ?>
    <div class="tf-container">
        <div class="tf-banner-content">
            <h1><?php echo esc_html__('Hotels', 'spa-hotel-toolkit'); ?></h1>
        </div>
        <div class="tf-spa-hero-search tf-archive-search">
            <?php include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/spa-archive-search-form.php'; ?>
        </div>
    </div>
</div>


