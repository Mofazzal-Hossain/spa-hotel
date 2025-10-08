<?php

namespace Spa_Hotel_Toolkit\Widgets;

defined('ABSPATH') || exit;

/**
 * Simplified Hotel Feature Filter Widget
 */
class Sht_Hotel_Feature_Filter extends \WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'sht_hotel_feature_filter',
            esc_html__('Hotels Filters by Feature', 'spa-hotel-toolkit'),
            array('description' => esc_html__('Filter hotels by feature (auto load all features)', 'spa-hotel-toolkit'))
        );
    }

    /**
     * Frontend Output
     */
    public function widget($args, $instance)
    {
        $posttype = isset($_GET['type']) ? sanitize_text_field(wp_unslash($_GET['type'])) : get_post_type();

        // Show only for hotels
        if (is_admin() || $posttype === 'tf_hotel') {
            extract($args);
            $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Spa Hotel Facitilies: ', 'spa-hotel-toolkit');

            echo wp_kses_post($before_widget);
            echo wp_kses_post($before_title . esc_html($title) . $after_title);

            $terms = get_terms(array(
                'taxonomy'   => 'hotel_feature',
                'hide_empty' => false,
            ));

            // Get selected features from URL
            $selected = isset($_GET['features']) ? (array) wp_unslash($_GET['features']) : [];

            if (!empty($terms) && !is_wp_error($terms)) {
                echo "<div class='sht-filter'><ul>";
                foreach ($terms as $term) {
                    $id   = $term->term_id;
                    $slug = $term->slug;
                    $name = $term->name;
                    $checked = in_array($slug, $selected, true) ? 'checked' : '';
                    echo '<li class="sht-filter-item">
                        <label>
                            <input type="checkbox" name="sht_features[]" value="' . esc_attr($id) . '" ' . esc_attr($checked) . ' />
                            <span class="sht-checkmark"></span> ' . esc_html($name) . '
                        </label>
                    </li>';
                }
                echo "</ul>";

                echo '<a href="#" class="see-more btn-link">' . esc_html__('See more', 'spa-hotel-toolkit') . '<span class="fa fa-angle-down"></span></a>';
                echo '<a href="#" class="see-less btn-link">' . esc_html__('See less', 'spa-hotel-toolkit') . '<span class="fa fa-angle-up"></span></a>';
                echo "</div>";
            } else {
                echo '<p>' . esc_html__('No features found.', 'spa-hotel-toolkit') . '</p>';
            }

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
