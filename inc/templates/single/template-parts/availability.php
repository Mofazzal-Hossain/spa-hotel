<?php
// Don't load directly
defined('ABSPATH') || exit;
$hotel_meta = get_post_meta($post_id, 'tf_hotels_opt', true);
$avilability_sec_title = ! empty($hotel_meta['availability-sec-title']) ? $hotel_meta['availability-sec-title'] : '';
$avilability_peak_session = ! empty($hotel_meta['availability-peak-session']) ? $hotel_meta['availability-peak-session'] : '';
$avilability_rates_info = ! empty($hotel_meta['availability-rates-info']) ? $hotel_meta['availability-rates-info'] : '';
$avilability_booking = ! empty($hotel_meta['availability-booking']) ? $hotel_meta['availability-booking'] : [];
?>

<div class="tf-availability-wrapper spa-single-section">
    <?php if (!empty($avilability_sec_title)): ?>
        <h4 class="tf-section-title"><?php echo esc_html($avilability_sec_title); ?></h4>
    <?php endif; ?>
    <div class="tf-availability-inner">
        <div class="tf-availability-info">
            <?php if (!empty($avilability_peak_session)): ?>
                <div class="tf-peak-session"><?php echo esc_html($avilability_peak_session); ?></div>
            <?php endif; ?>
            <?php if (!empty($avilability_rates_info)): ?>
                <div class="tf-rates-info tf-flex">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="none">
                            <path d="M10.0001 13.8327V10.4993M10.0001 7.16602H10.0084M18.3334 10.4993C18.3334 15.1017 14.6025 18.8327 10.0001 18.8327C5.39771 18.8327 1.66675 15.1017 1.66675 10.4993C1.66675 5.89698 5.39771 2.16602 10.0001 2.16602C14.6025 2.16602 18.3334 5.89698 18.3334 10.4993Z" stroke="#E67E22" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <span><?php echo esc_html($avilability_rates_info); ?></span>
                </div>
            <?php endif; ?>
        </div>
        <?php if (!empty($avilability_booking) && is_array($avilability_booking)): ?>
            <div class="tf-availability-platform">
                <ul>
                    <?php foreach ($avilability_booking as $booking): 
                        $booking_url = ! empty($booking['availability-booking-url']) ? $booking['availability-booking-url'] : '';
                        $platform_logo = ! empty($booking['availability-platform-logo']) ? $booking['availability-platform-logo'] : '';
                        ?>
                        <li>
                            <img src="<?php echo esc_url($platform_logo); ?>" alt="Platform Logo">
                            <?php if(!empty($booking_url)): ?>
                                <a href="<?php echo esc_url($booking_url); ?>" class="sht-btn sht-btn-fill" target="_blank" rel="nofollow noopener">
                                    <?php echo esc_html__('Check Availabiltiy', 'spa-hotel-toolkit'); ?>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>