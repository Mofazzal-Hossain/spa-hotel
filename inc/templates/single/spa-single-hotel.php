<?php

// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\Classes\Helper;
use \Tourfic\App\Wishlist;

if (!Helper::tf_is_woo_active()) {
?>
	<div class="tf-container">
		<div class="tf-notice tf-notice-danger">
			<?php esc_html_e('Please install and activate WooCommerce plugin to view hotel details.', 'spa-hotel-toolkit'); ?>
		</div>
	</div>
<?php
	get_footer();
	return;
}

// include template parts
?>
<div class="spa-single-details tf-single-template__legacy">
	<div class="tf-container">
		<div class="spa-single-details-sections">
			<?php
				include SHT_HOTEL_TOOLKIT_TEMPLATES . 'single/template-parts/hero.php';
				include SHT_HOTEL_TOOLKIT_TEMPLATES . 'single/template-parts/rator-progress.php';
				include SHT_HOTEL_TOOLKIT_TEMPLATES . 'single/template-parts/facilities.php';
				include SHT_HOTEL_TOOLKIT_TEMPLATES . 'single/template-parts/other-facilities.php';
				include SHT_HOTEL_TOOLKIT_TEMPLATES . 'single/template-parts/availability.php';
				include SHT_HOTEL_TOOLKIT_TEMPLATES . 'single/template-parts/about.php';
				include SHT_HOTEL_TOOLKIT_TEMPLATES . 'single/template-parts/review.php';
				include SHT_HOTEL_TOOLKIT_TEMPLATES . 'single/template-parts/location.php';
				include SHT_HOTEL_TOOLKIT_TEMPLATES . 'single/template-parts/insta-feeds.php';
				include SHT_HOTEL_TOOLKIT_TEMPLATES . 'single/template-parts/faq.php';
			?>
		</div>
	</div>
</div>

<!-- related hotels -->
<div class="spa-related-hotels">
	<div class="tf-container">
		<div class="spa-heading-wrap">
			<div class="spa-subtitle"><?php echo esc_html__('Near by Hotels', 'spa-hotel-toolkit'); ?></div>
			<h2 class="spa-title"><?php echo esc_html__('Hotel People Love Most', 'spa-hotel-toolkit'); ?></h2>
			<p class="spa-desc">
				<?php echo esc_html__("Explore our editor's picks for must-visit spa hotels this season.", 'spa-hotel-toolkit'); ?>
			</p>

		</div>
		<?php include SHT_HOTEL_TOOLKIT_TEMPLATES . 'single/template-parts/related-hotels.php'; ?>
	</div>
</div>

<?php
