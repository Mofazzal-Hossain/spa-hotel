<?php
/**
 * Template: Hotel Archive
 *
 * Display all hotels here
 * 
 * Default slug: /hotels 
 */
// Don't load directly
defined( 'ABSPATH' ) || exit;

?>

<div class="spa-hotel-archive-template">
	<?php 
		// archive hotel template
		include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/template-parts/hero.php';
		include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/template-parts/archive-details.php';
		include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/template-parts/latest-posts.php';
		include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/template-parts/reviews.php';
		include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/template-parts/faq.php';
	?>
</div>