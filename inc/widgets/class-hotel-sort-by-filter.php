<?php

namespace Spa_Hotel_Toolkit\Widgets;

defined('ABSPATH') || exit;

/**
 * Simplified Hotel Sort By Filter Widget
 */
class Sht_Hotel_Sort_By_Filter extends \WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'sht_hotel_sort_by_filter',
            esc_html__('Hotels Sort by Filter', 'spa-hotel-toolkit'),
            array('description' => esc_html__('Filter hotels by Sort', 'spa-hotel-toolkit'))
        );
    }

    /**
     * Frontend Output
     */
    public function widget($args, $instance)
    {
        $posttype = get_post_type();

        $is_hotel_location_archive = is_tax('hotel_location');
        $is_tf_search_page = is_page('tf-search');

        // Show only for hotels
        if (!is_admin() && $is_hotel_location_archive && !$is_tf_search_page) {
            extract($args);

            $title = !empty($instance['title'])
                ? $instance['title']
                : esc_html__('Sort Hotels By', 'spa-hotel-toolkit');



            echo wp_kses_post($before_widget);
            echo wp_kses_post($before_title . esc_html($title) . $after_title);
?>

            <div class="sht-sort-filter">
                <label class="sht-radio">
                    <input type="radio"
                        name="sort-by"
                        value="sparator-scores"
                        checked />
                    <span><?php esc_html_e('SpaRator Scores: High to Low', 'spa-hotel-toolkit'); ?></span>
                </label>

                <label class="sht-radio">
                    <input type="radio"
                        name="sort-by"
                        value="hotel-rates" />
                    <span><?php esc_html_e('Hotel Rates: High to Low', 'spa-hotel-toolkit'); ?></span>
                </label>


            </div>

        <?php
            echo wp_kses_post($after_widget);
        }
    }



    /**
     * Backend Form (Title Only)
     */
    public function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Hotel Features', 'spa-hotel-toolkit'); ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Title:', 'spa-hotel-toolkit'); ?>
            </label>
            <input class="widefat"
                id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                type="text"
                value="<?php echo esc_attr($title); ?>" />
        </p>
<?php
    }

    /**
     * Save Settings
     */
    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['title'] = !empty($new_instance['title']) ? wp_strip_all_tags($new_instance['title']) : '';
        return $instance;
    }
}
