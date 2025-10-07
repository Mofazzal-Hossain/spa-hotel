<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_action('plugins_loaded', function () {

    if (! class_exists('TF_Taxonomy_Metabox')) {
        $path = WP_PLUGIN_DIR . '/tourfic/inc/admin/TF_Options/classes/TF_Taxonomy_Metabox.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }

    if (class_exists('TF_Taxonomy_Metabox')) {
        TF_Taxonomy_Metabox::taxonomy('tf_hotel_facilities', array(
            'title'    => esc_html__('Hotel Facilities', 'spa-hotel-toolkit'),
            'taxonomy' => 'hotel_facilities',
            'fields'   => array(
                array(
                    'id'      => 'icon-type',
                    'type'    => 'select',
                    'title'   => esc_html__('Select Icon Type', 'spa-hotel-toolkit'),
                    'options' => array(
                        'fa' => esc_html__('Font Awesome', 'spa-hotel-toolkit'),
                        'c'  => esc_html__('Custom', 'spa-hotel-toolkit'),
                    ),
                    'default' => 'fa'
                ),

                array(
                    'id'         => 'icon-fa',
                    'type'       => 'icon',
                    'title'      => esc_html__('Select Font Awesome Icon', 'spa-hotel-toolkit'),
                    'dependency' => array('icon-type', '==', 'fa'),
                    'default'    => 'fa fa-check',
                ),

                array(
                    'id'             => 'icon-c',
                    'type'           => 'image',
                    'label'          => esc_html__('Upload Custom Icon', 'spa-hotel-toolkit'),
                    'placeholder'    => esc_html__('No Icon selected', 'spa-hotel-toolkit'),
                    'button_title'   => esc_html__('Add Icon', 'spa-hotel-toolkit'),
                    'remove_title'   => esc_html__('Remove Icon', 'spa-hotel-toolkit'),
                    'preview_width'  => '50',
                    'preview_height' => '50',
                    'dependency'     => array('icon-type', '==', 'c'),
                ),

                array(
                    'id'          => 'dimention',
                    'type'        => 'number',
                    'label'       => esc_html__('Custom Icon Size', 'spa-hotel-toolkit'),
                    'description' => esc_html__('Size in "px"', 'spa-hotel-toolkit'),
                    'show_units'  => false,
                    'height'      => false,
                    'default'     => '20',
                    'dependency'  => array('icon-type', '==', 'c'),
                ),

            ),
        ));
    }
});
