<?php

// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\Classes\Helper;

$default_checkin  = gmdate('Y/m/d');
$default_checkout = gmdate('Y/m/d', strtotime('+1 day'));

$checkin_date = ! empty($_GET['check-in-out-date']) ? $_GET['check-in-out-date'] : $default_checkin . ' - ' . $default_checkout;

// Split into check-in and check-out
$datesArr = explode(' - ', $checkin_date);
$checkin  = isset($datesArr[0]) ? $datesArr[0] : $default_checkin;
$checkout = isset($datesArr[1]) ? $datesArr[1] : $default_checkout;

// Convert to individual parts
$checkin_day   = gmdate('d', strtotime($checkin));
$checkin_month = gmdate('M', strtotime($checkin));
$checkin_year  = gmdate('Y', strtotime($checkin));

$checkout_day   = gmdate('d', strtotime($checkout));
$checkout_month = gmdate('M', strtotime($checkout));
$checkout_year  = gmdate('Y', strtotime($checkout));

$place_name = ! empty($_GET['place-name']) ? $_GET['place-name'] : '';
$place = ! empty($_GET['place']) ? $_GET['place'] : '';
$hotel_location_field_required   = ! empty(Helper::tfopt("required_location_hotel_search")) ? Helper::tfopt("required_location_hotel_search") : 0;
?>
<div class="tf-booking-forms-wrapper">
    <form class="tf-archive-search-box-wrapper tf-search__form tf-shortcode-design-4" id="tf_hotel_aval_check" method="post" autocomplete="off">
        <fieldset class="tf-search__form__fieldset">
            <div class="tf-search__form__fieldset__left">
                <div class="tf-search__form__field" id="locationField">
                    <input type="text" name="place-name" <?php echo $hotel_location_field_required != 1 ? '' : 'required'; ?> id="tf-location" class="tf-search__form__input" placeholder="<?php echo esc_attr(apply_filters('tf_location_placeholder', __('Where you wanna stay?', 'spa-hotel-toolkit'))); ?>" value="<?php echo esc_attr($place_name); ?>">
                    <input type="hidden" name="place" id="tf-search-hotel" class="tf-place-input" value="<?php echo esc_attr($place); ?>">
                </div>
            </div>

            <div class="tf-search__form__fieldset__middle">
                <!-- Check-in -->
                <div class="tf-search__form__group tf-checkin-group">
                    <div class="tf_check_inout_dates">
                        <div class="tf-search__form__field">
                            <div class="tf_checkin_dates tf-flex tf-flex-align-center">
                                <span class="date field--title"><?php echo esc_html($checkin_day); ?></span>
                                <div class="tf-search__form__field__mthyr">
                                    <span class="month form--span"><?php echo esc_html($checkin_month); ?></span>
                                    <span class="year form--span"><?php echo esc_html($checkin_year); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="check-in-out-date" class="tf-check-in-out-date tf-check-inout-hidden" value="<?php echo esc_attr($checkin_date); ?>" onkeypress="return false;" placeholder="<?php esc_attr_e('Check-in - Check-out', 'spa-hotel-toolkit'); ?>" <?php echo Helper::tfopt('date_hotel_search') ? 'required' : ''; ?>>
                </div>

                <!-- Check-out -->
                <div class="tf-search__form__group tf_check_inout_dates tf-checkout-group">
                    <div class="tf-search__form__field">
                        <div class="tf_checkout_dates tf-flex tf-flex-align-center">
                            <span class="date field--title"><?php echo esc_html($checkout_day); ?></span>
                            <div class="tf-search__form__field__mthyr">
                                <span class="month form--span"><?php echo esc_html($checkout_month); ?></span>
                                <span class="year form--span"><?php echo esc_html($checkout_year); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tf-search__form__fieldset__right">
                <!-- Submit Button -->
                <input type="hidden" name="type" value="tf_hotel" class="tf-post-type" />
                <button type="submit" class="tf-search__form__submit tf_btn">
                    <?php echo esc_html(apply_filters("tf_hotel_search_form_submit_button_text", 'Search')); ?>
                    <svg class="tf-search__form__submit__icon" width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.75 14.7188L11.5625 10.5312C12.4688 9.4375 12.9688 8.03125 12.9688 6.5C12.9688 2.9375 10.0312 0 6.46875 0C2.875 0 0 2.9375 0 6.5C0 10.0938 2.90625 13 6.46875 13C7.96875 13 9.375 12.5 10.5 11.5938L14.6875 15.7812C14.8438 15.9375 15.0312 16 15.25 16C15.4375 16 15.625 15.9375 15.75 15.7812C16.0625 15.5 16.0625 15.0312 15.75 14.7188ZM1.5 6.5C1.5 3.75 3.71875 1.5 6.5 1.5C9.25 1.5 11.5 3.75 11.5 6.5C11.5 9.28125 9.25 11.5 6.5 11.5C3.71875 11.5 1.5 9.28125 1.5 6.5Z" fill="white" />
                    </svg>
                </button>
            </div>
        </fieldset>
    </form>

    <script>
        (function($) {
            $(document).ready(function() {
                // flatpickr locale first day of Week
                <?php Helper::tf_flatpickr_locale("root"); ?>

                $(".tf_check_inout_dates").on("click", function() {
                    $(".tf-check-in-out-date").trigger("click");
                });

                // today + tomorrow
                const today = new Date();
                const tomorrow = new Date();
                tomorrow.setDate(today.getDate() + 1);

                $(".tf-check-in-out-date").flatpickr({
                    enableTime: false,
                    mode: "range",
                    dateFormat: "Y/m/d",
                    minDate: "today",
                    defaultDate: [today, tomorrow],
                    // flatpickr locale
                    <?php Helper::tf_flatpickr_locale(); ?>

                  
                    onChange: function(selectedDates, dateStr, instance) {
                        instance.element.value = dateStr.replace(/[a-z]+/g, '-');
                        dateSetToFields(selectedDates, instance);
                    }
                });

                function dateSetToFields(selectedDates, instance) {
                    if (selectedDates.length === 2) {
                        const monthNames = [
                            "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];
                        if (selectedDates[0]) {
                            const startDate = selectedDates[0];
                            $(".tf_checkin_dates span.date").html(startDate.getDate());
                            $(".tf_checkin_dates span.month").html(monthNames[startDate.getMonth()]);
                            $(".tf_checkin_dates span.year").html(startDate.getFullYear());
                        }
                        if (selectedDates[1]) {
                            const endDate = selectedDates[1];
                            $(".tf_checkout_dates span.date").html(endDate.getDate());
                            $(".tf_checkout_dates span.month").html(monthNames[endDate.getMonth()]);
                            $(".tf_checkout_dates span.year").html(endDate.getFullYear());
                        }
                    }
                }
            });
        })(jQuery);
    </script>
</div>