<?php

namespace Spa_Hotel_Toolkit\Widgets;

defined('ABSPATH') || exit;

use \Tourfic\Classes\Helper;

class Sht_Hotel_Score_Filter extends \WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'sht-hotel-score-filter',
            esc_html__('SpaRator Score Widget', 'spa-hotel-toolkit'),
            ['description' => esc_html__('Filter hotels by SpaRator rating range (multi-select).', 'spa-hotel-toolkit')]
        );
    }

    public function widget($args, $instance)
    {
        extract($args);

        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Spa Rator Score:', 'spa-hotel-toolkit');

        echo wp_kses_post($before_widget);
        echo wp_kses_post($before_title . esc_html($title) . $after_title);

        $counts = $this->get_spa_rator_property_counts();

        // Current rating base (5 or 10)
        $tf_settings_base = !empty(Helper::tfopt('r-base'))
            ? intval(Helper::tfopt('r-base'))
            : 10;

        /**
         * Generate dynamic labels based on rating base,
         * but keep original values 9, 8, 7, 6.
         */
        $ratings = [
            [
                'key'   => 'exceptional',
                'value' => 9,
                'label' => round($tf_settings_base * 0.90, 1) . '+ Exceptional',
                'count' => $counts['exceptional']
            ],
            [
                'key'   => 'outstanding',
                'value' => 8,
                'label' => round($tf_settings_base * 0.80, 1) . '+ Outstanding',
                'count' => $counts['outstanding']
            ],
            [
                'key'   => 'excellent',
                'value' => 7,
                'label' => round($tf_settings_base * 0.70, 1) . '+ Excellent',
                'count' => $counts['excellent']
            ],
            [
                'key'   => 'very_good',
                'value' => 6,
                'label' => round($tf_settings_base * 0.60, 1) . '+ Very Good',
                'count' => $counts['very_good']
            ],
        ];

        // Active filters
        $active_filters = [];
        if (!empty($_GET['score'])) {
            $active_filters = array_map('sanitize_text_field', (array) $_GET['score']);
        }

        echo '<div class="sht-filter"><ul>';

        foreach ($ratings as $rating) {
            echo '<li class="sht-filter-item spa-rator-item">';
            echo '<label class="sht-rator-badge ' . esc_attr($rating['key']) . '">';

            echo '<input type="checkbox" name="score[]" value="' . esc_attr($rating['value']) . '" '
                . checked(in_array($rating['key'], $active_filters, true), true, false)
                . ' hidden>';

            echo esc_html($rating['label']) . ' (' . intval($rating['count']) . ' properties)';
            echo '</label></li>';
        }

        echo '</ul></div>';
        echo wp_kses_post($after_widget);
    }



    public function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Spa Rator Score', 'spa-hotel-toolkit'); ?>
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

    private function get_spa_rator_property_counts()
    {
        $cache_key = 'spa_rator_counts';
        $cached = get_transient($cache_key);
        if ($cached !== false) {
            return $cached;
        }

        $args = [
            'post_type'      => 'tf_hotel',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        ];
        $hotels = get_posts($args);

        $ranges = [
            'exceptional' => 0,
            'outstanding' => 0,
            'excellent'   => 0,
            'very_good'   => 0,
            'good'        => 0,
        ];

        $tf_hotel_review = Helper::tf_data_types(Helper::tfopt('r-hotel')) ?: [];
        $tf_settings_base = !empty(Helper::tfopt('r-base')) ? intval(Helper::tfopt('r-base')) : 10;

        $original_base = 10;

        foreach ($hotels as $post_id) {

            $hotel_meta = get_post_meta($post_id, 'tf_hotels_opt', true);

            $total_sum = 0;
            $total_count = 0;

            foreach ($tf_hotel_review as $field) {

                if (empty($field['r-field-type'])) continue;

                $label = $field['r-field-type'];
                $slug  = sanitize_title($label);
                $meta_key = "rator-{$slug}-score";

                $value = isset($hotel_meta[$meta_key]) ? floatval($hotel_meta[$meta_key]) : 0;

                if ($value > 0) {
                    if ($tf_settings_base != $original_base) {
                        $value = ($value / $original_base) * $tf_settings_base;
                    }

                    $total_sum += $value;
                    $total_count++;
                }
            }

            $rating = ($total_count > 0) ? ($total_sum / $total_count) : 0;
            $percent = $tf_settings_base ? ($rating / $tf_settings_base) * 100 : 0;

            if ($percent >= 90) {
                $ranges['exceptional']++;
            } elseif ($percent >= 80) {
                $ranges['outstanding']++;
            } elseif ($percent >= 70) {
                $ranges['excellent']++;
            } elseif ($percent >= 60) {
                $ranges['very_good']++;
            } elseif ($percent > 0) {
                $ranges['good']++;
            }
        }

        set_transient($cache_key, $ranges, 6 * HOUR_IN_SECONDS);

        return $ranges;
    }
}
