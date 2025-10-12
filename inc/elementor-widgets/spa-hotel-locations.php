<?php

use \Tourfic\Classes\Hotel\Pricing;

use \Tourfic\Classes\Room\Room;

class Spa_Hotel_Locations extends \Elementor\Widget_Base
{
    /**
     * Get widget name.
     *
     * Retrieve  widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'spa-hotel-locations';
    }

    /**
     * Get widget title.
     *
     * Retrieve  widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('Spa Hotel Locations', 'spa-hotel-toolkit');
    }

    /**
     * Get widget icon.
     *
     * Retrieve  widget icon.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-google-maps';
    }

    /**
     * Get custom help URL.
     *
     * Retrieve a URL where the user can get more information about the widget.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget help URL.
     */
    public function get_custom_help_url()
    {
        return 'https://developers.elementor.com/docs/widgets/';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget categories.
     */
    public function get_categories(): array
    {
        return ['spa'];
    }


    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget keywords.
     */
    public function get_keywords()
    {
        return ['spa', 'hotel', 'locations'];
    }
    public function get_style_depends()
    {
        return ['spa-hotel-locations'];
    }
    /**
     * Register widget controls.
     *
     * Add input fields to allow the user to customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls()
    {

        $this->start_controls_section(
            'locations',
            [
                'label' => __('Locations', 'spa-hotel-toolkit'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'post_order_by',
            [
                'type' => \Elementor\Controls_Manager::SELECT,
                'label' => __('Order by', 'spa-hotel-toolkit'),
                'default' => 'date',
                'options' => [
                    'date' => __('Date', 'spa-hotel-toolkit'),
                    'title' => __('Title', 'spa-hotel-toolkit'),
                    'modified' => __('Modified date', 'spa-hotel-toolkit'),
                ],
            ]
        );

        $this->add_control(
            'post_items',
            [
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'label'       => __('Item Per page', 'spa-hotel-toolkit'),
                'placeholder' => __('9', 'spa-hotel-toolkit'),
                'default'     => 9,
            ]
        );

        // Order
        $this->add_control(
            'post_order',
            [
                'type' => \Elementor\Controls_Manager::SELECT,
                'label' => __('Order', 'spa-hotel-toolkit'),
                'default' => 'DESC',
                'options' => [
                    'DESC' => __('Descending', 'spa-hotel-toolkit'),
                    'ASC' => __('Ascending', 'spa-hotel-toolkit')
                ],
            ]
        );

        $this->end_controls_section();

        $this->end_controls_tab();

        $this->end_controls_tabs();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $sht_hotel_locations = get_terms(array(
            'taxonomy'   => 'hotel_location',
            'orderby'    => $settings['post_order_by'],
            'order'      => $settings['post_order'],
            'number'     => $settings['post_items'],
            'hide_empty' => true,
        ));


        $sht_hotel_prices = [];

?>
        <div class="sht-locations-wrapper">
            <div class="sht-locations-content">
                <?php if (! empty($sht_hotel_locations) && ! is_wp_error($sht_hotel_locations)) :
                    foreach ($sht_hotel_locations as $index => $location) :
                        $term_id = $location->term_id;
                        $term_name = $location->name;
                        $term_desc   = $location->description;
                        $term_count  = $location->count;

                        //  Get all hotels for this location
                        $hotel_args = array(
                            'post_type'      => 'tf_hotel',
                            'posts_per_page' => -1,
                            'tax_query'      => array(
                                array(
                                    'taxonomy' => 'hotel_location',
                                    'field'    => 'term_id',
                                    'terms'    => $term_id,
                                ),
                            ),
                        );

                        $query = new WP_Query($hotel_args);

                        if ($query->have_posts()) {
                            $prices = array();

                            while ($query->have_posts()) {
                                $query->the_post();
                                $post_id = get_the_ID();

                                // Get min price using your Pricing class
                                $sht_min_price = Pricing::instance($post_id)->get_min_price();
                                $sht_price = !empty($sht_min_price['min_sale_price']) ? $sht_min_price['min_sale_price'] : 0;
                                if ($sht_price) {
                                    $prices[] = $sht_price;
                                }
                            }

                            // Store min price for this location
                            $sht_hotel_prices[$location->slug] = ! empty($prices) ? min($prices) : 0;

                            wp_reset_postdata();
                        }

                        $tf_location_meta  = get_term_meta($term_id, 'tf_hotel_location', true);
                        $tf_location_image = ! empty($tf_location_meta['image']) ? $tf_location_meta['image'] : SHT_HOTEL_TOOLKIT_ASSETS . 'images/location-bg-fallback.png';
                        $location_featured = ! empty($tf_location_meta['featured']) ? $tf_location_meta['featured'] : '';

                        $rooms = Room::get_hotel_rooms($post_id);
                        $room_id = ! empty($rooms) ? $rooms[0]->ID : '';

                        $rating_badge = sht_sparator_rating_badge($post_id);
                ?>
                        <!-- single location -->
                        <div class="sht-location-single-item" style="background: linear-gradient(180deg, rgba(46,29,12,0) 48.2%, #2E1D0C 100%), url('<?php echo esc_url($tf_location_image); ?>') lightgray 50% / cover no-repeat;">

                            <div class="sht-locations-badges">
                                <?php if (!empty($location_featured)): ?>
                                    <div class="sht-badge sht-badge-secondary sht-badge-border">
                                        <?php echo esc_html($location_featured); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="sht-badge">
                                    <?php echo esc_html($term_count) . ' ' . esc_html__('Hotel available', 'spa-hotel-toolkit'); ?>
                                </div>
                            </div>
                            <div class="sht-location-item-content">
                                <div class="sht-rator-badge <?php echo esc_attr($rating_badge['class']); ?>">
                                    <?php echo esc_html($rating_badge['text']); ?>
                                </div>
                                <div class="sht-locaiton-content-middle">
                                    <div class="sht-locaiton-content-info">
                                        <h3 class="sht-location-item-title">
                                            <a href="<?php echo esc_url(get_term_link($location)); ?>">
                                                <?php echo esc_html($term_name); ?>
                                            </a>
                                        </h3>
                                        <?php if (!empty($term_desc)): ?>
                                            <div class="sht-location-item-description">
                                                <p><?php echo wp_kses_post($term_desc); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="sht-location-item-price">
                                        <?php echo esc_html__('Starting from', 'spa-hotel-toolkit'); ?>
                                        <span class="sht-location-item-price-value">
                                            <?php if (function_exists('wc_price') && isset($sht_hotel_prices[$location->slug])) {
                                                echo wp_kses_post(wc_price($sht_hotel_prices[$location->slug]));
                                            } else {
                                                // Fallback if WooCommerce is not active
                                                echo '$' . number_format($sht_hotel_prices[$location->slug], 2);
                                            }
                                            ?>
                                            <div class="price-per-label"><?php echo esc_attr__('night', 'spa-hotel-toolkit'); ?></div>
                                        </span>
                                    </div>
                                </div>

                                <a href="<?php echo esc_url(get_term_link($location)); ?>" class="sht-btn sht-btn-transparent">
                                    <?php echo esc_html('View ' . $term_count . ' Spa Hotels'); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M4.16675 9.99984H15.8334M15.8334 9.99984L10.0001 4.1665M15.8334 9.99984L10.0001 15.8332" stroke="#D4A574" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>

                            </div>
                        </div>
                <?php endforeach;
                endif; ?>

            </div>
        </div>

<?php
    }
}
