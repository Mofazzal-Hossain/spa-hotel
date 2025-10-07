<?php 

// Don't load directly
defined( 'ABSPATH' ) || exit;

// args for related hotels
$args = array(
    'post_type'      => 'tf_hotel',
    'post__not_in'   => array( get_the_ID() ),
    'orderby'        => 'rand',
    'posts_per_page' => 6,
);

$query = new WP_Query( $args );

include SHT_HOTEL_TOOLKIT_PATH . 'inc/common/spa-hotel-slider-contents.php';