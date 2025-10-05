<?php
class Spa_Latest_Posts extends \Elementor\Widget_Base {
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
        return 'spa-latest-posts';
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
        return esc_html__('Spa Latest Posts', 'spa-hotel-toolkit');
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
        return 'eicon-posts-grid';
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
    public function get_categories(): array {
		return [ 'spa' ];
	}

    public function grid_get_all_post_type_categories($post_type)
    {
        $options = array();

        if ($post_type == 'post') {
            $taxonomy = 'category';
        }

        if (! empty($taxonomy)) {
            // Get categories for post type.
            $terms = get_terms(
                array(
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => false,
                )
            );
            if (! empty($terms)) {
                foreach ($terms as $term) {
                    if (isset($term)) {
                        if (isset($term->slug) && isset($term->name)) {
                            $options[$term->slug] = $term->name;
                        }
                    }
                }
            }
        }

        return $options;
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
        return ['spa', 'blog', 'latest'];
    }
    public function get_style_depends()
    {
        return ['spa-latest-news'];
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
            'blog_news',
            [
                'label' => __('Blog News', 'spa-hotel-toolkit'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Category name
        $this->add_control(
            'post_category',
            [
                'type'     => \Elementor\Controls_Manager::SELECT2,
                'label'     => __('Category', 'spa-hotel-toolkit'),
                'options'   => $this->grid_get_all_post_type_categories('post'),
                'multiple' => true,
            ]
        );

        $this->add_control(
            'post_items',
            [
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'label'       => __('Item Per page', 'spa-hotel-toolkit'),
                'placeholder' => __('4', 'spa-hotel-toolkit'),
                'default'     => 4,
            ]
        );
        // Order by.
        $this->add_control(
            'post_order_by',
            [
                'type'    => \Elementor\Controls_Manager::SELECT,
                'label'   => __('Order by', 'spa-hotel-toolkit'),
                'default' => 'date',
                'options' => [
                    'date'          => __('Date', 'spa-hotel-toolkit'),
                    'title'         => __('Title', 'spa-hotel-toolkit'),
                    'modified'      => __('Modified date', 'spa-hotel-toolkit'),
                    'comment_count' => __('Comment count', 'spa-hotel-toolkit'),
                    'rand'          => __('Random', 'spa-hotel-toolkit'),
                ],
            ]
        );
        // Order
        $this->add_control(
            'post_order',
            [
                'type'    => \Elementor\Controls_Manager::SELECT,
                'label'   => __('Order', 'spa-hotel-toolkit'),
                'default' => 'DESC',
                'options' => [
                    'DESC'        => __('Descending', 'spa-hotel-toolkit'),
                    'ASC'         => __('Ascending', 'spa-hotel-toolkit'),
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
            'post_type'   => 'post',
            'post_status' => 'publish',
        );

        // Display posts in category.
        if (! empty($settings['post_category'])) {
            $args['category_name'] = implode(',', $settings['post_category']);
        }

        if(!empty($settings['post_items'])) {
            $args['post_per_page'] = $settings['post_items'];
        }

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
        
        ?>
        <?php include SHT_HOTEL_TOOLKIT_PATH . 'inc/common/spa-latest-posts-contents.php'; ?>
       
<?php
    }
}
