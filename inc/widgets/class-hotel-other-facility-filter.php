<?php
namespace Spa_Hotel_Toolkit\Widgets;

defined('ABSPATH') || exit;

class Sht_Hotel_Other_Facility_Filter extends \WP_Widget {

    public function __construct() {
        parent::__construct(
            'sht_hotel_other_facility_filter',
            esc_html__('Hotels Filter by Other Facilities', 'spa-hotel-toolkit'),
            ['description' => esc_html__('Filter hotels by custom other facilities (from repeater field).', 'spa-hotel-toolkit')]
        );
    }

    /**
     * Frontend widget output
     */
    public function widget($args, $instance) {
        $posttype = isset($_GET['type']) ? sanitize_text_field(wp_unslash($_GET['type'])) : get_post_type();

        // Only show for hotels
        if (is_admin() || $posttype == 'tf_hotel') {
            extract($args);

            echo wp_kses_post($before_widget);
            $title = isset($instance['title']) ? $instance['title'] : esc_html__('Other Hotel Facilites:', 'spa-hotel-toolkit');
            echo wp_kses_post($before_title . esc_html($title) . $after_title);

            global $wpdb;
            $meta_key = 'tf_hotels_opt';
            $results = $wpdb->get_col("
                SELECT meta_value FROM {$wpdb->postmeta}
                WHERE meta_key = '{$meta_key}'
            ");

            $facilities = [];
            foreach ($results as $meta_value) {
                $hotel_meta = maybe_unserialize($meta_value);
                if (!empty($hotel_meta['hotel-other-facilities'])) {
                    foreach ($hotel_meta['hotel-other-facilities'] as $facility) {
                        if (!empty($facility['facilities-feature'])) {
                            $facilities[] = trim($facility['facilities-feature']);
                        }
                    }
                }
            }

            // Remove duplicates and sort alphabetically
            $facilities = array_unique($facilities);
            sort($facilities);

            // Selected values from URL
            $selected = isset($_GET['facilities']) ? (array) wp_unslash($_GET['facilities']) : [];

            if (!empty($facilities)) {
                echo "<div class='sht-filter'><ul>";
                foreach ($facilities as $feature_name) {
                    $checked = in_array($feature_name, $selected) ? 'checked' : '';
                    echo '<li class="sht-filter-item">
                        <label>
                            <input type="checkbox" name="facilities[]" value="' . esc_attr($feature_name) . '" ' . $checked . '>
                            <span class="sht-checkmark"></span> ' . esc_html($feature_name) . '
                        </label>
                    </li>';
                }
                echo "</ul>";
                echo '<a href="#" class="see-more btn-link">'
                    . esc_html__('See more', 'spa-hotel-toolkit') .
                    '<span class="fa fa-angle-down"></span></a>';
                echo '<a href="#" class="see-less btn-link">'
                    . esc_html__('See less', 'spa-hotel-toolkit') .
                    '<span class="fa fa-angle-up"></span></a>';

                echo "</div>";
            } else {
                echo '<p>' . esc_html__('No facilities found.', 'spa-hotel-toolkit') . '</p>';
            }

            echo wp_kses_post($after_widget);
        }
    }

    /**
     * Backend widget form
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Other Hotel Facilites:', 'spa-hotel-toolkit'); ?>
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
     * Save widget settings
     */
    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = !empty($new_instance['title']) ? wp_strip_all_tags($new_instance['title']) : '';
        return $instance;
    }
}
