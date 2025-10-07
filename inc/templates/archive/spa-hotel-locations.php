<?php

/**
 * Template: Hotel Location Archive
 */
// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\Classes\Helper;

$term = get_queried_object();
$post_type = 'tf_hotel';
$taxonomy = $term->taxonomy;
$taxonomy_name = $term->name;
$taxonomy_slug = $term->slug;
$max = '8';

$tf_location_meta      = get_term_meta($term->term_id, 'tf_hotel_location', true);
$tf_hotel_arc_banner = ! empty(Helper::tf_data_types(Helper::tfopt('tf-template'))['hotel_archive_design_2_bannar']) ?  Helper::tf_data_types(Helper::tfopt('tf-template'))['hotel_archive_design_2_bannar'] : '';
$tf_location_image = ! empty($tf_location_meta['image']) ? $tf_location_meta['image'] : $tf_hotel_arc_banner;

$tf_hotel_arc_selected_template = ! empty(Helper::tf_data_types(Helper::tfopt('tf-template'))['hotel-archive']) ?  Helper::tf_data_types(Helper::tfopt('tf-template'))['hotel-archive'] : 'design-1';
$tf_template = Helper::tf_data_types(Helper::tfopt('tf-template'));
$tf_hotel_arc_banner = ! empty($tf_template['hotel_archive_design_1_bannar'])
	? $tf_template['hotel_archive_design_1_bannar']
	: '';

$banner_style = $tf_hotel_arc_banner ? 'style="background-image: url(' . esc_url($tf_hotel_arc_banner) . ');"' : '';
?>
<div class="spa-hotel-archive-template">
	<div class="tf-hotel-archive-banner" <?php echo $banner_style; ?>>
		<div class="tf-container">
			<div class="tf-banner-content">
				<h1>
					<?php echo esc_html__('Best Spa Hotels in', 'spa-hotel-toolkit'); ?>
					<br>
					<?php echo esc_html($term->name); ?>
				</h1>
			</div>
			<div class="tf-spa-hero-search tf-archive-search">
				<?php echo do_shortcode("[tf_search_form type='hotel' fullwidth='true' classes='tf-hero-search-form' advanced='disabled' design='4']"); ?>
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
					<a href="<?php echo esc_url(home_url('/hotels')); ?>">
						<?php echo esc_html__('Hotels', 'spa-hotel-toolkit'); ?>
						<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
							<path d="M8 15.5L13 10.5L8 5.5" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</a>
				</li>
				<li>
					<span><?php echo esc_html($term->name); ?></span>
				</li>
			</ul>
		</div>
	</div>

	<!-- location info -->
	<div class="sht-hotel-location-info">
		<div class="tf-container">
			<h2>
				<?php echo esc_html__('Top Spa Hotels in', 'spa-hotel-toolkit'); ?>
				<span><?php echo esc_html($term->name); ?></span>
			</h2>
			<?php if (!empty($term->description)): ?>
				<p><?php echo esc_html($term->description); ?></p>
			<?php endif; ?>
		</div>
	</div>


	<?php
	// archive hotel template
	include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/template-parts/archive-details.php';
	include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/template-parts/latest-posts.php';
	include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/template-parts/reviews.php';
	include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/template-parts/video.php';
	include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/template-parts/faq.php';
	?>
</div>