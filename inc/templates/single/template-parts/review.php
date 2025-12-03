<?php
// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\App\TF_Review;
use \Tourfic\Classes\Helper;

$tf_setting_base = ! empty(Helper::tfopt('r-base')) ? Helper::tfopt('r-base') : 10;
$tf_hotel_review = !empty(Helper::tf_data_types(Helper::tfopt('r-hotel')))
    ? Helper::tf_data_types(Helper::tfopt('r-hotel'))
    : [];



?>
<!-- Start Review Section -->
<?php if (! $disable_review_sec == 1) { ?>
    <div id="tf-review" class="tf-review-wrapper spa-single-section">
        <h4 class="tf-section-title"><?php echo ! empty($meta['review-section-title']) ? esc_html($meta['review-section-title']) : ''; ?></h4>
        <div class="reviews">
            <?php comments_template(); ?>
            <div class="tf-single-review <?php echo esc_attr(get_post_type($post_id)) ?>">
                <?php
                if ($comments) {
                    foreach ($comments as $comment) {

                        $review_images = get_comment_meta($comment->comment_ID, 'sht_review_media', true);
                        $rating_params = get_comment_meta($comment->comment_ID, 'sht_ratings', true);

                        $tf_overall_rate = TF_Review::Tf_average_ratings($rating_params);

                        $c_rating  = TF_Review::tf_single_rating_change_on_base($tf_overall_rate, $tf_setting_base);

                        // Comment details
                        $c_avatar      = get_avatar($comment, '56');
                        $c_author_name = $comment->comment_author;
                        $c_date        = $comment->comment_date;
                        $review_date = gmdate('d M, Y', strtotime($c_date));
                        $c_content     = $comment->comment_content;
                        global $post_type;
                ?>
                        <div class="tf-single-details">
                            <div class="tf-review-header">
                                <div class="tf-review-details">
                                    <div class="tf-review-avatar"><?php echo wp_kses_post($c_avatar); ?></div>
                                    <div class="tf-review-meta">
                                        <div class="tf-name"><?php echo esc_html($c_author_name); ?></div>
                                        <div class="tf-rating-stars">
                                            <?php echo wp_kses_post($c_rating); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="tf-date"><?php echo esc_html($review_date); ?></div>
                            </div>
                            <?php if ($post_type == 'hotel') {
                                if ($tf_hotel_selected_template == "default") {
                                    if (strlen($c_content) > 120) { ?>
                                        <div class="tf-description">
                                            <p><?php echo wp_kses_post(Helper::tourfic_character_limit_callback($c_content, 200)) ?></p>
                                        </div>
                                        <div class="tf-full-description" style="display:none;">
                                            <p><?php echo wp_kses_post($c_content) ?></p>
                                        </div>
                                    <?php } else { ?>
                                        <div class="tf-description">
                                            <p><?php echo wp_kses_post($c_content); ?></p>
                                        </div>
                                <?php
                                    }
                                }
                            } else { ?>
                                <div class="tf-description">
                                    <p><?php echo wp_kses_post($c_content); ?></p>
                                </div>
                            <?php } ?>
                            <?php if ($post_type == 'hotel' && $tf_hotel_selected_template == "default" && strlen($c_content) > 200): ?>
                                <div class="tf-hotel-show-more"><?php esc_html_e("Show more", "spa-hotel-toolkit") ?></div>
                            <?php endif; ?>

                            <?php if (!empty($review_images) && is_array($review_images)): ?>
                                <div class="tf-review-images">
                                    <?php foreach ($review_images as $img_id):
                                        $img_url = wp_get_attachment_url($img_id);
                                        $mime = get_post_mime_type($img_id);
                                    ?>
                                        <?php if (!empty($img_url)): ?>
                                            <div class="tf-review-image-item">
                                                <a href="<?php echo esc_url($img_url); ?>" data-fancybox="review-images">
                                                    <?php if (strpos($mime, 'video') !== false): ?>
                                                        <div class="video-thumb-wrapper">
                                                            <video width="100" height="100">
                                                                <source src="<?php echo esc_url($img_url); ?>" type="<?php echo esc_attr($mime); ?>">
                                                            </video>
                                                            <div class="video-overlay">
                                                                <span class="video-play-icon"><i class="fa-regular fa-circle-play"></i></span>
                                                            </div>
                                                        </div>
                                                    <?php else: ?>
                                                        <img src="<?php echo esc_url($img_url); ?>">
                                                    <?php endif; ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                <?php
                    }
                } else {
                    echo '<p class="tf-no-review">' . esc_html__('No Review Available', 'spa-hotel-toolkit') . '</p>';
                }
                ?>
                <div class="tf-btn-wrap">
                    <button class="tf-btn-wrap sht-modal-btn">
                        <i class="fas fa-plus"></i>
                        <?php echo esc_html(apply_filters('tourfic_add_review_button_text', __('Add Review', 'spa-hotel-toolkit'))); ?>
                    </button>
                </div>
            </div>
            <div class="sht-modal" id="sht-rating-modal">
                <div class="sht-modal-dialog">
                    <div class="sht-modal-content">
                        <div class="sht-modal-header">
                            <?php echo apply_filters('tf_rating_modal_header_content', ''); ?>
                            <a data-dismiss="modal" class="sht-modal-close">&#10005;</a>
                        </div>
                        <div class="sht-modal-body">
                            <div class="tf-review-form-container">
                                <form method="post" id="sht-review-form"
                                    class="tf-review-form">
                                    <input type="hidden" name="sht_review_nonce" value="<?php echo wp_create_nonce('sht_review_nonce'); ?>">

                                    <div class="tf-rating-wrapper tf-star-base-<?php echo esc_attr($tf_setting_base); ?>">

                                        <?php foreach ($tf_hotel_review as $review_field) :

                                            if (empty($review_field['r-field-type'])) continue;

                                            // Raw and safe keys
                                            $label_text   = $review_field['r-field-type'];
                                            $field_key    = sanitize_title($label_text);

                                        ?>
                                            <div class="tf-form-single-rating">
                                                <label for="<?php echo esc_attr('sht-' . $field_key); ?>"><?php echo esc_html($label_text); ?></label>

                                                <div class="ratings-container star<?php echo esc_attr($tf_setting_base); ?>">

                                                    <?php for ($i = $tf_setting_base; $i >= 1; $i--) :

                                                        $input_id = $field_key . '-' . $i;
                                                        $name     = 'sht_comment_meta[' . $field_key . ']';

                                                    ?>
                                                        <input
                                                            type="radio"
                                                            id="<?php echo esc_attr('sht-' . $input_id); ?>"
                                                            name="<?php echo esc_attr($name); ?>"
                                                            value="<?php echo esc_attr($i); ?>">
                                                        <label for="<?php echo esc_attr('sht-' . $input_id); ?>"><?php echo esc_html($i); ?></label>

                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                    </div>
                                    <div class="tf-fields">
                                        <div class="tf-visitor-info">
                                            <div><input type="text" id="first_name" name="first_name" aria-required="true" placeholder="First Name*" required></div>
                                            <div><input type="text" id="surname" name="surname" aria-required="true" placeholder="Surname (Optional)"></div>
                                        </div>
                                        <div class="tf-visitor-email">
                                            <input type="email" id="user-email" name="user-email" placeholder="Email*" required>
                                        </div>

                                        <div class="review-desc">
                                            <textarea id="sht_comment" name="sht_comment" aria-required="true" placeholder="Review Description*" required></textarea>
                                        </div>
                                        <div class="tf-review-media">
                                            <label for="review_media" class="media-upload-label">
                                                Upload Photos/Videos (Optional, up to 5)
                                            </label>

                                            <input type="file"
                                                id="review_media"
                                                name="review_media[]"
                                                accept="image/*,video/*"
                                                multiple
                                                onchange="previewFiles(event)"
                                                hidden>

                                            <div id="media-preview" class="media-preview"></div>
                                        </div>

                                        <div class="tf-visit-date">
                                            <input type="text"
                                                name="sht_visit_date"
                                                id="sht_visit_date"
                                                class="tf-visit-date-input flatpickr-input"
                                                placeholder="Visit Date (Optional)"
                                                readonly>
                                        </div>

                                        <div class="tf-review-submit">
                                            <input name="submit" type="submit" id="sht-comment-submit" class="tf_btn tf_btn_small" value="Submit">
                                            <input type="hidden" name="sht_comment_post_ID" value="<?php echo get_the_ID(); ?>" id="sht_comment_post_ID">
                                            <input type="hidden" name="sht_comment_parent" id="sht_comment_parent" value="0">
                                            <div class="loader" style="display: none;"></div>
                                        </div>
                                    </div>
                                </form>
                                <div class="review-success-message" style="display: none;">
                                    <p><?php echo esc_html__('We appreciate your feedback! Your review will be visible once approved.', 'spa-hotel-toolkit'); ?></p>
                                </div>
                                <div class="review-error-message" style="display: none;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>


<!-- End Review Section -->