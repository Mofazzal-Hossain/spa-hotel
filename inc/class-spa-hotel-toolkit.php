<?php
if (! defined('ABSPATH')) exit;

use \Tourfic\Classes\Helper;


class Sht_Hotel_Toolkit
{

    public function __construct()
    {
        // Enqueue frontend scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'sht_enqueue_scripts']);

        // Register widget category
        add_action('elementor/elements/categories_registered', [$this, 'sht_elementor_widget_categories']);

        // Register Elementor widgets
        add_action('elementor/widgets/register', [$this, 'sht_register_widgets']);

        // Change placeholder text
        add_filter('tf_location_placeholder', [$this, 'sht_location_placeholder_change'], 10, 2);

        // Shortcode for estimated reading time
        add_shortcode('sht_reading_time', [$this, 'sht_estimated_reading_time']);

        // Shortcode to get current year
        add_shortcode('sht_year', [$this, 'sht_current_year']);

        // filters
        add_filter('body_class', [$this, 'sht_body_class']);
        add_filter('wp_list_categories', [$this, 'sht_categories_list_filter']);
        add_filter('tourfic_add_review_button_text', [$this, 'sht_add_review_button_text']);
        add_filter('tf_rating_modal_header_content', [$this, 'sht_rating_modal_header_content']);

        // filters for tourfic admin settings and metaboxes
        add_filter('tf_settings_sections', [$this, 'sht_tourfic_admin_setting_sections']);
        add_filter('tf_hotels_opt_sections', [$this, 'sht_hotels_opt_sections']);

        // template override
        add_filter('tf_hotel_single_legacy_template', [$this, 'sht_hotel_single_legacy_template']);
        add_filter('tf_hotel_archive_legacy_template', [$this, 'sht_hotel_archive_legacy_template']);
    }

    // Enqueue frontend scripts and styles
    public function sht_enqueue_scripts()
    {
        wp_enqueue_style('sht-style-min-css', SHT_HOTEL_TOOLKIT_URL . 'assets/css/style.min.css', array(), time(), 'all');
        wp_enqueue_style('sht-swiper-css', '//cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css', array(), SHT_HOTEL_TOOLKIT_VERSION, 'all');
        wp_enqueue_script('sht-swiper-js', '//cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js', array('jquery'), SHT_HOTEL_TOOLKIT_VERSION, true);
        wp_enqueue_script('sht-main-js', SHT_HOTEL_TOOLKIT_URL . 'assets/js/main.js', array('jquery'), time(), true);
    }

    // Add custom category for Elementor widgets
    public function sht_elementor_widget_categories($elements_manager)
    {
        $elements_manager->add_category(
            'spa',
            [
                'title' => __('Spa Addons', 'spa-hotel-toolkit'),
                'icon'  => 'fa fa-plug',
            ]
        );
    }

    // Register Elementor widgets
    public function sht_register_widgets($widgets_manager)
    {

        // If the Helper class does not exist, return early
        if (! class_exists('Tourfic\Classes\Helper')) {
            return;
        }

        require_once SHT_HOTEL_TOOLKIT_PATH . 'inc/elementor-widgets/spa-hotels.php';
        require_once SHT_HOTEL_TOOLKIT_PATH . 'inc/elementor-widgets/spa-booking-rator.php';
        require_once SHT_HOTEL_TOOLKIT_PATH . 'inc/elementor-widgets/spa-hotel-locations.php';
        require_once SHT_HOTEL_TOOLKIT_PATH . 'inc/elementor-widgets/spa-blog-posts.php';
        require_once SHT_HOTEL_TOOLKIT_PATH . 'inc/elementor-widgets/spa-latests-posts.php';
        require_once SHT_HOTEL_TOOLKIT_PATH . 'inc/elementor-widgets/spa-related-posts.php';
        $widgets_manager->register(new \Spa_Hotels());
        $widgets_manager->register(new \Spa_Booking_Rator());
        $widgets_manager->register(new \Spa_Hotel_Locations());
        $widgets_manager->register(new \Spa_Blog_Posts());
        $widgets_manager->register(new \Spa_Latest_Posts());
        $widgets_manager->register(new \Spa_Related_Posts());
    }


