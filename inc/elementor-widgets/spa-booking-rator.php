<?php

class Spa_Booking_Rator extends \Elementor\Widget_Base
{
    /**
     * Get widget name.
     *
     * Retrieve  widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'spa-booking-rator';
    }

    /**
     * Get widget title.
     *
     * Retrieve  widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('Spa Booking Rator', 'spa-hotel-toolkit');
    }

    /**
     * Get widget icon.
     *
     * Retrieve  widget icon.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-info-box';
    }

    /**
     * Get custom help URL.
     *
     * Retrieve a URL where the user can get more information about the widget.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget help URL.
     */
    public function get_custom_help_url()
    {
        return 'https://developers.elementor.com/docs/widgets/';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget categories.
     */
    public function get_categories(): array
    {
        return ['spa'];
    }


    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget keywords.
     */
    public function get_keywords()
    {
        return ['spa', 'booking', 'rator'];
    }
    public function get_style_depends()
    {
        return ['spa-booking-rator'];
    }
    /**
     * Register widget controls.
     *
     * Add input fields to allow the user to customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls()
    {

        $this->start_controls_section(
            'booking_rator',
            [
                'label' => __('Booking Rator', 'spa-hotel-toolkit'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'rator_title',
            [
                'label'       => __('Rator Title', 'spa-hotel-toolkit'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => __('SpaRator', 'spa-hotel-toolkit'),
                'placeholder' => __('Enter title', 'spa-hotel-toolkit'),
            ]
        );
        $this->add_control(
            'rator_score',
            [
                'label'       => __('Score Text', 'spa-hotel-toolkit'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => __('Score', 'spa-hotel-toolkit'),
                'placeholder' => __('Enter text', 'spa-hotel-toolkit'),
            ]
        );

        $this->add_control(
            'label_treatments',
            [
                'label'       => __('Treatments Label', 'spa-hotel-toolkit'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => __('Treatments', 'spa-hotel-toolkit'),
                'placeholder' => __('Enter label', 'spa-hotel-toolkit'),
            ]
        );

        $this->add_control(
            'label_spa_facilities',
            [
                'label'       => __('Spa Facilities Label', 'spa-hotel-toolkit'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => __('Spa Facilities', 'spa-hotel-toolkit'),
                'placeholder' => __('Enter label', 'spa-hotel-toolkit'),
            ]
        );

        $this->add_control(
            'label_wellness',
            [
                'label'       => __('Wellness Programs Label', 'spa-hotel-toolkit'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => __('Wellness Programs', 'spa-hotel-toolkit'),
                'placeholder' => __('Enter label', 'spa-hotel-toolkit'),
            ]
        );

        $this->add_control(
            'label_staff',
            [
                'label'       => __('Staff & Service Label', 'spa-hotel-toolkit'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => __('Staff & Service', 'spa-hotel-toolkit'),
                'placeholder' => __('Enter label', 'spa-hotel-toolkit'),
            ]
        );

        $this->add_control(
            'label_experience',
            [
                'label'       => __('Experience Label', 'spa-hotel-toolkit'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => __('Experience', 'spa-hotel-toolkit'),
                'placeholder' => __('Enter label', 'spa-hotel-toolkit'),
            ]
        );

        $this->add_control(
            'label_value',
            [
                'label'       => __('Value for Money Label', 'spa-hotel-toolkit'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => __('Value for Money', 'spa-hotel-toolkit'),
                'placeholder' => __('Enter label', 'spa-hotel-toolkit'),
            ]
        );


        $this->end_controls_section();

        $this->end_controls_tab();

        $this->end_controls_tabs();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $rator_data = [
            'treatments' => [
                'label' => $settings['label_treatments'],
                'score' => 8,
                'icon'  => SPA_HOTEL_TOOLKIT_ASSETS . 'images/treatments.svg',
            ],
            'spa_facilities' => [
                'label' => $settings['label_spa_facilities'],
                'score' => 9,
                'icon'  => SPA_HOTEL_TOOLKIT_ASSETS . 'images/facilities.svg',
            ],
            'wellness' => [
                'label' => $settings['label_wellness'],
                'score' =>7,
                'icon'  => SPA_HOTEL_TOOLKIT_ASSETS . 'images/wellness.svg',
            ],
            'staff' => [
                'label' => $settings['label_staff'],
                'score' => 6,
                'icon'  => SPA_HOTEL_TOOLKIT_ASSETS . 'images/staff.svg',
            ],
            'experience' => [
                'label' => $settings['label_experience'],
                'score' => 4,
                'icon'  => SPA_HOTEL_TOOLKIT_ASSETS . 'images/experience.svg',
            ],
            'value' => [
                'label' => $settings['label_value'],
                'score' => 9,
                'icon'  => SPA_HOTEL_TOOLKIT_ASSETS . 'images/money.svg',
            ],
        ];

        $rator_meter = SPA_HOTEL_TOOLKIT_ASSETS . 'images/rator-meter.png';
        $rator_handle = SPA_HOTEL_TOOLKIT_ASSETS . 'images/rator-handle.png';

        // Extract scores
        $scores = array_column($rator_data, 'score');
        $total_score = array_sum($scores);
        $count       = count($scores);
        $average     = $count > 0 ? round($total_score / $count, 1) : 0;
        $transform = get_rating_transform($average);


?>
        <div class="sht-review-rator-box">
            <div class="sht-review-gauge">
                <div class="sht-rator-meter">
                    <img src="<?php echo esc_url($rator_meter); ?>" alt="Rator Meter">
                    <div class="sht-gauge-needle">
                        <img src="<?php echo esc_url($rator_handle); ?>"
                            alt="Rator Handle"
                            style="transform: rotate(<?php echo $transform['rotate']; ?>deg) translate(<?php echo $transform['tx']; ?>px, <?php echo $transform['ty']; ?>px);">
                    </div>
                    <div class="sht-gauge-score">
                        <span class="sht-text"><?php echo esc_html($settings['rator_title']); ?></span>
                        <span class="sht-score"><?php echo esc_html($average); ?></span>
                        <span class="sht-text"><?php echo esc_html($settings['rator_score']); ?></span>
                    </div>
                </div>
            </div>
            <div class="sht-review-progress-lists">
                <?php foreach ($rator_data as $key => $item): ?>
                    <div class="sht-review-item <?php echo esc_attr($key); ?>">
                        <div class="icon">
                            <img src="<?php echo esc_url($item['icon']); ?>" alt="<?php echo esc_attr($item['label']); ?>">
                        </div>
                        <div class="sht-review-item-content">
                            <div class="sht-review-progress-report">
                                <span class="sht-review-label"><?php echo esc_html__($item['label'], 'spa-hotel-toolkit'); ?></span>
                                <div class="sht-review-score"><?php echo esc_html($item['score']); ?>/10</div>
                            </div>
                            <div class="sht-review-bar">
                                <span class="sht-bar-fill" style="width: <?php echo ($item['score'] * 10); ?>%"></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>


<?php
    }
}

// Get rating transform
function get_rating_transform($average) {
    $positions = [
        1  => ['rotate' => -92, 'tx' => -28, 'ty' => -60],
        2  => ['rotate' => -73, 'tx' => -27, 'ty' => -35],
        3  => ['rotate' => -52, 'tx' => -28, 'ty' => -20],
        4  => ['rotate' => -30, 'tx' => -28, 'ty' => -6],
        5  => ['rotate' => -14, 'tx' =>  -4, 'ty' =>  1],
        6  => ['rotate' =>   14, 'tx' =>   8, 'ty' =>  0],
        7  => ['rotate' =>  38, 'tx' =>   12, 'ty' => -0],
        8  => ['rotate' =>  54, 'tx' =>  20, 'ty' => -22],
        9  => ['rotate' =>  70, 'tx' =>  28, 'ty' => -32],
        10 => ['rotate' =>  90, 'tx' =>  30, 'ty' => -50],
    ];

    // Clamp value between 1 and 10
    $average = max(1, min(10, $average));
    
    // Find the two closest positions to interpolate between
    $floor = floor($average);
    $ceil = ceil($average);
    
    // If it's exactly on an integer, return that position
    if ($floor == $ceil) {
        return $positions[$floor];
    }
    
    // Calculate interpolation ratio (0 to 1)
    $ratio = $average - $floor;
    
    // Interpolate between the two positions
    $result = [];
    foreach (['rotate', 'tx', 'ty'] as $property) {
        $start = $positions[$floor][$property];
        $end = $positions[$ceil][$property];
        $result[$property] = $start + ($end - $start) * $ratio;
    }
    
    return $result;
}