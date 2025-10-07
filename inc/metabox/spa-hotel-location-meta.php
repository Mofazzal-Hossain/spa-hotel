<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_filter('tf_hotel_location_metabox_args', function($args){
    $args['fields'][] = array(
        'id'    => 'featured',
        'label' => esc_html__( 'Feature Text', 'spa-hotel-toolkit' ),
        'type'  => 'text',
    );

    $args['fields'][] = array(
        'id'    => 'sec-subtitle',
        'type'  => 'text',
        'label' => esc_html__( 'Subtitle', 'spa-hotel-toolkit' ),
        'subtitle' => esc_html__( 'Add subtitle for archive video section', 'spa-hotel-toolkit' ),
        'default' => esc_html__( 'Youtube', 'spa-hotel-toolkit' ),
    );
    
    $args['fields'][] = array(
        'id'    => 'sec-title',
        'type'  => 'text',
        'label' => esc_html__( 'Title', 'spa-hotel-toolkit' ),
        'subtitle' => esc_html__( 'Add title for archive video section', 'spa-hotel-toolkit' ),
        'default' => esc_html__( 'Videos of New York City Hotels with Hot Tub in Room​', 'spa-hotel-toolkit' ),
    );

    $args['fields'][] = array(
        'id'    => 'videos',
        'label' => esc_html__( 'Videos', 'spa-hotel-toolkit' ),
        'type'  => 'repeater',
        'button_title' => esc_html__( 'Add New', 'spa-hotel-toolkit' ),
        'field_title'  => 'video-title',
        'fields'   => array(
            array(
                'id'    => 'video-thumbnail',
                'type'  => 'image',
                'label' => esc_html__( 'Thumbnail', 'spa-hotel-toolkit' ),
            ),
            array(
                'id'    => 'video-title',
                'type'  => 'text',
                'label' => esc_html__( 'Title', 'spa-hotel-toolkit' ),
            ),
            array(
                'id'    => 'video-url',
                'type'  => 'text',
                'label' => esc_html__( 'URL', 'spa-hotel-toolkit' ),
            ),
        ),
    );

    

    return $args;
});



