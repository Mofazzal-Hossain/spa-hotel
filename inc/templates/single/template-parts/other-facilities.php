<?php
// Don't load directly
defined('ABSPATH') || exit;

$hotel_meta = get_post_meta($post_id, 'tf_hotels_opt', true);
$hotel_other_facilities = ! empty($hotel_meta['hotel-other-facilities']) ? $hotel_meta['hotel-other-facilities'] : '';
?>

<!-- Start features -->
<?php if (!empty($hotel_other_facilities)) { ?>
    <div class="tf-hotel-facilities-section spa-single-section tf-other-facilities">
        <h4 class="tf-title tf-section-title">
            <?php echo !empty($hotel_meta['other-facilities-title']) ? esc_html($hotel_meta['other-facilities-title']) : ''; ?>
        </h4>
        <div class="tf-hotel-facilities-content-area">
            <div class="hotel-facility-item">
                <ul>
                    <?php
                    foreach ($hotel_other_facilities as $facility) :
                        $features_details = !empty($facility['facilities-feature']) ? $facility['facilities-feature'] : '';
                        $feature_icon = !empty($facility['other-facilities-icon']) ? $facility['other-facilities-icon'] : 'fa fa-check';
                        if(!empty($features_details)) :
                    ?>
                        <li>
                            <span class="feature-icon"><i class="<?php echo esc_attr($feature_icon); ?>"></i></span>
                            <span><?php echo esc_html($features_details); ?></span>
                        </li>
                    <?php endif; endforeach; ?>
                </ul>
            </div>

        </div>

    </div>
<?php } ?>
<!-- End features -->