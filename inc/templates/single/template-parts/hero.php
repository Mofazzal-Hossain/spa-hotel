<?php
// Don't load directly
defined('ABSPATH') || exit;

$rating_badge = sht_sparator_rating_badge($post_id);
?>
<div class="spa-hero-wrapper tf-single-hero">
    <!-- Search -->
    <div class="tf-spa-hero-search">
        <?php echo do_shortcode("[tf_search_form type='hotel' fullwidth='true' classes='tf-hero-search-form' advanced='disabled' design='4']"); ?>
    </div>
    <!-- Breadcrumb -->
    <div class="tf-single-breadcrumb sht-breadcrumb">
        <ul>
            <li>
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <?php echo esc_html__('Home', 'spa-hotel-toolkit'); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                        <path d="M8 15.5L13 10.5L8 5.5" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </li>
            <li>
                <a href="<?php echo esc_url(home_url('/hotels')); ?>">
                    <?php echo esc_html__('Hotels', 'spa-hotel-toolkit'); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                        <path d="M8 15.5L13 10.5L8 5.5" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </li>
            <li>
                <a href="<?php echo esc_url($first_location_url); ?>">
                    <?php echo esc_html($first_location_name); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                        <path d="M8 15.5L13 10.5L8 5.5" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </li>
            <li>
                <span> <?php the_title(); ?></span>
            </li>
        </ul>
    </div>

    <!-- head -->
    <div class="tf-head">
        <div class="tf-head-left">
            <div class="sht-rator-badge <?php echo esc_attr($rating_badge['class']); ?>">
                <?php echo esc_html($rating_badge['text']); ?>
            </div>
            <h1><?php the_title(); ?></h1>
            <?php if (!empty($locations)) : ?>
                <div class="tf-title-meta">
                    <?php if ($locations) { ?>
                        <?php if (!empty($address)) : ?>
                            <div class="spa-address">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M16.6666 8.33268C16.6666 12.4935 12.0508 16.8268 10.5008 18.1652C10.3564 18.2738 10.1806 18.3325 9.99992 18.3325C9.81925 18.3325 9.64348 18.2738 9.49909 18.1652C7.94909 16.8268 3.33325 12.4935 3.33325 8.33268C3.33325 6.56457 4.03563 4.86888 5.28587 3.61864C6.53612 2.36839 8.23181 1.66602 9.99992 1.66602C11.768 1.66602 13.4637 2.36839 14.714 3.61864C15.9642 4.86888 16.6666 6.56457 16.6666 8.33268Z" stroke="#4E4E4E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M9.99992 10.8327C11.3806 10.8327 12.4999 9.71339 12.4999 8.33268C12.4999 6.95197 11.3806 5.83268 9.99992 5.83268C8.61921 5.83268 7.49992 6.95197 7.49992 8.33268C7.49992 9.71339 8.61921 10.8327 9.99992 10.8327Z" stroke="#4E4E4E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="location">
                                    <?php echo wp_kses_post($address); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php } ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="tf-head-right">
            <a href="#" class="sht-btn">
                <?php echo esc_html__('Check Availability', 'spa-hotel-toolkit'); ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M4.16663 9.99935H15.8333M15.8333 9.99935L9.99996 4.16602M15.8333 9.99935L9.99996 15.8327" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Hotel Gallery Section -->
    <div class="tf-hero-gallery">
        <div class="tf-gallery-featured <?php echo empty($gallery_ids) ? esc_attr('tf-without-gallery-featured') : ''; ?>">
            <img src="<?php echo !empty(wp_get_attachment_url(get_post_thumbnail_id(), 'tf_gallery_thumb')) ? esc_url(wp_get_attachment_url(get_post_thumbnail_id(), 'tf_gallery_thumb')) : esc_url(TF_ASSETS_APP_URL . 'images/feature-default.jpg'); ?>" alt="<?php esc_html_e('Hotel Image', 'spa-hotel-toolkit'); ?>">
        </div>
        <div class="tf-gallery">
            <?php
            $gallery_count = 1;
            if (! empty($gallery_ids)) {
                foreach ($gallery_ids as $key => $gallery_item_id) {
                    $image_url = wp_get_attachment_url($gallery_item_id, 'full');
            ?>
                    <a class="<?php echo $gallery_count == 3 ? esc_attr('tf-gallery-more') : ''; ?>" id="tour-gallery" href="<?php echo esc_url($image_url); ?>" data-fancybox="tour-gallery"><img src="<?php echo esc_url($image_url); ?>"></a>
            <?php $gallery_count++;
                }
            } ?>
        </div>
    </div>

</div>