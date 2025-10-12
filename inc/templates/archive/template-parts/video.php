<?php

// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\Classes\Helper;

$term_id = get_queried_object_id();
$location_meta  = get_term_meta($term_id, 'tf_hotel_location', true);
$sec_subtitle = ! empty($location_meta['sec-subtitle']) ? $location_meta['sec-subtitle'] : esc_html__('Youtube', 'spa-hotel-toolkit');
$sec_title = ! empty($location_meta['sec-title']) ? $location_meta['sec-title'] : esc_html__('Videos', 'spa-hotel-toolkit');

$videos = isset($location_meta['videos']) ? $location_meta['videos'] : [];

$location_videos = !empty(Helper::tf_data_types($videos)) ? Helper::tf_data_types($videos) : [];

?>
<div class="tf-video-wrapper sht-sec-space">
    <div class="tf-container">
        <div class="spa-heading-wrap">
            <?php if (!empty($sec_subtitle)): ?>
                <div class="spa-subtitle">
                    <?php echo esc_html($sec_subtitle); ?>
                </div>
            <?php endif; ?>
            <?php if ($sec_title): ?>
                <h2 class="spa-title">
                    <?php echo esc_html($sec_title); ?>
                </h2>
            <?php endif; ?>
        </div>
        <?php if(!empty($location_videos)): ?>
            <div class="sht-location-video-slider">
                <div class="sht-location-video-content">
                    <?php foreach ($location_videos as $key => $video):

                        $video_url = ! empty($video['video-url']) ? $video['video-url'] : '#';
                        $video_thumbnail = ! empty($video['video-thumbnail']) ? $video['video-thumbnail'] : '';
                        $video_title = ! empty($video['video-title']) ? $video['video-title'] : '';

                    ?>
                        <div class="sht-location-video-item">
                            <a href="<?php echo esc_url($video_url); ?>" data-fancybox="location-videos" data-caption="<?php echo esc_attr($video_title); ?>">
                                <?php if (!empty($video_thumbnail)): ?>
                                    <div class="sht-video-thumbnail">
                                        <img src="<?php echo esc_url($video_thumbnail); ?>" alt="<?php echo esc_attr($video_title); ?>">
                                    </div>
                                <?php endif; ?>
                                <div class="sht-video-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="22" viewBox="0 0 18 22" fill="none">
                                        <path d="M0 0.25L18 10.75L0 21.25V0.25Z" fill="#2D5A27" />
                                    </svg>
                                </div>
                                <?php if (!empty($video_title)): ?>
                                    <h5 class="sht-video-title"><?php echo esc_html($video_title); ?></h5>
                                <?php endif; ?>
                            </a>
                        </div>

                    <?php endforeach; ?>
                </div>
                <!-- slider controls -->
                <div class="sht-slider-controls">
                    <button class="sht-arrow sht-prev" type="button" aria-label="Previous">
                        <span class="sht-arrow-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M12.5 15L7.5 10L12.5 5" stroke="#DDB892" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </button>

                    <div class="sht-pagination"></div> <!-- bullets will appear here -->

                    <button class="sht-arrow sht-next" type="button" aria-label="Next">
                        <span class="sht-arrow-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="8" height="12" viewBox="0 0 8 12" fill="none">
                                <path d="M1.5 11L6.5 6L1.5 1" stroke="#DDB892" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>