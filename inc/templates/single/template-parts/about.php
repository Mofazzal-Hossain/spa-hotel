<?php

// Don't load directly
defined('ABSPATH') || exit;
$hotel_meta = get_post_meta($post_id, 'tf_hotels_opt', true);
$rating_sec_title = ! empty($hotel_meta['rating-sec-title']) ? $hotel_meta['rating-sec-title'] : '';

// booking.com rating
$rating_booking_feedback = ! empty($hotel_meta['rating-booking-feedback']) ? $hotel_meta['rating-booking-feedback'] : '';
$rating_booking_score = ! empty($hotel_meta['rating-booking-score']) ? $hotel_meta['rating-booking-score'] : 0;
$rating_booking_desc = ! empty($hotel_meta['rating-booking-desc']) ? $hotel_meta['rating-booking-desc'] : '';

// google rating
$rating_google_score = ! empty($hotel_meta['rating-google-score']) ? $hotel_meta['rating-google-score'] : 0;
$rating_google_desc = ! empty($hotel_meta['rating-google-desc']) ? $hotel_meta['rating-google-desc'] : '';

// tripadvisor rating
$rating_tripadvisor_score = ! empty($hotel_meta['rating-tripadvisor-score']) ? $hotel_meta['rating-tripadvisor-score'] : 0;
$rating_tripadvisor_desc = ! empty($hotel_meta['rating-tripadvisor-desc']) ? $hotel_meta['rating-tripadvisor-desc'] : '';


?>

