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

$banner_title = ! empty($tf_location_meta['location-banner-title']) ? $tf_location_meta['location-banner-title'] : '';
$location_inner_title = ! empty($tf_location_meta['location-inner-title']) ? $tf_location_meta['location-inner-title'] : '';

$faq_subtitle = ! empty($tf_location_meta['location-faq-subtitle']) ? $tf_location_meta['location-faq-subtitle'] : esc_html__('FAQ', 'spa-hotel-toolkit');
$faq_title = ! empty($tf_location_meta['location-faq-title']) ? $tf_location_meta['location-faq-title'] : esc_html__("Got Questions? We've Got Answers.
", 'spa-hotel-toolkit');
$faq_desc = ! empty($tf_location_meta['location-faq-desc']) ? $tf_location_meta['location-faq-desc'] : esc_html__("Everything you need to know about finding and booking your perfect spa getaway.", 'spa-hotel-toolkit');

$faqs = isset($tf_location_meta['location-faqs']) ? $tf_location_meta['location-faqs'] : [];

$faq_items = !empty(Helper::tf_data_types($faqs)) ? Helper::tf_data_types($faqs) : [];


$args = array(
	'post_type' => 'tf_hotel',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'tax_query' => array(
		array(
			'taxonomy' => 'hotel_location',
			'field' => 'slug',
			'terms' => $taxonomy_slug,
			'include_children' => false,
		)
	)
);
$hotels = new \WP_Query($args);

?>
<div class="spa-hotel-archive-template">
	<?php if (! empty($tf_hotel_arc_banner)) : ?>
		<div class="tf-hotel-archive-banner"
			style="background-image: url('<?php echo esc_url($tf_hotel_arc_banner); ?>');">
		<?php else : ?>
		<div class="tf-hotel-archive-banner">
	<?php endif; ?>
		<div class="tf-container">
			<div class="tf-banner-content">
				<h1>
					<?php if(!empty($banner_title)): ?>
						<?php echo esc_html($banner_title);?>
					<?php else: ?>
						<?php echo esc_html__('Best Spa Hotels in', 'spa-hotel-toolkit'); ?>
						<br>
						<?php echo esc_html($term->name); ?>
					<?php endif; ?>
				</h1>
			</div>
			<div class="tf-spa-hero-search">
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
				<?php if(!empty($location_inner_title)): ?>
					<?php echo esc_html($location_inner_title);?>
				<?php else: ?>
					<?php echo esc_html__('Top Spa Hotels in', 'spa-hotel-toolkit'); ?>
					<span><?php echo esc_html($term->name); ?></span>	
				<?php endif; ?>
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
	?>

	<div class="tf-faq-wrapper sht-sec-space">
		<div class="tf-container">
			<div class="spa-heading-wrap">
				<?php if (!empty($faq_subtitle)): ?>
					<div class="spa-subtitle"><?php echo esc_html($faq_subtitle); ?></div>
				<?php endif; ?>
				<?php if (!empty($faq_title)): ?>
					<h2 class="spa-title"><?php echo esc_html($faq_title); ?></h2>
				<?php endif; ?>
				<?php if ($faq_desc): ?>
					<p class="spa-desc">
						<?php echo esc_html($faq_desc); ?>
					</p>
				<?php endif; ?>
			</div>
			<div class="tf-faq-inner">
				<?php if (!empty($faq_items)): ?>
					<?php foreach ($faq_items as $key => $faq_item): ?>
						<div class="tf-faq-item">
							<div class="tf-faq-item-title">
								<div class="tf-faq-item-icon">
									<div class="tf-plus">
										<svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="none">
											<path d="M4.16675 10.7493H15.8334M10.0001 4.91602V16.5827" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
										</svg>
									</div>
									<div class="tf-minus">
										<svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="none">
											<path d="M4.16675 10.75H15.8334" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
										</svg>
									</div>
								</div>
								<div class="tf-faq-item-title-text">
									<?php echo esc_html($faq_item['faq-question']); ?>
								</div>
							</div>
							<div class="tf-faq-item-content">
								<p><?php echo esc_html($faq_item['faq-answer']); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<p style="text-align: center;"><?php echo esc_html__('No faq found', 'spa-hotel-toolkit'); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>