    // Change placeholder text
    public function sht_location_placeholder_change($placeholder)
    {
        $placeholder = esc_html__('Search for Spa Hotel...', 'spa-hotel-toolkit');
        return $placeholder;
    }

    // Shortcode to estimate reading time
    public function sht_estimated_reading_time($atts)
    {
        global $post;

        $atts = shortcode_atts(array('wpm' => 200,), $atts, 'reading_time');
        $content = get_post_field('post_content', $post->ID);
        $word_count = str_word_count(strip_tags($content));
        $minutes = ceil($word_count / $atts['wpm']);
        return $minutes . ' min read';
    }

    // Shortcode to get current year
    public function sht_current_year()
    {
        return date('Y');
    }

    // Add custom body class
    public function sht_body_class($classes)
    {
        $classes[] = 'spa-hotel';
        return $classes;
    }

    // Categories list filter
    public function sht_categories_list_filter($output)
    {
        return preg_replace_callback('/\((\d+)\)/', function ($matches) {
            return sprintf("%02d", $matches[1]);
        }, $output);
    }

    // Template override for single hotel legacy design
    public function sht_hotel_single_legacy_template($template)
    {
        $template = SHT_HOTEL_TOOLKIT_PATH . 'inc/templates/single/spa-single-hotel.php';
        return $template;
    }

    // Template override for hotel archive legacy design
    public function sht_hotel_archive_legacy_template($template)
    {
        $template = SHT_HOTEL_TOOLKIT_PATH . 'inc/templates/archive/spa-archive-hotels.php';
        return $template;
    }

    // Change "Add Review" button text
    public function sht_add_review_button_text($text)
    {
        return esc_html__('Leave your review', 'spa-hotel-toolkit');
    }

    // Add custom fields to the review sections
    public function sht_tourfic_admin_setting_sections($sections)
    {
        if (isset($sections['review'])) {

            // Add title
            $sections['review']['fields'][] = array(
                'id'       => 'review-popup-title',
                'type'     => 'text',
                'label'    => __('Review Title', 'spa-hotel-toolkit'),
                'default'  => __('Leave your review', 'spa-hotel-toolkit'),
                'subtitle' => __('Add title for review popup', 'spa-hotel-toolkit'),
            );

            // Add description
            $sections['review']['fields'][] = array(
                'id'       => 'review-popup-description',
                'type'     => 'textarea',
                'label'    => __('Review Description', 'spa-hotel-toolkit'),
                'default'  => __('Your email address will not be published. Required fields are marked.', 'spa-hotel-toolkit'),
                'subtitle' => __('Add description for review popup', 'spa-hotel-toolkit'),
            );

            if (!empty($sections['review']['fields'])) {
                foreach ($sections['review']['fields'] as &$field) {
                    if (isset($field['id']) && $field['id'] === 'r-hotel' && isset($field['fields'])) {

                        $field['fields'][] = array(
                            'id'    => 'r-field-icon',
                            'type'  => 'image',
                            'label' => __('Image', 'spa-hotel-toolkit'),
                            'url'   => true,
                        );
                    }
                }
            }
        }

        return $sections;
    }

    // Add content to the rating modal header
    public function sht_rating_modal_header_content($content)
    {
        $review_popup_title = ! empty(Helper::tfopt('review-popup-title')) ? sanitize_text_field(Helper::tfopt('review-popup-title')) : 'Leave your review';
        $review_popup_desc = ! empty(Helper::tfopt('review-popup-description')) ? sanitize_text_field(Helper::tfopt('review-popup-description')) : 'Your email address will not be published. Required fields are marked.';

        $custom_content = '<h4>' . esc_html__($review_popup_title, 'spa-hotel-toolkit') . '</h4>';
        $custom_content .= '<p>' . esc_html__($review_popup_desc, 'spa-hotel-toolkit') . '</p>';
        return $custom_content;
    }

