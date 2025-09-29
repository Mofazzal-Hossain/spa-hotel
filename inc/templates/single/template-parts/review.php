<?php
// Don't load directly
defined('ABSPATH') || exit;

?>
<!-- Start Review Section -->
<?php if (! $disable_review_sec == 1) { ?>
    <div id="tf-review" class="review-section spa-single-section">
        <h4 class="tf-section-title"><?php echo ! empty($meta['review-section-title']) ? esc_html($meta['review-section-title']) : ''; ?></h4>
        <div class="reviews">
            <?php comments_template(); ?>
        </div>
       
    </div>
<?php } ?>
<!-- End Review Section -->