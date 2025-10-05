<?php
// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\Classes\Helper;

$faq_subtitle  = ! empty(Helper::tfopt('faq-subtitle')) ? Helper::tfopt('faq-subtitle') : '';
$faq_title  = ! empty(Helper::tfopt('faq-title')) ? Helper::tfopt('faq-title') : '';
$faq_desc  = ! empty(Helper::tfopt('faq-description')) ? Helper::tfopt('faq-description') : '';
$faq_items = ! empty(Helper::tf_data_types(Helper::tfopt('faq-items'))) ? Helper::tf_data_types(Helper::tfopt('faq-items')) : [];

?>

<div class="tf-faq-wrapper sht-sec-space">
    <div class="tf-container">
        <div class="spa-heading-wrap">
            <?php if (!empty($faq_subtitle)): ?>
                <div class="spa-subtitle"><?php echo esc_html($faq_subtitle); ?></div>
            <?php endif; ?>
            <?php if (!empty($faq_title)): ?>
                <h2 class="spa-title"><?php echo esc_html($faq_title); ?></h2>
            <?php endif; ?>
            <?php if ($faq_desc): ?>
                <p class="spa-desc">
                    <?php echo esc_html($faq_desc); ?>
                </p>
            <?php endif; ?>
        </div>
        <div class="tf-faq-inner">
            <?php foreach ($faq_items as $key => $faq_item): ?>
                <div class="tf-faq-item">
                    <div class="tf-faq-item-title">
                        <div class="tf-faq-item-icon">
                            <div class="tf-plus">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="none">
                                    <path d="M4.16675 10.7493H15.8334M10.0001 4.91602V16.5827" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="tf-minus">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="none">
                                    <path d="M4.16675 10.75H15.8334" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="tf-faq-item-title-text">
                            <?php echo esc_html($faq_item['faq-question']); ?>
                        </div>
                    </div>
                    <div class="tf-faq-item-content">
                        <p><?php echo esc_html($faq_item['faq-answer']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>