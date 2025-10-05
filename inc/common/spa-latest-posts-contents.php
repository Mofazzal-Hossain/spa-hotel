<?php
// Don't load directly
defined('ABSPATH') || exit;

if (empty($query) || ! $query instanceof WP_Query) {
    return;
}

?>
<div class="sht-latest-posts-wrapper sht-blog-posts">
    <div class="sht-blog-grid-section">
        <?php if ($query->have_posts()) :
            $index = 0;
        ?>
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <div class="sht-col-item sht-post-single-item">
                    <div class="sht-blog-thumbnail">
                        <a href="<?php the_permalink(); ?>">
                            <?php
                            if (get_the_post_thumbnail_url(get_the_ID())) {
                                the_post_thumbnail('blog-thumb');
                            } else { ?>
                                <img src="<?php echo esc_url(site_url() . '/wp-content/plugins/elementor/assets/images/placeholder.png'); ?>" alt="Post">
                            <?php }
                            ?>
                        </a>
                    </div>
                    <div class="sht-content-details">
                        <div class="sht-post-meta">
                            <p class="sht-meta">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                                    <path d="M5.33333 1.83301V4.49967M10.6667 1.83301V4.49967M2 7.16634H14M3.33333 3.16634H12.6667C13.403 3.16634 14 3.76329 14 4.49967V13.833C14 14.5694 13.403 15.1663 12.6667 15.1663H3.33333C2.59695 15.1663 2 14.5694 2 13.833V4.49967C2 3.76329 2.59695 3.16634 3.33333 3.16634Z" stroke="#7C7C7C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <?php echo get_the_date('M j, Y'); ?>
                            </p>
                            <div class="sht-meta-divider"></div>
                            <p class="sht-meta">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                                    <g clip-path="url(#clip0_814_69)">
                                        <path d="M8.11003 4.49967V8.49967L10.7767 9.83301M14.7767 8.49967C14.7767 12.1816 11.7919 15.1663 8.11003 15.1663C4.42813 15.1663 1.44336 12.1816 1.44336 8.49967C1.44336 4.81778 4.42813 1.83301 8.11003 1.83301C11.7919 1.83301 14.7767 4.81778 14.7767 8.49967Z" stroke="#7C7C7C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_814_69">
                                            <rect width="16" height="16" fill="white" transform="translate(0.110107 0.5)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                                <?php echo do_shortcode('[sht_reading_time]'); ?>
                            </p>
                        </div>
                        <?php if ($index == 0): ?>
                            <p class="sht-meta sht-categories">
                                <?php
                                $categories = get_the_category();
                                if (! empty($categories)) {
                                    foreach ($categories as $cat) {
                                        echo '<a href="' . esc_url(get_category_link($cat->term_id)) . '" class="sht-category">'
                                            . esc_html($cat->name) .
                                            '</a> ';
                                    }
                                }
                                ?>
                            </p>
                        <?php endif; ?>
                        <h3 class="sht-title">
                            <a href="<?php echo esc_url(get_permalink()); ?>">
                                <?php echo esc_html(get_the_title()); ?>
                            </a>
                        </h3>
                        <?php if ($index == 0): ?>
                            <div class="post-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
                        <div class="sht-read-more">
                            <a href="<?php echo esc_url(get_permalink()); ?>" class="sht-btn sht-btn-transparent">
                                <?php echo esc_html_e("Read Details", "spa-hotel-toolkit"); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                                    <path d="M1.16675 7.00033H12.8334M12.8334 7.00033L7.00008 1.16699M12.8334 7.00033L7.00008 12.8337" stroke="#D4A574" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            <?php $index++;
            endwhile; ?>
        <?php endif;  ?>
    </div>
</div>