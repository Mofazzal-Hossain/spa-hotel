<?php
defined('ABSPATH') || exit;

$hotel_meta = get_post_meta($post_id, 'tf_hotels_opt', true);

// Get current post's hotel_facilities terms
$hotel_facilities = wp_get_post_terms($post_id, 'hotel_facilities');

if (!empty($hotel_facilities) && !is_wp_error($hotel_facilities)) :
?>
    <div class="tf-hotel-facilities-section spa-single-section tf-other-facilities">
        <h4 class="tf-title tf-section-title">
            <?php echo !empty($hotel_meta['other-facilities-title']) 
                ? esc_html($hotel_meta['other-facilities-title']) 
                : esc_html__('Other Facilities', 'spa-hotel-toolkit'); ?>
        </h4>

        <div class="tf-hotel-facilities-content-area">
            <div class="hotel-facility-item">
                <ul>
                    <?php foreach ($hotel_facilities as $term) : 
                        $feature_meta = get_term_meta($term->term_id, 'tf_hotel_facilities', true);

                        // Determine icon
                        $f_icon_type  = !empty($feature_meta['icon-type']) ? $feature_meta['icon-type'] : '';
                        $feature_icon = '';

                        if (!empty($f_icon_type)) {
                            if ($f_icon_type === 'fa' && !empty($feature_meta['icon-fa'])) {
                                $feature_icon = '<i class="' . esc_attr($feature_meta['icon-fa']) . '"></i>';
                            } elseif ($f_icon_type === 'c' && !empty($feature_meta['icon-c'])) {
                                $dimension = !empty($feature_meta['dimention']) ? intval($feature_meta['dimention']) : 20;
                                $feature_icon = '<img src="' . esc_url($feature_meta['icon-c']) . '" style="width:' . $dimension . 'px;height:' . $dimension . 'px;" />';
                            }
                        } else {
                            $feature_icon = '<i class="fa fa-check"></i>';
                        }
                    ?>
                        <li>
                            <span class="feature-icon"><?php echo wp_kses_post($feature_icon); ?></span>
                            <span><?php echo esc_html($term->name); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>
