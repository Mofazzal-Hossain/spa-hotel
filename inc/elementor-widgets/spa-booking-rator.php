<?php
use \Tourfic\Classes\Helper;

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


        $this->end_controls_section();

        $this->end_controls_tab();

        $this->end_controls_tabs();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $tf_settings_base = ! empty(Helper::tfopt('r-base')) ? Helper::tfopt('r-base') : 10;
        $tf_hotel_review = ! empty(Helper::tf_data_types(Helper::tfopt('r-hotel'))) ? Helper::tf_data_types(Helper::tfopt('r-hotel')) : [];

        $rator_data = [];
        $total_rating_sum = 0;
        $total_hotel_count = 0;

        // Get all hotels
        $hotels = get_posts([
            'post_type'      => 'tf_hotel',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        ]);

        foreach ($hotels as $post_id) {
            $hotel_meta = get_post_meta($post_id, 'tf_hotels_opt', true);

            $hotel_total = 0;
            $hotel_count = 0;

            foreach ($tf_hotel_review as $field) {
                if (empty($field['r-field-type'])) continue;

                $label = $field['r-field-type'];
                $icon = isset($field['r-field-icon']) ? $field['r-field-icon'] : '';
                $slug  = sanitize_title($label);
                $meta_key = "rator-{$slug}-score";

                $score = isset($hotel_meta[$meta_key]) ? floatval($hotel_meta[$meta_key]) : 0;
                if ($score > 0) {
                    $hotel_total += $score;
                    $hotel_count++;
                }

                // Collect rator data for the first hotel only for display
                if (!isset($rator_data[$slug])) {
                    $rator_data[$slug] = [
                        'label' => $label,
                        'score' => 0,
                        'count' => 0,
                        'icon' => $icon,
                    ];
                }

                if ($score > 0) {
                    $rator_data[$slug]['score'] += $score;
                    $rator_data[$slug]['count']++;
                }
            }

            if ($hotel_count > 0) {
                $total_rating_sum += ($hotel_total / $hotel_count);
                $total_hotel_count++;
            }
        }
        $total_rating = $total_hotel_count ? ($total_rating_sum / $total_hotel_count) : 0;
        if ($tf_settings_base != 10) {
            $total_rating = ($total_rating / 10) * $tf_settings_base;
        }

        // Normalize each field's score
        foreach ($rator_data as $key => &$item) {
            $item['score'] = $item['count'] ? $item['score'] / $item['count'] : 0;

            // Convert 10-base → 5-base
            if ($tf_settings_base != 10) {
                $item['score'] = ($item['score'] / 10) * $tf_settings_base;
            }
        }
        unset($item);

        if($tf_settings_base == 10) {
            $rator_meter  = SHT_HOTEL_TOOLKIT_ASSETS . 'images/rator-meter.png';
        }else{
            $rator_meter  = SHT_HOTEL_TOOLKIT_ASSETS . 'images/rator-meter-min.png';
        }
        $rator_handle = SHT_HOTEL_TOOLKIT_ASSETS . 'images/rator-handle.png';
        $transform    = get_rating_transform($total_rating, $tf_settings_base);
?>
        <div class="sht-review-rator-box">
            <div class="sht-review-gauge">
                <div class="sht-rator-meter">
                    <img src="<?php echo esc_url($rator_meter); ?>" alt="Rator Meter">
                    <div class="sht-gauge-needle">
                        <img src="<?php echo esc_url($rator_handle); ?>"
                            alt="Rator Handle"
                            style="transform: rotate(<?php echo esc_attr($transform['rotate']); ?>deg) translate(<?php echo esc_attr($transform['tx']); ?>px, <?php echo esc_attr($transform['ty']); ?>px);">
                    </div>
                    <div class="sht-gauge-score">
                        <span class="sht-text"><?php echo esc_html($settings['rator_title']); ?></span>
                        <span class="sht-score">
                            <?php
                            if ($total_rating == 10 || $total_rating == 5) {
                                echo esc_html($tf_settings_base);
                            } else {
                                echo esc_html(number_format($total_rating, 1));
                            }
                            ?>
                        </span>
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
                                <span class="sht-review-label"><?php echo esc_html($item['label']); ?></span>
                                <div class="sht-review-score">
                                    <?php
                                    if ($item['score'] == 10 || $item['score'] == 5) {
                                        echo esc_html($tf_settings_base);
                                    } else {
                                        echo esc_html(number_format($item['score'], 1));
                                    }
                                    ?>/<?php echo esc_html($tf_settings_base); ?>
                                </div>
                            </div>
                            <div class="sht-review-bar">
                                <span class="sht-bar-fill" style="width: <?php echo intval(($item['score'] / $tf_settings_base) * 100); ?>%"></span>
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
function get_rating_transform($average, $base = 10)
{

    if ($base == 5) {
        $average = ($average / 5) * 10;
    }

    // Position map (1–10)
    $positions = [
        1  => ['rotate' => -92, 'tx' => -28, 'ty' => -60],
        2  => ['rotate' => -73, 'tx' => -27, 'ty' => -35],
        3  => ['rotate' => -52, 'tx' => -28, 'ty' => -20],
        4  => ['rotate' => -30, 'tx' => -28, 'ty' => -6],
        5  => ['rotate' => -14, 'tx' => -4,  'ty' => 1],
        6  => ['rotate' => 14,  'tx' => 8,   'ty' => 0],
        7  => ['rotate' => 38,  'tx' => 12,  'ty' => 0],
        8  => ['rotate' => 54,  'tx' => 20,  'ty' => -22],
        9  => ['rotate' => 70,  'tx' => 28,  'ty' => -32],
        10 => ['rotate' => 90,  'tx' => 30,  'ty' => -50],
    ];

    $average = max(1, min(10, $average));

    $floor = floor($average);
    $ceil  = ceil($average);

    if ($floor == $ceil) {
        return $positions[$floor];
    }

    $ratio = $average - $floor;

    $result = [];
    foreach (['rotate', 'tx', 'ty'] as $prop) {
        $start = $positions[$floor][$prop];
        $end   = $positions[$ceil][$prop];
        $result[$prop] = $start + ($end - $start) * $ratio;
    }

    return $result;
}

