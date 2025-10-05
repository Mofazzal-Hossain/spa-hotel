<?php


class Spa_Hotels extends \Elementor\Widget_Base
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
        return 'spa-hotels';
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
        return esc_html__('Spa Hotels', 'spa-hotel-toolkit');
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
        return 'eicon-posts-justified';
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
        return ['spa', 'hotels'];
    }
    public function get_style_depends()
    {
        return ['spa-hotels'];
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
            'hotels',
            [
                'label' => __('Hotels', 'spa-hotel-toolkit'),
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
                'placeholder' => __('6', 'spa-hotel-toolkit'),
                'default'     => 6,
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

        $args = array(
            'post_type'   => 'tf_hotel'
        );

        // Items per page
        if (! empty($settings['post_items'])) {
            $args['posts_per_page'] = $settings['post_items'];
        }

        // Items Order By
        if (! empty($settings['post_order_by'])) {
            $args['orderby'] = $settings['post_order_by'];
        }

        // Items Order
        if (! empty($settings['post_order'])) {
            $args['order'] = $settings['post_order'];
        }

        $query = new \WP_Query($args);
        include SHT_HOTEL_TOOLKIT_PATH . 'inc/common/spa-hotel-slider-contents.php';
?>
        
<?php
    }
}
