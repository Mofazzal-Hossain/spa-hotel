<?php
// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\Classes\Helper;

$hotel_meta = get_post_meta($post_id, 'tf_hotels_opt', true);

$rator_sec_title = !empty($hotel_meta['rator-sec-title']) ? $hotel_meta['rator-sec-title'] : esc_html__('SpaRator', 'spa-hotel-toolkit');
$rator_overall_text = !empty($hotel_meta['rator-overall-text']) ? $hotel_meta['rator-overall-text'] : esc_html__('Overall Spa Score', 'spa-hotel-toolkit');
$rator_description = !empty($hotel_meta['rator-description']) ? $hotel_meta['rator-description'] : '';

$tf_settings_base = !empty(Helper::tfopt('r-base')) ? intval(Helper::tfopt('r-base')) : 10;
$original_base = 10; 

$tf_hotel_review = !empty(Helper::tf_data_types(Helper::tfopt('r-hotel')))
    ? Helper::tf_data_types(Helper::tfopt('r-hotel'))
    : [];

$tf_meta_ratings = [];
$total_sum = 0;
$total_count = 0;

foreach ($tf_hotel_review as $field) {

    if (empty($field['r-field-type'])) continue;

    $label = $field['r-field-type'];
    $slug  = sanitize_title($label);
    $meta_key = "rator-{$slug}-score";

    $value = isset($hotel_meta[$meta_key]) ? floatval($hotel_meta[$meta_key]) : 0;

    if ($value !== '' && is_numeric($value)) {

        if ($tf_settings_base != $original_base) {
            $value = ($value / $original_base) * $tf_settings_base;
        }

        $tf_meta_ratings[$label] = [
            'value' => $value,
            'icon'  => $field['r-field-icon'] ?? '',
        ];

        $total_sum += $value;
        $total_count++;
    }
}

// Calculate final avg
$total_rating = $total_count ? ($total_sum / $total_count) : 0;

?>

<div class="tf-rator-progress spa-single-section">

    <h4 class="tf-section-title"><?php echo esc_html($rator_sec_title); ?></h4>

    <div class="tf-rator-progress-inner">

        <!-- Overall Rating -->
        <div class="tf-rator-average-progress">
            <div class="average">
                <div class="average-text"><?php echo esc_html($rator_overall_text); ?></div>

                <div class="rating">
                    <?php echo esc_html(number_format($total_rating, 1)); ?>
                    <?php echo esc_html__('out of', 'spa-hotel-toolkit'); ?>
                    <span><?php echo esc_html($tf_settings_base); ?></span>
                </div>
            </div>

            <div class="tf-rator-average-inner">
                <div class="tf-rator-average-bar">
                    <div class="tf-rator-average-bar-fill"
                        style="width: <?php echo esc_attr(($total_rating / $tf_settings_base) * 100); ?>%;">
                    </div>
                </div>

                <!-- Dynamic Scale -->
                <div class="tf-rator-progress-scale">
                    <?php $base = (int) $tf_settings_base;
                    $step = ($base == 10) ? 2 : 1;
                    for ($i = 0; $i <= $base; $i += $step) {
                        echo '<span>' . esc_html($i) . '</span>';
                    } ?>
                </div>
            </div>
        </div>

        <!-- Description -->
        <?php if (!empty($rator_description)) : ?>
            <div class="tf-rator-desc">
                <?php echo esc_html($rator_description); ?>
            </div>
        <?php endif; ?>

        <!-- Fields -->
        <?php if (!empty($tf_meta_ratings)) : ?>
            <div class="tf-rator-progress-fields">

                <?php foreach ($tf_meta_ratings as $label => $rating_data) :
                    $value = $rating_data['value'];
                    $icon_url = $rating_data['icon'];
                ?>
                    <div class="tf-progress-item">

                        <div class="icon">
                            <?php if (!empty($icon_url)) : ?>
                                <img src="<?php echo esc_url($icon_url); ?>"
                                    alt="<?php echo esc_attr($label); ?>">
                            <?php endif; ?>
                        </div>

                        <div class="tf-review-feature">
                            <p class="feature-label"><?php echo esc_html($label); ?></p>

                            <div class="tf-progress-bar">
                                <span class="percent-progress"
                                    style="width: <?php echo esc_attr(($value / $tf_settings_base) * 100); ?>%;">
                                </span>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>

    </div>
</div>