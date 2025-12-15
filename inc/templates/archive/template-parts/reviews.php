<?php
// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\Classes\Helper;
use \Tourfic\App\TF_Review;

$testimonial_subtitle  = ! empty(Helper::tfopt('testimonial-subtitle')) ? Helper::tfopt('testimonial-subtitle') : '';
$testimonial_title  = ! empty(Helper::tfopt('testimonial-title')) ? Helper::tfopt('testimonial-title') : '';
$testimonial_desc  = ! empty(Helper::tfopt('testimonial-description')) ? Helper::tfopt('testimonial-description') : '';

$term = get_queried_object();
$hotel_posts = get_posts([
    'post_type'      => 'tf_hotel',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'tax_query'      => [
        [
            'taxonomy' => 'hotel_location',
            'field'    => 'term_id',
            'terms'    => $term->term_id,
        ]
    ]
]);
$args = [
    'post__in' => $hotel_posts,
    'status'   => 'approve',
    'type'     => 'comment',
];

$comments_query = new WP_Comment_Query($args);
$comments = $comments_query->comments;
$comment_count = count($comments);

?>
<div class="tf-testimonial-wrapper sht-sec-space">
    <div class="tf-container">
        <div class="spa-heading-wrap">
            <?php if (!empty($testimonial_subtitle)): ?>
                <div class="spa-subtitle">
                    <?php echo esc_html($testimonial_subtitle); ?>
                </div>
            <?php endif; ?>
            <?php if ($testimonial_title): ?>
                <h2 class="spa-title">
                    <?php echo esc_html($testimonial_title); ?>
                </h2>
            <?php endif; ?>
            <?php if ($testimonial_desc): ?>
                <p class="spa-desc">
                    <?php echo esc_html($testimonial_desc); ?>
                </p>
            <?php endif; ?>
        </div>
        <div class="tf-testimonial-inner<?php echo ($comment_count < 4) ? ' hide-blur' : ''; ?>">
            <div class="tf-testimonial-slider <?php echo ($comment_count > 3) ? 'has-slider' : 'no-slide'; ?>">
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
                        $c_avatar      = get_avatar($comment, '48');
                        $user_designation = '';
                        if ($comment->user_id) {
                            $user_designation = get_user_meta($comment->user_id, 'designation', true);
                        }
                        if (empty($user_designation)) {
                            $user_designation = 'Entrepreneur';
                        }
                        $c_author_name = $comment->comment_author;
                        $c_content     = $comment->comment_content;
                        global $post_type;
                        ?>
                        <div class="tf-testimonial-item">
                            <div class="tf-shape">
                                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="40" viewBox="0 0 34 40" fill="none">
                                    <path d="M0 37.2118C0 39.084 2.34231 39.9299 3.53853 38.4897L32.7845 3.27776C33.867 1.97451 32.9402 -9.53674e-05 31.246 -9.91821e-05L2 -0.000156403C0.895431 -0.000160217 0 0.895271 0 1.99984L0 37.2118Z" fill="white" />
                                </svg>
                            </div>
                            <div class="tf-review-header">
                                <div class="tf-review-avatar"><?php echo wp_kses_post($c_avatar); ?></div>
                                <div class="tf-review-meta">
                                    <div class="tf-name"><?php echo esc_html($c_author_name); ?></div>
                                    <div class="tf-user-designation">
                                        <?php echo esc_html($user_designation); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tf-rating-stars">
                                <?php echo wp_kses_post($c_rating); ?>
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
            <?php if ($comment_count > 3): ?>
                <!-- slider controls -->
                <div class="sht-slider-controls">
                    <button class="sht-arrow sht-prev" type="button" aria-label="Previous">
                        <span class="sht-arrow-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M15 18L9 12L15 6" stroke="#BB7C3A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </button>
                    <button class="sht-arrow sht-next" type="button" aria-label="Next">
                        <span class="sht-arrow-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M9 18L15 12L9 6" stroke="#BB7C3A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if($comment_count == 0): ?>
                <p class="no-review-found" style="text-align: center;"><?php esc_html_e('No reviews found.', 'spa-hotel-toolkit'); ?></p>    
            <?php endif; ?>
        </div>
    </div>
</div>