<div class="tf-single-description spa-single-section">
    <h4 class="tf-section-title"><?php echo esc_html($rating_sec_title); ?></h4>
    <div class="tf-short-description">
        <?php
        if (strlen(get_the_content()) > 450) {
            echo wp_kses_post(wp_strip_all_tags(\Tourfic\Classes\Helper::tourfic_character_limit_callback(get_the_content(), 450))) . '<span class="tf-see-description">See more</span>';
        } else {
            the_content();
        }
        ?>
    </div>
    <div class="tf-full-description">
        <?php
        the_content();
        echo '<span class="tf-see-less-description">See less</span>';
        ?>
    </div>
    <div class="tf-hotel-place-review">
        <ul>
            <li>
                <div class="rating">
                    <span><?php echo esc_html($rating_booking_score); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="16" viewBox="0 0 20 16" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00146484 0.349609H19.979V15.4995H0.00147948L0.00146484 0.349609Z" fill="#0C3B7C" />
                    </svg>
                </div>
                <div class="review-info">
                    <?php if(!empty($rating_booking_feedback)): ?>
                        <h6><?php echo esc_html($rating_booking_feedback); ?></h6>    
                    <?php endif; ?>
                    <?php if(!empty($rating_booking_desc)): ?>
                        <p><?php echo esc_html($rating_booking_desc); ?></p>
                    <?php endif; ?>
                </div>
            </li>
            <li>
                <img src="<?php echo esc_url(SHT_HOTEL_TOOLKIT_ASSETS . 'images/google-maps.png'); ?>" alt="Google Map">
                <div class="review-info">
                    <h6>
                        <div class="rating-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20" fill="none">
                                <path d="M10.271 1.91176C10.3076 1.83798 10.364 1.77587 10.4339 1.73245C10.5039 1.68903 10.5845 1.66602 10.6669 1.66602C10.7492 1.66602 10.8299 1.68903 10.8998 1.73245C10.9698 1.77587 11.0262 1.83798 11.0627 1.91176L12.9877 5.81093C13.1145 6.06757 13.3017 6.2896 13.5332 6.45797C13.7647 6.62634 14.0336 6.73602 14.3169 6.77759L18.6219 7.40759C18.7034 7.41941 18.7801 7.45382 18.8431 7.50693C18.9061 7.56003 18.9531 7.62972 18.9785 7.7081C19.004 7.78648 19.0071 7.87043 18.9874 7.95046C18.9676 8.03048 18.9259 8.10339 18.8669 8.16093L15.7535 11.1926C15.5482 11.3927 15.3946 11.6397 15.3059 11.9123C15.2172 12.1849 15.1961 12.475 15.2444 12.7576L15.9794 17.0409C15.9938 17.1225 15.985 17.2064 15.9539 17.2832C15.9229 17.3599 15.871 17.4264 15.804 17.4751C15.737 17.5237 15.6577 17.5526 15.5751 17.5583C15.4925 17.5641 15.4099 17.5465 15.3369 17.5076L11.4885 15.4843C11.235 15.3511 10.9528 15.2816 10.6664 15.2816C10.38 15.2816 10.0979 15.3511 9.84436 15.4843L5.99686 17.5076C5.92381 17.5463 5.84136 17.5637 5.75891 17.5578C5.67645 17.5519 5.5973 17.5231 5.53044 17.4744C5.46359 17.4258 5.41173 17.3594 5.38074 17.2828C5.34976 17.2061 5.34091 17.1223 5.3552 17.0409L6.08936 12.7584C6.13786 12.4757 6.11685 12.1854 6.02815 11.9126C5.93944 11.6398 5.7857 11.3927 5.5802 11.1926L2.46686 8.16176C2.40736 8.10429 2.36519 8.03126 2.34517 7.95099C2.32514 7.87072 2.32806 7.78644 2.3536 7.70775C2.37913 7.62906 2.42625 7.55913 2.48959 7.50591C2.55294 7.4527 2.62995 7.41834 2.71186 7.40676L7.01603 6.77759C7.29958 6.73634 7.56886 6.62681 7.80068 6.45842C8.03251 6.29002 8.21995 6.06782 8.34686 5.81093L10.271 1.91176Z" fill="#E67E22" stroke="#E67E22" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <span><?php echo esc_html($rating_google_score); ?></span>
                    </h6>
                    <?php if(!empty($rating_google_desc)): ?>
                        <p><?php echo esc_html($rating_google_desc); ?></p>
                    <?php endif; ?>
                </div>
            </li>
            <li>

                <img src="<?php echo esc_url(SHT_HOTEL_TOOLKIT_ASSETS . 'images/tripadvisior.png'); ?>" alt="Trip Advisor">
                <div class="review-info">
                    <h6>
                        <div class="rating-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20" fill="none">
                                <path d="M10.271 1.91176C10.3076 1.83798 10.364 1.77587 10.4339 1.73245C10.5039 1.68903 10.5845 1.66602 10.6669 1.66602C10.7492 1.66602 10.8299 1.68903 10.8998 1.73245C10.9698 1.77587 11.0262 1.83798 11.0627 1.91176L12.9877 5.81093C13.1145 6.06757 13.3017 6.2896 13.5332 6.45797C13.7647 6.62634 14.0336 6.73602 14.3169 6.77759L18.6219 7.40759C18.7034 7.41941 18.7801 7.45382 18.8431 7.50693C18.9061 7.56003 18.9531 7.62972 18.9785 7.7081C19.004 7.78648 19.0071 7.87043 18.9874 7.95046C18.9676 8.03048 18.9259 8.10339 18.8669 8.16093L15.7535 11.1926C15.5482 11.3927 15.3946 11.6397 15.3059 11.9123C15.2172 12.1849 15.1961 12.475 15.2444 12.7576L15.9794 17.0409C15.9938 17.1225 15.985 17.2064 15.9539 17.2832C15.9229 17.3599 15.871 17.4264 15.804 17.4751C15.737 17.5237 15.6577 17.5526 15.5751 17.5583C15.4925 17.5641 15.4099 17.5465 15.3369 17.5076L11.4885 15.4843C11.235 15.3511 10.9528 15.2816 10.6664 15.2816C10.38 15.2816 10.0979 15.3511 9.84436 15.4843L5.99686 17.5076C5.92381 17.5463 5.84136 17.5637 5.75891 17.5578C5.67645 17.5519 5.5973 17.5231 5.53044 17.4744C5.46359 17.4258 5.41173 17.3594 5.38074 17.2828C5.34976 17.2061 5.34091 17.1223 5.3552 17.0409L6.08936 12.7584C6.13786 12.4757 6.11685 12.1854 6.02815 11.9126C5.93944 11.6398 5.7857 11.3927 5.5802 11.1926L2.46686 8.16176C2.40736 8.10429 2.36519 8.03126 2.34517 7.95099C2.32514 7.87072 2.32806 7.78644 2.3536 7.70775C2.37913 7.62906 2.42625 7.55913 2.48959 7.50591C2.55294 7.4527 2.62995 7.41834 2.71186 7.40676L7.01603 6.77759C7.29958 6.73634 7.56886 6.62681 7.80068 6.45842C8.03251 6.29002 8.21995 6.06782 8.34686 5.81093L10.271 1.91176Z" fill="#E67E22" stroke="#E67E22" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <span><?php echo esc_html($rating_tripadvisor_score); ?></span>
                    </h6>
                    <?php if(!empty($rating_tripadvisor_desc)): ?>
                        <p><?php echo esc_html($rating_tripadvisor_desc); ?></p>
                    <?php endif; ?>
                </div>
            </li>
        </ul>
    </div>
</div>