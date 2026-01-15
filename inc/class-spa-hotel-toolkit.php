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
        add_filter('tf_get_terms_dropdown_args', [$this, 'sht_get_terms_dropdown_args'], 10, 2);

        // filters for tourfic admin settings and metaboxes
        add_filter('tf_settings_sections', [$this, 'sht_tourfic_admin_setting_sections']);
        add_filter('tf_hotels_opt_sections', [$this, 'sht_hotels_opt_sections']);

        // template override
        add_filter('tf_hotel_single_legacy_template', [$this, 'sht_hotel_single_legacy_template']);
        add_filter('tf_hotel_archive_legacy_template', [$this, 'sht_hotel_archive_legacy_template']);
        add_filter('tf_hotel_location_archive_legacy_template', [$this, 'sht_hotel_location_archive_legacy_template'], 10, 5);
        add_filter('tf_search_result_legacy_template', [$this, 'sht_search_result_legacy_template']);

        // widget init  
        add_action('widgets_init', [$this, 'sht_widget_init'], 20);

        // Register taxonomies
        add_action('init', [$this, 'sht_register_hotel_facilities_taxonomy']);

        // Add custom archive filters
        include SHT_HOTEL_TOOLKIT_TEMPLATES . 'archive/spa-archive-filters.php';
    }


    // Enqueue frontend scripts and styles
    public function sht_enqueue_scripts()
    {
        wp_enqueue_style('sht-style-min-css', SHT_HOTEL_TOOLKIT_URL . 'assets/css/style.min.css', array(), SHT_HOTEL_TOOLKIT_VERSION, 'all');
        wp_enqueue_style('sht-swiper-css', '//cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css', array(), SHT_HOTEL_TOOLKIT_VERSION, 'all');
        wp_enqueue_script('sht-swiper-js', '//cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js', array('jquery'), SHT_HOTEL_TOOLKIT_VERSION, true);
        wp_enqueue_script('sht-ig-embed-js', SHT_HOTEL_TOOLKIT_URL . 'assets/js/ig-embed.js', array('jquery'), SHT_HOTEL_TOOLKIT_VERSION, true);
        wp_enqueue_script('sht-main-js', SHT_HOTEL_TOOLKIT_URL . 'assets/js/main.js', array('jquery'), SHT_HOTEL_TOOLKIT_VERSION, true);
        wp_localize_script('sht-main-js', 'sht_params', array(
            'nonce'            => wp_create_nonce('sht_ajax_nonce'),
            'ajax_url'               => admin_url('admin-ajax.php'),
            'map_marker_width' => !empty(Helper::tfopt('map_marker_width')) ? Helper::tfopt('map_marker_width') : '35',
            'map_marker_height' => !empty(Helper::tfopt('map_marker_height')) ? Helper::tfopt('map_marker_height') : '45',
        ));
        
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
        require_once SHT_HOTEL_TOOLKIT_PATH . 'inc/elementor-widgets/spa-faq.php';
        $widgets_manager->register(new \Spa_Hotels());
        $widgets_manager->register(new \Spa_Booking_Rator());
        $widgets_manager->register(new \Spa_Hotel_Locations());
        $widgets_manager->register(new \Spa_Blog_Posts());
        $widgets_manager->register(new \Spa_Latest_Posts());
        $widgets_manager->register(new \Spa_Related_Posts());
        $widgets_manager->register(new \Spa_Faq());
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
        $word_count = str_word_count(wp_strip_all_tags($content));
        $minutes = ceil($word_count / $atts['wpm']);
        return $minutes . ' min read';
    }

    // Shortcode to get current year
    public function sht_current_year()
    {
        return gmdate('Y');
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

    public function sht_hotel_location_archive_legacy_template($content, $post_type, $taxonomy, $taxonomy_name, $taxonomy_slug)
    {
        ob_start();
        include SHT_HOTEL_TOOLKIT_PATH . 'inc/templates/archive/spa-hotel-locations.php';
        return ob_get_clean();
    }

    public function sht_search_result_legacy_template()
    {
        ob_start();
        include SHT_HOTEL_TOOLKIT_PATH . 'inc/templates/archive/spa-search-result.php';
        return ob_get_clean();
    }

    // Change "Add Review" button text
    public function sht_add_review_button_text($text)
    {
        return esc_html__('Leave your review', 'spa-hotel-toolkit');
    }

    // Add custom fields to the review sections
    public function sht_tourfic_admin_setting_sections($sections)
    {

        // Add banner image for hotel archive
        if (isset($sections['tf-template-settings']['fields'][1]['tabs'][0]['fields'])) {

            // Go through all fields inside this section
            if (isset($sections['tf-template-settings']['fields'][1]['tabs'][0]['fields'])) {
                $new_field = array(
                    'id'       => 'hotel_archive_design_1_bannar',
                    'type'     => 'image',
                    'label'    => esc_html__('Archive & Search Result Banner Image', 'spa-hotel-toolkit'),
                    'subtitle' => esc_html__('Upload Banner Image for this hotel archive template.', 'spa-hotel-toolkit'),
                    'library'  => 'image',
                    'default'  => SHT_HOTEL_TOOLKIT_ASSETS . "images/archive-hero.png",
                    'dependency' => array('hotel-archive', '==', 'default'),
                );
                $fields = &$sections['tf-template-settings']['fields'][1]['tabs'][0]['fields'];
                $inserted = false;

                foreach ($fields as $index => $field) {
                    if (isset($field['id']) && $field['id'] === 'hotel-archive') {
                        array_splice($fields, $index + 1, 0, array($new_field));
                        $inserted = true;
                        break;
                    }
                }
                if (! $inserted) {
                    $fields[] = $new_field;
                }
            }
        }

        // FAQ section
        $faq_section = array(
            'faq' => array(
                'title'  => __('FAQ', 'spa-hotel-toolkit'),
                'icon'   => 'fa-solid fa-question',
                'fields' => array(
                    array(
                        'id'       => 'faq-subtitle',
                        'type'     => 'text',
                        'label'    => __('FAQ Subtitle', 'spa-hotel-toolkit'),
                        'default'  => __('FAQ', 'spa-hotel-toolkit'),
                    ),
                    array(
                        'id'       => 'faq-title',
                        'type'     => 'text',
                        'label'    => __('FAQ Title', 'spa-hotel-toolkit'),
                        'default'  => __("Got Questions? We've Got Answers.", 'spa-hotel-toolkit'),
                    ),
                    array(
                        'id'       => 'faq-description',
                        'type'     => 'textarea',
                        'label'    => __('FAQ Description', 'spa-hotel-toolkit'),
                        'default'  => __('Everything you need to know about finding and booking your perfect spa getaway.', 'spa-hotel-toolkit'),
                    ),
                    array(
                        'id'       => 'faq-items',
                        'type'     => 'repeater',
                        'label'    => __('FAQ Items', 'spa-hotel-toolkit'),
                        'button_title' => esc_html__('Add New', 'spa-hotel-toolkit'),
                        'subtitle' => __('Add multiple FAQ questions and answers', 'spa-hotel-toolkit'),
                        'fields'   => array(
                            array(
                                'id'       => 'faq-question',
                                'type'     => 'text',
                                'label'    => __('Question', 'spa-hotel-toolkit'),
                                'default'  => '',
                            ),
                            array(
                                'id'       => 'faq-answer',
                                'type'     => 'textarea',
                                'label'    => __('Answer', 'spa-hotel-toolkit'),
                                'default'  => '',
                            ),
                        ),
                    ),
                ),
            ),
        );

        $position = 10;
        $sections = array_slice($sections, 0, $position, true)
            + $faq_section
            + array_slice($sections, $position, null, true);


        // testimonial section
        $testimonial_section = array(
            'testimonial' => array(
                'title'  => __('Testimonial', 'spa-hotel-toolkit'),
                'icon'   => 'fa-regular fa-star',
                'fields' => array(
                    array(
                        'id'       => 'testimonial-subtitle',
                        'type'     => 'text',
                        'label'    => __('Testimonial Subtitle', 'spa-hotel-toolkit'),
                        'default'  => __('Testimonial', 'spa-hotel-toolkit'),
                    ),
                    array(
                        'id'       => 'testimonial-title',
                        'type'     => 'text',
                        'label'    => __('Testimonial Title', 'spa-hotel-toolkit'),
                        'default'  => __("Trusted by 1200+ World Class Business", 'spa-hotel-toolkit'),
                    ),
                    array(
                        'id'       => 'testimonial-description',
                        'type'     => 'textarea',
                        'label'    => __('Testimonial Description', 'spa-hotel-toolkit'),
                    ),
                ),
            ),
        );

        $position = 11;
        $sections = array_slice($sections, 0, $position, true)
            + $testimonial_section
            + array_slice($sections, $position, null, true);


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

        $custom_content = '<h4>' . esc_html($review_popup_title) . '</h4>';
        $custom_content .= '<p>' . esc_html($review_popup_desc) . '</p>';
        return $custom_content;
    }

    // Add custom sections to hotel metabox
    public function sht_hotels_opt_sections($sections)
    {

        $tf_hotel_review = Helper::tf_data_types(Helper::tfopt('r-hotel')) ?: [];
        $sht_score_fields = [];

        if (!empty($tf_hotel_review)) {
            foreach ($tf_hotel_review as $field) {

                $label = isset($field['r-field-type']) ? $field['r-field-type'] : 'Custom';
                $slug = sanitize_title($label);

                $sht_score_fields[] = array(
                    'id'      => "rator-{$slug}-score",
                    'type'    => 'number',
                    'label'   => __($label . ' Score', 'spa-hotel-toolkit'),
                    'subtitle' => esc_html__('Enter a number between 0 and 10', 'spa-hotel-toolkit'),
                    'attributes' => array(
                        'min' => '0',
                        'max' => '10',
                    ),
                );
            }
        }


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

        // Brand rating section
        $sections['spa_rator'] = array(
            'title' => __('Spa Rator', 'spa-hotel-toolkit'),
            'icon'  => 'fa-solid fa-spa',
            'fields' => array_merge(
                [

                    // Fixed fields
                    array(
                        'id'      => 'rator-heading',
                        'type'    => 'heading',
                        'title'   => __('Spa Rator Settings', 'spa-hotel-toolkit'),
                        'content' => esc_html__('These are some spa rator settings specific to this Hotel.', 'spa-hotel-toolkit'),
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
                        'default' => __("This spa provides everything any wellness enthusiast could need. From signature treatments to world-class facilities, this hotel has created a transformative spa atmosphere.", 'spa-hotel-toolkit'),
                    ),
                    array(
                        'id'    => 'rator-heading',
                        'type'  => 'heading',
                        'title' => __('SpaRator Scores', 'spa-hotel-toolkit'),
                    ),

                ],
                $sht_score_fields
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
                    'title'   => __('Availability Settings', 'spa-hotel-toolkit'),
                    'content' => esc_html__('These are some availability settings specific to this Hotel.', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'availability-btn-label',
                    'type'    => 'text',
                    'label'   => __('Availability Button', 'spa-hotel-toolkit'),
                    'default' => __('Check Availability', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'availability-btn-link',
                    'type'    => 'text',
                    'label'   => __('Availability Button Link', 'spa-hotel-toolkit'),
                    'default' => __('#', 'spa-hotel-toolkit'),
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
                    'title'   => __('Rating Settings', 'spa-hotel-toolkit'),
                    'content' => esc_html__('These are some rating settings specific to this Hotel.', 'spa-hotel-toolkit'),
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
                    'title'   => __('Booking', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'rating-booking-url',
                    'type'    => 'text',
                    'label'   => __('URL', 'spa-hotel-toolkit'),
                    'default' => '#',
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
                    'title'   => __('Google', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'rating-google-url',
                    'type'    => 'text',
                    'label'   => __('URL', 'spa-hotel-toolkit'),
                    'default' => '#',
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
                    'title'   => __('Tripadvisor', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'rating-tripadvisor-url',
                    'type'    => 'text',
                    'label'   => __('URL', 'spa-hotel-toolkit'),
                    'default' => '#',
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
                    'title'   => __('Feeds Settings', 'spa-hotel-toolkit'),
                    'content' => esc_html__('These are some instagram feeds settings specific to this Hotel.', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'feeds-sec-title',
                    'type'    => 'text',
                    'label'   => __('Section Title', 'spa-hotel-toolkit'),
                    'default' => __('Guest Experiences', 'spa-hotel-toolkit'),
                ),
                array(
                    'id'      => 'instagram-posts',
                    'type'         => 'repeater',
                    'label' => esc_html__('Insert / Create Instagram Posts', 'spa-hotel-toolkit'),
                    'button_title' => esc_html__('Add New', 'spa-hotel-toolkit'),
                    'class'        => 'tf-field-class',
                    'fields'       => array(
                        array(
                            'id'          => 'instagram-post-url',
                            'type'        => 'text',
                            'label'       => esc_html__('Post URL', 'spa-hotel-toolkit'),
                            'placeholder' => esc_html__('Add post URL', 'spa-hotel-toolkit'),
                        ),
                    ),
                ),
            ),
        );


        return $sections;
    }

    // Register widgets
    public function sht_widget_init()
    {

        require_once SHT_HOTEL_TOOLKIT_PATH . 'inc/widgets/class-hotel-score-filter.php';
        require_once SHT_HOTEL_TOOLKIT_PATH . 'inc/widgets/class-hotel-feature-filter.php';
        require_once SHT_HOTEL_TOOLKIT_PATH . 'inc/widgets/class-hotel-other-facility-filter.php';
        require_once SHT_HOTEL_TOOLKIT_PATH . 'inc/widgets/class-hotel-rating-filter.php';
        require_once SHT_HOTEL_TOOLKIT_PATH . 'inc/widgets/class-hotel-sort-by-filter.php';
        register_widget('Spa_Hotel_Toolkit\Widgets\Sht_Hotel_Score_Filter');
        register_widget('Spa_Hotel_Toolkit\Widgets\Sht_Hotel_Feature_Filter');
        register_widget('Spa_Hotel_Toolkit\Widgets\Sht_Hotel_Other_Facility_Filter');
        register_widget('Spa_Hotel_Toolkit\Widgets\Sht_Rating_Filter_Widget');
        register_widget('Spa_Hotel_Toolkit\Widgets\Sht_Hotel_Sort_By_Filter');
    }

    /**
     * Taxonomy: Facilities.
     */

    public function sht_register_hotel_facilities_taxonomy()
    {

        $labels = [
            "name" => esc_html__("Facilities", "spa-hotel-toolkit"),
            "singular_name" => esc_html__("Facility", "spa-hotel-toolkit"),
        ];


        $args = [
            "label" => esc_html__("Facilities", "spa-hotel-toolkit"),
            "labels" => $labels,
            "public" => true,
            "publicly_queryable" => true,
            "hierarchical" => true,
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => ['slug' => 'hotel_facilities', 'with_front' => true,],
            "show_admin_column" => true,
            "show_in_rest" => true,
            "show_tagcloud" => false,
            "rest_base" => "hotel_facilities",
            "rest_controller_class" => "WP_REST_Terms_Controller",
            "rest_namespace" => "wp/v2",
            "show_in_quick_edit" => false,
            "sort" => false,
            "show_in_graphql" => false,
        ];
        register_taxonomy("hotel_facilities", ["tf_hotel"], $args);
    }

    // Terms dropdown
    public function sht_get_terms_dropdown_args($args, $taxonomy)
    {
        if ($taxonomy === 'hotel_location') {
            $args['parent'] = 0;
        }
        return $args;
    }

  

}
