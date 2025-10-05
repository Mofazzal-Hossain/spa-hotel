<?php
/**
 * Template: Hotel Location Archive
 */
// Don't load directly
defined( 'ABSPATH' ) || exit;

use \Tourfic\Classes\Helper;

$term = get_queried_object();
$post_type = 'tf_hotel';
$taxonomy = $term->taxonomy;
$taxonomy_name = $term->name;
$taxonomy_slug = $term->slug;
$max = '8';

$tf_location_meta      = get_term_meta( $term->term_id, 'tf_hotel_location', true );
$tf_hotel_arc_banner = ! empty( Helper::tf_data_types(Helper::tfopt( 'tf-template' ))['hotel_archive_design_2_bannar'] ) ?  Helper::tf_data_types(Helper::tfopt( 'tf-template' ))['hotel_archive_design_2_bannar'] : '';
$tf_location_image = ! empty( $tf_location_meta['image'] ) ? $tf_location_meta['image'] : $tf_hotel_arc_banner;

$tf_hotel_arc_selected_template = ! empty( Helper::tf_data_types(Helper::tfopt( 'tf-template' ))['hotel-archive'] ) ?  Helper::tf_data_types(Helper::tfopt( 'tf-template' ))['hotel-archive'] : 'design-1';

?>
<div class="spa-hotel-archive-template">
	<?php 
		// archive hotel template
		include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/template-parts/hero.php';
		include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/template-parts/latest-posts.php';
		include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/template-parts/reviews.php';
		include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/template-parts/video.php';
		include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/template-parts/faq.php';
	?>
</div>

