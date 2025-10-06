<?php

namespace Spa_Hotel_Toolkit\Widgets;

defined('ABSPATH') || exit;

use Tourfic\Classes\Helper;

class Sht_Rating_Filter_Widget extends \WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'sht_rating_filter_widget',
            __('Hotel Rating Filter', 'spa-hotel-toolkit'),
            array('description' => __('Displays selectable rating filters with stars or numbers.', 'spa-hotel-toolkit'))
        );
    }

    public function widget($args, $instance)
    {
        $posttype = isset($_GET['type']) ? sanitize_text_field(wp_unslash($_GET['type'])) : get_post_type();

        // Show only for hotels
        if (is_admin() || $posttype === 'tf_hotel') {
            echo $args['before_widget'];

            $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Spa Hotel Facilities:', 'spa-hotel-toolkit');

            echo wp_kses_post($args['before_title'] . esc_html($title) . $args['after_title']);

            // Get rating base
            $tf_settings_base = !empty(Helper::tfopt('r-base')) ? Helper::tfopt('r-base') : 10;
            $ratings = [];

            // Generate ratings from base down to 1 (or 0)
            for ($i = $tf_settings_base; $i >= ($tf_settings_base == 5 ? 1 : 1); $i--) {
                $ratings[] = $i;
            }

            echo '<div class="sht-filter spa-rating-filter"><ul>';
            foreach ($ratings as $rating) {
                echo '<li class="sht-filter-item">';
                echo '<label>';
                echo '<input type="checkbox" name="ratings[]" value="' . esc_attr($rating) . '">  <span class="sht-checkmark"></span>';

                // Show stars for all bases
                echo $this->get_stars($rating, $tf_settings_base);

                echo '</label>';
                echo '</li>';
            }

            // “No rating” option
            echo '<li class="sht-filter-item"><label><input type="checkbox" name="ratings[]" value="no-rating">  <span class="sht-checkmark"></span>' . esc_html__('No rating', 'spa-hotel-toolkit') . '</label></li>';
            echo '</ul></div>';

            echo $args['after_widget'];
        }
    }

    private function get_stars($count)
    {
        $html = '';
        for ($i = 1; $i <= $count; $i++) {
            $html .= '<i class="fas fa-star"></i>'; // solid star
        }
        return '<span class="rating-stars">' . $html . '</span>';
    }

    public function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Hotel Rating', 'spa-hotel-toolkit'); ?>
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

    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['title'] = !empty($new_instance['title']) ? wp_strip_all_tags($new_instance['title']) : '';
        return $instance;
    }


}
