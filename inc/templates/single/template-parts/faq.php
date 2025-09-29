<?php
// Don't load directly
defined('ABSPATH') || exit;

if ($faqs): ?>
    <!-- Hotel Questions Srart -->
    <div class="tf-hotel-faqs-section spa-single-section">
        <div class="tf-container">
            <h4 class="tf-section-title"><?php echo ! empty($meta['faq-section-title']) ? esc_html($meta['faq-section-title']) : ''; ?></h4>
            <div class="tf-section-flex tf-flex">
                <?php
                $tf_enquiry_section_status = ! empty($meta['h-enquiry-section']) ? $meta['h-enquiry-section'] : "";
                $tf_enquiry_section_icon   = ! empty($meta['h-enquiry-option-icon']) ? esc_html($meta['h-enquiry-option-icon']) : '';
                $tf_enquiry_section_title  = ! empty($meta['h-enquiry-option-title']) ? esc_html($meta['h-enquiry-option-title']) : '';
                $tf_enquiry_section_des    = ! empty($meta['h-enquiry-option-content']) ? esc_html($meta['h-enquiry-option-content']) : '';
                $tf_enquiry_section_button = ! empty($meta['h-enquiry-option-btn']) ? esc_html($meta['h-enquiry-option-btn']) : '';

                ?>
                <div class="tf-faq-items-wrapper">
                    <?php foreach ($faqs as $key => $faq): ?>
                        <div id="tf-faq-item" class="tf-faq-item">
                            <div class="tf-faq-title">
                                <div class="tf-faq-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="tf-icon-minus" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M4.16675 10H15.8334" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="tf-icon-plus" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M4.16675 9.99935H15.8334M10.0001 4.16602V15.8327" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <h6><?php echo esc_html($faq['title']); ?></h6>
                            </div>
                            <div class="tf-faq-desc">
                                <p><?php echo wp_kses_post($faq['description']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Hotel Questions end -->
<?php endif; ?>