    // Add custom sections to hotel metabox
    public function sht_hotels_opt_sections($sections)
    {

        // inside hotel information Other Facilities
        $sections['hotel_info']['fields'][] = array(
            'id'      => 'other-facilities-heading',
            'type'    => 'heading',
            'content' => esc_html__('Other Facilities', 'spa-hotel-toolkit'),
            'class'   => 'tf-field-class',
        );
        $sections['hotel_info']['fields'][] = array(
            'id'          => 'other-facilities-title',
            'type'        => 'text',
            'label'       => esc_html__('Facilities Title', 'spa-hotel-toolkit'),
            'placeholder' => esc_html__("Other facilities", 'spa-hotel-toolkit'),
            'default' => esc_html__("Other facilities", 'spa-hotel-toolkit'),
            'attributes'  => array(
                'required' => 'required',
            ),
        );
        $sections['hotel_info']['fields'][] = array(
            'id'           => 'hotel-other-facilities',
            'type'         => 'repeater',
            'label' => esc_html__('Insert / Create Other Facilities', 'spa-hotel-toolkit'),
            'button_title' => esc_html__('Add New', 'spa-hotel-toolkit'),
            'class'        => 'tf-field-class',
            'fields'       => array(
                array(
                    'id'          => 'facilities-feature',
                    'type'        => 'text',
                    'label'       => esc_html__('Facilities Feature', 'spa-hotel-toolkit'),
                    'placeholder' => esc_html__('Add facilities', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'       => 'other-facilities-icon',
                    'type'     => 'icon',
                    'label'    => esc_html__('Facilities Icon', 'spa-hotel-toolkit'),
                    'subtitle' => esc_html__('Choose an appropriate icon', 'spa-hotel-toolkit'),
                    'default'  => 'fa fa-check',
                ),
            ),
        );

        // Brand rating section
        $sections['spa_rator'] = array(
            'title'  => __('Spa Rator', 'spa-hotel-toolkit'),
            'icon'   => 'fa-solid fa-spa',
            'fields' => array(
                array(
                    'id'      => 'rator-heading',
                    'type'    => 'heading',
                    'label'   => __('Spa Rator Settings', 'spa-hotel-toolkit'),
                    'subtitle' => esc_html__('These are some spa rator settings specific to this Hotel.', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'rator-sec-title',
                    'type'    => 'text',
                    'label'   => __('Section Title', 'spa-hotel-toolkit'),
                    'default' => __('SpaRator', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'rator-overall-text',
                    'type'    => 'text',
                    'label'   => __('Overall Text', 'spa-hotel-toolkit'),
                    'default' => __('Overall Spa Score', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'rator-description',
                    'type'    => 'textarea',
                    'label'   => __('Description', 'spa-hotel-toolkit'),
                    'default' => __("This spa provides everything any wellness enthusiast could need. From signature treatments to world-class facilities, this hotel has created a transformative spa atmosphere that you won't want to miss.", 'spa-hotel-toolkit'),
                ),
            ),
        );

        // Availability section
        $sections['availability'] = array(
            'title'  => __('Availability', 'spa-hotel-toolkit'),
            'icon'   => 'fa-regular fa-calendar-check',
            'fields' => array(
                array(
                    'id'      => 'availability-heading',
                    'type'    => 'heading',
                    'label'   => __('Availability Settings', 'spa-hotel-toolkit'),
                    'subtitle' => esc_html__('These are some availability settings specific to this Hotel.', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'availability-sec-title',
                    'type'    => 'text',
                    'label'   => __('Section Title', 'spa-hotel-toolkit'),
                    'default' => __('Pricing & Availability', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'availability-peak-session',
                    'type'    => 'text',
                    'label'   => __('Peak Session', 'spa-hotel-toolkit'),
                    'default' => __('Peak season: $350-450 | Off season: $200-300', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'availability-rates-info',
                    'type'    => 'text',
                    'label'   => __('Rates Info', 'spa-hotel-toolkit'),
                    'default' => __('Rates vary by date, availability, and booking platform. Prices shown are indicative and subject to change.', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'availability-booking',
                    'type'         => 'repeater',
                    'label' => esc_html__('Insert / Create Booking', 'spa-hotel-toolkit'),
                    'button_title' => esc_html__('Add New', 'spa-hotel-toolkit'),
                    'class'        => 'tf-field-class',
                    'fields'       => array(
                        array(
                            'id'          => 'availability-booking-url',
                            'type'        => 'text',
                            'label'       => esc_html__('Booking URL', 'spa-hotel-toolkit'),
                            'placeholder' => esc_html__('Add booking URL', 'spa-hotel-toolkit'),
                        ),
                        array(
                            'id'       => 'availability-platform-logo',
                            'type'     => 'image',
                            'label'    => esc_html__('Platform Logo', 'spa-hotel-toolkit'),
                            'subtitle' => esc_html__('Choose an appropriate platform logo', 'spa-hotel-toolkit'),
                        ),
                    ),
                ),
            ),
        );

        // Brand rating section
        $sections['brand_rating'] = array(
            'title'  => __('Brand Rating', 'spa-hotel-toolkit'),
            'icon'   => 'fa-solid fa-star',
            'fields' => array(
                array(
                    'id'      => 'rating-heading',
                    'type'    => 'heading',
                    'label'   => __('Rating Settings', 'spa-hotel-toolkit'),
                    'subtitle' => esc_html__('These are some rating settings specific to this Hotel.', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'rating-sec-title',
                    'type'    => 'text',
                    'label'   => __('Section Title', 'spa-hotel-toolkit'),
                    'default' => __('About', 'spa-hotel-toolkit'),
                ),
                // Booking rating
                array(
                    'id'      => 'rating-booking-heading',
                    'type'    => 'heading',
                    'label'   => __('Booking', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'rating-booking-feedback',
                    'type'    => 'text',
                    'label'   => __('Feedback', 'spa-hotel-toolkit'),
                    'default' => __('Very Good', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'rating-booking-score',
                    'type'    => 'text',
                    'label'   => __('Rating', 'spa-hotel-toolkit'),
                    'default' => __('8.2', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'rating-booking-desc',
                    'type'    => 'text',
                    'label'   => __('Description', 'spa-hotel-toolkit'),
                    'default' => __('250 reviews on Booking.com', 'spa-hotel-toolkit'),
                ),
                // Google rating
                array(
                    'id'      => 'rating-google-heading',
                    'type'    => 'heading',
                    'label'   => __('Google', 'spa-hotel-toolkit'),
                ),

                array(
                    'id'      => 'rating-google-score',
                    'type'    => 'text',
                    'label'   => __('Rating', 'spa-hotel-toolkit'),
                    'default' => __('4.5', 'spa-hotel-toolkit'),
                ),

                array(
                    'id'      => 'rating-google-desc',
                    'type'    => 'text',
                    'label'   => __('Description', 'spa-hotel-toolkit'),
                    'default' => __('250 reviews on Google', 'spa-hotel-toolkit'),
                ),

                // tripadvisor rating
                array(
                    'id'      => 'rating-tripadvisor-heading',
                    'type'    => 'heading',
                    'label'   => __('Tripadvisor', 'spa-hotel-toolkit'),
                ),

                array(
                    'id'      => 'rating-tripadvisor-score',
                    'type'    => 'text',
                    'label'   => __('Rating', 'spa-hotel-toolkit'),
                    'default' => __('5.0', 'spa-hotel-toolkit'),
                ),

                array(
                    'id'      => 'rating-tripadvisor-desc',
                    'type'    => 'text',
                    'label'   => __('Description', 'spa-hotel-toolkit'),
                    'default' => __('250 reviews on TripAdvisor', 'spa-hotel-toolkit'),
                ),
            ),
        );


        // Brand rating section
        $sections['insta_feeds'] = array(
            'title'  => __('Instagram Feeds', 'spa-hotel-toolkit'),
            'icon'   => 'fa-brands fa-instagram',
            'fields' => array(
                array(
                    'id'      => 'feeds-heading',
                    'type'    => 'heading',
                    'label'   => __('Feeds Settings', 'spa-hotel-toolkit'),
                    'subtitle' => esc_html__('These are some instagram feeds settings specific to this Hotel.', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'feeds-sec-title',
                    'type'    => 'text',
                    'label'   => __('Section Title', 'spa-hotel-toolkit'),
                    'default' => __('Guest Experiences', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'feeds-account-id',
                    'type'    => 'text',
                    'label'   => __('Account ID', 'spa-hotel-toolkit'),
                    'placeholder' => __('Add your account ID', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'feeds-username',
                    'type'    => 'text',
                    'label'   => __('Username', 'spa-hotel-toolkit'),
                    'placeholder' => __('Add your username', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'feeds-access-token',
                    'type'    => 'text',
                    'label'   => __('Access Token', 'spa-hotel-toolkit'),
                    'placeholder' => __('Add your access token', 'spa-hotel-toolkit'),
                ),
            ),
        );


        return $sections;
    }
}
