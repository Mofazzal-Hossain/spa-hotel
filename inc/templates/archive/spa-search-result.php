<?php

/**
 * Template: Hotel Search Result
 */

// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\Classes\Helper;
use \Tourfic\Classes\Hotel\Pricing;
use \Tourfic\Classes\Room\Room;

$tf_defult_views = !empty(Helper::tf_data_types(Helper::tfopt('tf-template'))['hotel_archive_view']) ? Helper::tf_data_types(Helper::tfopt('tf-template'))['hotel_archive_view'] : 'list';
$tf_map_settings = !empty(Helper::tfopt('google-page-option')) ? Helper::tfopt('google-page-option') : "default";
$tf_map_api = !empty(Helper::tfopt('tf-googlemapapi')) ? Helper::tfopt('tf-googlemapapi') : '';

$tf_template = Helper::tf_data_types(Helper::tfopt('tf-template'));
$tf_hotel_arc_banner = ! empty($tf_template['hotel_archive_design_1_bannar'])
    ? $tf_template['hotel_archive_design_1_bannar']
    : '';


$place = ! empty($_GET['place']) ? $_GET['place'] : '';

$args = array(
    'post_type' => 'tf_hotel',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'hotel_location',
            'field' => 'slug',
            'terms' => $place,
            'include_children' => false,
        )
    )
);
$hotels = new \WP_Query($args);
?>
<div class="spa-hotel-search-result spa-hotel-archive-template">
    <!-- Banner -->
    <?php if (! empty($tf_hotel_arc_banner)) : ?>
        <div class="tf-hotel-archive-banner" style="background-image: url('<?php echo esc_url($tf_hotel_arc_banner); ?>');">
    <?php else : ?>
        <div class="tf-hotel-archive-banner">
    <?php endif; ?>
            <div class="tf-container">
                <div class="tf-banner-content">
                    <h1><?php echo esc_html__('Search Results', 'spa-hotel-toolkit'); ?></h1>
                </div>
                <div class="tf-spa-hero-search tf-archive-search">
                    <?php include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/spa-archive-search-form.php'; ?>
                </div>
            </div>
            </div>

            <!-- Breadcrumb -->
            <div class="sht-breadcrumb sht-sec-space">
                <div class="tf-container">
                    <ul>
                        <li>
                            <a href="<?php echo esc_url(home_url('/')); ?>">
                                <?php echo esc_html__('Home', 'spa-hotel-toolkit'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                                    <path d="M8 15.5L13 10.5L8 5.5" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <?php echo esc_html__('Search Result', 'spa-hotel-toolkit'); ?>
                        </li>
                    </ul>
                </div>
            </div>



            <?php

            include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/template-parts/archive-details.php';
            include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/template-parts/latest-posts.php';
            include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/template-parts/reviews.php';
            include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/template-parts/faq.php';
            ?>
        </div>