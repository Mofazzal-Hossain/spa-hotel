<?php
// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\Classes\Helper;


?>

<!-- Start features -->
<?php if (!empty($hotel_facilities)) { ?>
    <div class="tf-hotel-facilities-section spa-single-section">
        <h4 class="tf-title tf-section-title">
            <?php echo !empty($meta['facilities-section-title']) ? esc_html($meta['facilities-section-title']) : ''; ?>
        </h4>
        <div class="tf-hotel-facilities-content-area">
            <?php
            $facilities_list = [];
            if (!empty($meta['hotel-facilities'])) {
                foreach ($meta['hotel-facilities'] as $facility) {
                    $facilities_list[$facility['facilities-category']] = $facility['facilities-category'];
                }
            }

            if (!empty($facilities_list)) {
                foreach ($facilities_list as $key => $single_feature) {
                    $f_icon_single  = ! empty($hotel_facilities_categories[$key]['hotel_facilities_cat_icon']) ? esc_attr($hotel_facilities_categories[$key]['hotel_facilities_cat_icon']) : '';
            ?>
                    <div class="hotel-facility-item">
                        <?php if (! empty($hotel_facilities_categories[$key]['hotel_facilities_cat_name'])) : ?>
                            <div class="hotel-single-facility-title">
                                <?php if(! empty($f_icon_single)): ?>
                                    <div class="cat-icon">
                                        <i class="<?php echo esc_attr($f_icon_single); ?>"></i>    
                                    </div>
                                <?php endif; ?>
                                <?php echo esc_html($hotel_facilities_categories[$key]['hotel_facilities_cat_name']); ?>
                            </div>
                        <?php endif; ?>
                        <ul>
                            <?php
                            foreach ($hotel_facilities as $facility) :
                                if ($facility['facilities-category'] == $key) {
                                    $features_details = !empty($facility['facilities-feature']) ? get_term($facility['facilities-feature']) : '';
                                    $feature_meta = get_term_meta($facility['facilities-feature'], 'tf_hotel_feature', true);
                                    $feature_icon = '<i class="fa fa-check"></i>';
                                    if (!empty($feature_meta)) {
                                        $f_icon_type  = !empty($feature_meta['icon-type']) ? $feature_meta['icon-type'] : '';

                                        if ($f_icon_type === 'fa' && !empty($feature_meta['icon-fa'])) {
                                            $feature_icon = '<i class="' . esc_attr($feature_meta['icon-fa']) . '"></i>';
                                        } elseif ($f_icon_type === 'c' && !empty($feature_meta['icon-c'])) {
                                            $dimension    = !empty($feature_meta['dimention']) ? intval($feature_meta['dimention']) : 20;
                                            $feature_icon = '<img src="' . esc_url($feature_meta['icon-c']) . '" style="width:' . $dimension . 'px;height:' . $dimension . 'px;" />';
                                        }
                                    }

                                    if (!empty($features_details->name)) {
                            ?>
                                        <li>
                                            <span class="feature-icon"><?php echo wp_kses_post($feature_icon); ?></span>
                                            <span><?php echo esc_html($features_details->name); ?></span>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
            <?php
                }
            }
            ?>
        </div>

    </div>
<?php } ?>
<!-- End features -->