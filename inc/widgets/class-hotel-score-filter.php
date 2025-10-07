<?php

namespace Spa_Hotel_Toolkit\Widgets;

defined('ABSPATH') || exit;

use \Tourfic\App\TF_Review;

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

        $ratings = [
            ['label' => '9.0+ Exceptional', 'key' => 'exceptional', 'value' => 9, 'count' => $counts['exceptional']],
            ['label' => '8.0+ Outstanding', 'key' => 'outstanding', 'value' => 8, 'count' => $counts['outstanding']],
            ['label' => '7.0+ Excellent',   'key' => 'excellent',  'value' => 7, 'count' => $counts['excellent']],
            ['label' => '6.0+ Very Good',   'key' => 'very_good', 'value' => 6,  'count' => $counts['very_good']],
        ];

        $active_filters = [];
        if (!empty($_GET['score'])) {
            $active_filters = array_map('sanitize_text_field', (array) $_GET['score']);
        }

        echo '<div class="sht-filter">';
        echo '<ul>';
        foreach ($ratings as $rating) {
            echo '<li class="sht-filter-item spa-rator-item">';
            echo '<label class="sht-rator-badge ' . esc_attr($rating['key']) . '">';
            echo '<input type="checkbox" name="score[]" value="' . esc_attr($rating['value']) . '" ' . checked(in_array($rating['key'], $active_filters, true), true, false) . ' hidden>';
            echo esc_html($rating['label']) . ' (' . intval($rating['count']) . ' properties)';
            echo '</label>';
            echo '</li>';
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

        if (!empty($hotels)) {
            foreach ($hotels as $post_id) {
                $args = [
                    'post_id' => $post_id,
                    'status'  => 'approve',
                    'type'    => 'comment',
                ];
                $comments_query = new \WP_Comment_Query($args);
                $comments = $comments_query->comments;

                $tf_overall_rate = [];
                $total_rating = 0;
                TF_Review::tf_calculate_comments_rating($comments, $tf_overall_rate, $total_rating);
                $rating = floatval($total_rating);

                if ($rating >= 9.0 && $rating <= 10.0) {
                    $ranges['exceptional']++;
                } elseif ($rating >= 8.0 && $rating <= 8.9) {
                    $ranges['outstanding']++;
                } elseif ($rating >= 7.0 && $rating <= 7.9) {
                    $ranges['excellent']++;
                } elseif ($rating >= 6.0 && $rating <= 6.9) {
                    $ranges['very_good']++;
                } elseif ($rating > 0) {
                    $ranges['good']++;
                }
            }
        }

        set_transient($cache_key, $ranges, 6 * HOUR_IN_SECONDS);
        return $ranges;
    }
}
