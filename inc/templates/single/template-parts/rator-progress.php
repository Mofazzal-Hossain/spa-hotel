<?php
// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\App\TF_Review;
use \Tourfic\Classes\Helper;

$hotel_meta = get_post_meta($post_id, 'tf_hotels_opt', true);
$rator_sec_title = ! empty($hotel_meta['rator-sec-title']) ? $hotel_meta['rator-sec-title'] : esc_html__('SpaRator', 'spa-hotel-toolkit');
$rator_overall_text = ! empty($hotel_meta['rator-overall-text']) ? $hotel_meta['rator-overall-text'] : esc_html__('Overall Spa Score', 'spa-hotel-toolkit');
$rator_description = ! empty($hotel_meta['rator-description']) ? $hotel_meta['rator-description'] : '';

/**
 * Review query
 */
$args = array(
    'post_id' => $post_id,
    'status'  => 'approve',
    'type'    => 'comment',
);
$comments_query = new WP_Comment_Query($args);
$comments = $comments_query->comments;
$tf_rating_progress_bar = '';
$tf_overall_rate        = [];
$tf_settings_base = ! empty(Helper::tfopt('r-base')) ? Helper::tfopt('r-base') : 10;
$tf_hotel_review     = ! empty(Helper::tf_data_types(Helper::tfopt('r-hotel'))) ? Helper::tf_data_types(Helper::tfopt('r-hotel')) : [];

TF_Review::tf_calculate_comments_rating($comments, $tf_overall_rate, $total_rating);
TF_Review::tf_get_review_fields($fields);
?>

<div class="tf-rator-progress spa-single-section">
    <h4 class="tf-section-title"><?php echo esc_html($rator_sec_title); ?></h4>
    <div class="tf-rator-progress-inner">
        <div class="tf-rator-average-progress">
            <div class="average">
                <div class="average-text"><?php echo esc_html($rator_overall_text); ?></div>
                <div class="rating">
                    <?php echo esc_html(sprintf('%.1f', $total_rating)); ?>
                    <?php echo esc_html__('out of', 'spa-hotel-toolkit'); ?>
                    <span><?php echo esc_html($tf_settings_base); ?></span>
                </div>
            </div>
            <div class="tf-rator-average-inner">
                <div class="tf-rator-average-bar">
                    <div class="tf-rator-average-bar-fill" style="width: <?php echo intval(($total_rating / $tf_settings_base) * 100); ?>%">
                    </div>
                </div>
                <div class="tf-rator-progress-scale">
                    <?php
                    $base = !empty($tf_settings_base) ? (int) $tf_settings_base : 5;
                    $step = ($base == 10) ? 2 : 1;

                    for ($i = 0; $i <= $base; $i += $step) {
                        echo '<span>' . esc_html($i) . '</span>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php if (!empty($rator_description)): ?>
            <div class="tf-rator-desc"><?php echo esc_html($rator_description); ?></div>
        <?php endif; ?>
        <?php if ($tf_overall_rate) : ?>
            <div class="tf-rator-progress-fields">
                <?php foreach ($tf_overall_rate as $key => $value) {
                    if (empty($value) || ! in_array($key, $fields)) {
                        continue;
                    }
                    $value = TF_Review::Tf_average_ratings($value);
                    $icon_url = '';
                    if (!empty($tf_hotel_review)) {
                        foreach ($tf_hotel_review as $review_field) {
                            if (!empty($review_field['r-field-type']) && strtolower($review_field['r-field-type']) === $key) {
                                $icon_url = !empty($review_field['r-field-icon']) ? $review_field['r-field-icon'] : '';
                                break;
                            }
                        }
                    }
                ?>
                    <div class="tf-progress-item">
                        <div class="icon">
                            <?php if ($icon_url): ?>
                                <img src="<?php echo esc_url($icon_url); ?>" alt="<?php echo esc_attr($key); ?>" />
                            <?php endif; ?>
                        </div>
                        <div class="tf-review-feature">
                            <p class="feature-label"><?php echo esc_html($key); ?></p>
                            <div class="tf-progress-bar">
                                <span class="percent-progress" style="width: <?php echo esc_html(TF_Review::tf_average_rating_percent($value, Helper::tfopt('r-base'))); ?>%"></span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php endif; ?>
    </div>
</div>