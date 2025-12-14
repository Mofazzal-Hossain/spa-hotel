<?php

if (! defined('ABSPATH')) exit; // Exit if accessed directly

add_filter('tf_hotel_location_metabox_args', function ($args) {
    $args['fields'][] = array(
        'id'    => 'featured',
        'label' => esc_html__('Feature Text', 'spa-hotel-toolkit'),
        'type'  => 'text',
    );


    $args['fields'][] = array(
        'id'    => 'location-banner-title',
        'type'  => 'text',
        'label' => esc_html__('Location Banner Title', 'spa-hotel-toolkit'),
        'subtitle' => esc_html__('Add location archive banner title', 'spa-hotel-toolkit'),
    );

    $args['fields'][] = array(
        'id'    => 'location-inner-title',
        'type'  => 'text',
        'label' => esc_html__('Location Inner Title', 'spa-hotel-toolkit'),
        'subtitle' => esc_html__('Add location archive inner title', 'spa-hotel-toolkit'),
    );
    $args['fields'][] = array(
        'id'    => 'sec-subtitle',
        'type'  => 'text',
        'label' => esc_html__('Video Section Subtitle', 'spa-hotel-toolkit'),
        'subtitle' => esc_html__('Add subtitle for archive video section', 'spa-hotel-toolkit'),
        'default' => esc_html__('Youtube', 'spa-hotel-toolkit'),
    );

    $args['fields'][] = array(
        'id'    => 'sec-title',
        'type'  => 'text',
        'label' => esc_html__('Video Section Title', 'spa-hotel-toolkit'),
        'subtitle' => esc_html__('Add title for archive video section', 'spa-hotel-toolkit'),
        'default' => esc_html__('Videos of New York City Hotels with Hot Tub in Room​', 'spa-hotel-toolkit'),
    );

    $args['fields'][] = array(
        'id'    => 'videos',
        'label' => esc_html__('Videos', 'spa-hotel-toolkit'),
        'type'  => 'repeater',
        'button_title' => esc_html__('Add New', 'spa-hotel-toolkit'),
        'field_title'  => 'video-title',
        'fields'   => array(
            array(
                'id'    => 'video-thumbnail',
                'type'  => 'image',
                'label' => esc_html__('Thumbnail', 'spa-hotel-toolkit'),
            ),
            array(
                'id'    => 'video-title',
                'type'  => 'text',
                'label' => esc_html__('Title', 'spa-hotel-toolkit'),
            ),
            array(
                'id'    => 'video-url',
                'type'  => 'text',
                'label' => esc_html__('URL', 'spa-hotel-toolkit'),
            ),
        ),
    );

    $args['fields'][] = array(
        'id'    => 'location-faq-subtitle',
        'type'  => 'text',
        'label' => esc_html__('FAQ Section Subtitle', 'spa-hotel-toolkit'),
        'subtitle' => esc_html__('Add subtitle for archive faq section', 'spa-hotel-toolkit'),
        'default' => esc_html__('FAQ', 'spa-hotel-toolkit'),
    );

    $args['fields'][] = array(
        'id'    => 'location-faq-title',
        'type'  => 'text',
        'label' => esc_html__('FAQ Section Title', 'spa-hotel-toolkit'),
        'subtitle' => esc_html__('Add title for archive faq section', 'spa-hotel-toolkit'),
        'default' => esc_html__("Got Questions? We've Got Answers.", 'spa-hotel-toolkit'),
    );

    $args['fields'][] = array(
        'id'    => 'location-faq-desc',
        'type'  => 'text',
        'label' => esc_html__('FAQ Section Description', 'spa-hotel-toolkit'),
        'subtitle' => esc_html__('Add description for archive faq section', 'spa-hotel-toolkit'),
        'default' => esc_html__("Everything you need to know about finding and booking your perfect spa getaway.", 'spa-hotel-toolkit'),
    );
    $args['fields'][] = array(
        'id'    => 'location-faqs',
        'label' => esc_html__('FAQ Questions', 'spa-hotel-toolkit'),
        'type'  => 'repeater',
        'button_title' => esc_html__('Add New', 'spa-hotel-toolkit'),
        'field_title'  => 'faq-title',
        'fields'   => array(
            array(
                'id'    => 'faq-question',
                'type'  => 'text',
                'label' => esc_html__('Title', 'spa-hotel-toolkit'),
            ),
            array(
                'id'    => 'faq-answer',
                'type'  => 'textarea',
                'label' => esc_html__('Answer', 'spa-hotel-toolkit'),
            ),
        ),
    );

    return $args;
});
