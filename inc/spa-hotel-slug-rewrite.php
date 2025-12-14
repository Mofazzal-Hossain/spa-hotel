<?php
if ( ! defined( 'ABSPATH' ) ) exit;


add_filter( 'post_type_link', 'sht_hotel_permalink_rewirte', 10, 2 );
function sht_hotel_permalink_rewirte( $permalink, $post ) {

    if ( $post->post_type != 'tf_hotel' ) {
        return $permalink;
    }

    // Get assigned hotel locations
    $terms = wp_get_post_terms( $post->ID, 'hotel_location' );
    if ( empty( $terms ) || is_wp_error( $terms ) ) {
        return $permalink;
    }

    // Pick the deepest term (child-most)
    $deepest_term = $terms[0];
    $max_depth = 0;

    foreach ( $terms as $t ) {
        $depth = 0;
        $parent = $t->parent;
        while ( $parent ) {
            $parent_term = get_term( $parent, 'hotel_location' );
            if ( is_wp_error( $parent_term ) ) break;
            $parent = $parent_term->parent;
            $depth++;
        }
        if ( $depth > $max_depth ) {
            $max_depth = $depth;
            $deepest_term = $t;
        }
    }

    // Hierarchical slug path
    $locations = array();
    $term = $deepest_term;
    while ( $term ) {
        $locations[] = $term->slug;
        $term = $term->parent ? get_term( $term->parent, 'hotel_location' ) : false;
    }

    $locations = array_reverse( $locations );

    // Remove USA from URL
    if ( strtolower( $locations[0] ) === 'usa' ) {
        array_shift( $locations );
    }

    $location_path = implode( '/', $locations );

    // Build hotel permalink
    $permalink = trailingslashit( home_url() ) . $location_path . '/' . $post->post_name;

    return $permalink;
}

// Rewrite rules
add_action( 'init', 'custom_hotel_rewrite_rules' );
function custom_hotel_rewrite_rules() {
    add_rewrite_rule(
        '^(?!hotel-location)(.+)/([^/]+)/?$',
        'index.php?tf_hotel=$matches[2]',
        'top'
    );

}
