<?php
// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\App\TF_Review;
use \Tourfic\Classes\Helper;
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

                        // Get rating details
                        $tf_overall_rate = get_comment_meta($comment->comment_ID, TF_TOTAL_RATINGS, true);
             

                        if ($tf_overall_rate == false) {
                            $tf_comment_meta = get_comment_meta($comment->comment_ID, TF_COMMENT_META, true);
                            $tf_overall_rate = TF_Review::Tf_average_ratings($tf_comment_meta);
                        }
                        $base_rate = get_comment_meta($comment->comment_ID, TF_BASE_RATE, true);
                        $c_rating  = TF_Review::tf_single_rating_change_on_base($tf_overall_rate, $base_rate);

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
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>

    </div>
<?php } ?>


<!-- End Review Section -->