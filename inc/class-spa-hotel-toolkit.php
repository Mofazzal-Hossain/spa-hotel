<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Spa_Hotel_Toolkit {

    public function __construct() {
        // Enqueue frontend scripts and styles
        add_action('wp_enqueue_scripts', [ $this, 'sht_enqueue_scripts' ] );

        // Register widget category
        add_action( 'elementor/elements/categories_registered', [ $this,'sht_elementor_widget_categories'] );
       
        // Register Elementor widgets
        add_action( 'elementor/widgets/register', [ $this, 'sht_register_widgets' ] );
      
        // Change placeholder text
        add_filter( 'tf_location_placeholder', [ $this, 'sht_location_placeholder_change' ], 10, 2 );
        
        // Shortcode for estimated reading time
        add_shortcode( 'sht_reading_time', [ $this,'sht_estimated_reading_time'] );

        // Shortcode to get current year
        add_shortcode( 'sht_year', [ $this,'sht_current_year'] );

        // filters
        add_filter('body_class', [ $this, 'sht_body_class' ]);
        add_filter('wp_list_categories', [ $this, 'sht_categories_list_filter' ]);

        // template override
        add_filter( 'tf_hotel_single_legacy_template', [ $this, 'sht_hotel_single_legacy_template' ] );
        add_filter( 'tf_hotel_archive_legacy_template', [ $this, 'sht_hotel_archive_legacy_template' ] );
    }

    // Enqueue frontend scripts and styles
    public function sht_enqueue_scripts() {
        wp_enqueue_style( 'sht-style-min-css', SPA_HOTEL_TOOLKIT_URL . 'assets/css/style.min.css', array(), time(), 'all' );
        wp_enqueue_style( 'sht-swiper-css', '//cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css', array(), SPA_HOTEL_TOOLKIT_VERSION, 'all' );
        wp_enqueue_script( 'sht-swiper-js', '//cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js', array('jquery'), SPA_HOTEL_TOOLKIT_VERSION, true );
        wp_enqueue_script( 'sht-main-js', SPA_HOTEL_TOOLKIT_URL . 'assets/js/main.js', array('jquery'), time(), true );
    }

    // Add custom category for Elementor widgets
    public function sht_elementor_widget_categories( $elements_manager ) {
        $elements_manager->add_category(
            'spa',
            [
                'title' => __( 'Spa Addons', 'spa-hotel-toolkit' ),
                'icon'  => 'fa fa-plug',
            ]
        );
    }

    // Register Elementor widgets
    public function sht_register_widgets( $widgets_manager ) {

        // If the Helper class does not exist, return early
        if ( ! class_exists( 'Tourfic\Classes\Helper' ) ) {
            return; 
        }

        require_once SPA_HOTEL_TOOLKIT_PATH . 'inc/elementor-widgets/spa-hotels.php';
        require_once SPA_HOTEL_TOOLKIT_PATH . 'inc/elementor-widgets/spa-booking-rator.php';
        require_once SPA_HOTEL_TOOLKIT_PATH . 'inc/elementor-widgets/spa-hotel-locations.php';
        require_once SPA_HOTEL_TOOLKIT_PATH . 'inc/elementor-widgets/spa-blog-posts.php';
        require_once SPA_HOTEL_TOOLKIT_PATH . 'inc/elementor-widgets/spa-latests-posts.php';
        require_once SPA_HOTEL_TOOLKIT_PATH . 'inc/elementor-widgets/spa-related-posts.php';
        $widgets_manager->register( new \Spa_Hotels() );
        $widgets_manager->register( new \Spa_Booking_Rator() );
        $widgets_manager->register( new \Spa_Hotel_Locations() );
        $widgets_manager->register( new \Spa_Blog_Posts() );
        $widgets_manager->register( new \Spa_Latest_Posts() );
        $widgets_manager->register( new \Spa_Related_Posts() );
    }


    // Change placeholder text
    public function sht_location_placeholder_change( $placeholder ) {
        $placeholder = esc_html__( 'Search for Spa Hotel...', 'spa-hotel-toolkit' );
        return $placeholder;
    }

    // Shortcode to estimate reading time
    public function sht_estimated_reading_time( $atts ) {
        global $post;

        $atts = shortcode_atts( array( 'wpm' => 200, ), $atts, 'reading_time');
        $content = get_post_field( 'post_content', $post->ID);
        $word_count = str_word_count( strip_tags( $content ));
        $minutes = ceil( $word_count / $atts['wpm'] );
        return $minutes . ' min read';
    }

    // Shortcode to get current year
    public function sht_current_year() {
        return date('Y');
    }    
    
    // Add custom body class
    public function sht_body_class( $classes ) {
        $classes[] = 'spa-hotel';
        return $classes;
    }

    // Categories list filter
    public function sht_categories_list_filter( $output ) {
        return preg_replace_callback('/\((\d+)\)/', function($matches) {
            return sprintf("%02d", $matches[1]);
        }, $output);
    }

    // Template override for single hotel legacy design
    public function sht_hotel_single_legacy_template( $template ) {
        $template = SPA_HOTEL_TOOLKIT_PATH . 'inc/templates/single/spa-single-hotel.php';
        return $template;
    }

    // Template override for hotel archive legacy design
    public function sht_hotel_archive_legacy_template( $template ) {
        $template = SPA_HOTEL_TOOLKIT_PATH . 'inc/templates/archive/spa-archive-hotels.php';
        return $template;
    }

}
