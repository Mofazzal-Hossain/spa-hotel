<?php
defined('ABSPATH') || exit;

$hotel_meta = get_post_meta($post_id, 'tf_hotels_opt', true);
$sec_title = ! empty($hotel_meta['feeds-sec-title']) ? $hotel_meta['feeds-sec-title'] : esc_html__('Guest Experiences', 'spa-hotel-toolkit');
$instagram_posts = ! empty($hotel_meta['instagram-posts']) ? $hotel_meta['instagram-posts'] : [];
?>

<div class="tf-insta-feeds spa-single-section">
    <?php if(!empty($sec_title)): ?>
        <h4 class="tf-section-title"><?php echo esc_html($sec_title); ?></h4>
    <?php endif; ?>

    <div class="tf-insta-post-wrap">
        <?php foreach ($instagram_posts as $posts):
            $insta_post_url = isset($posts['instagram-post-url']) ? trim($posts['instagram-post-url']) : '';
            if(!$insta_post_url) continue;
        ?>
            <div class="tf-insta-post">
                <blockquote class="instagram-media"
                    data-instgrm-permalink="<?php echo esc_url($insta_post_url); ?>"
                    data-instgrm-version="14"></blockquote>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
// Load Instagram embed script
add_action('wp_footer', function() {
    echo '<script async src="//www.instagram.com/embed.js"></script>';
});
?>
