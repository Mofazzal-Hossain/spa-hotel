<?php

// Don't load directly
defined('ABSPATH') || exit;

use \Tourfic\App\TF_Review;

if (! function_exists('sht_sparator_rating_badge')) {
    /**
     * Return rating badge text and CSS class based on post ID
     *
     * @param int $post_id
     * @return array ['text' => string, 'class' => string]
     */
    function sht_sparator_rating_badge($post_id)
    {
        $args = array(
            'post_id' => $post_id,
            'status'  => 'approve',
            'type'    => 'comment',
        );
        $comments_query = new WP_Comment_Query($args);
        $comments = $comments_query->comments;
        $tf_overall_rate = [];
        TF_Review::tf_calculate_comments_rating($comments, $tf_overall_rate, $total_rating);

        $rating = floatval($total_rating);
        $text = $class = '';

        if ($rating >= 9.0 && $rating <= 10.0) {
            /* translators: %.1f is the hotel rating number (e.g., 9.5) */
            $text  = sprintf(__('SpaRator %.1f: Exceptional', 'spa-hotel-toolkit'), $rating);
            $class = 'exceptional';
        } elseif ($rating >= 8.0 && $rating <= 8.9) {
            /* translators: %.1f is the hotel rating number (e.g., 8.7) */
            $text  = sprintf(__('SpaRator %.1f: Outstanding', 'spa-hotel-toolkit'), $rating);
            $class = 'outstanding';
        } elseif ($rating >= 7.0 && $rating <= 7.9) {
            /* translators: %.1f is the hotel rating number (e.g., 7.5) */
            $text  = sprintf(__('SpaRator %.1f: Excellent', 'spa-hotel-toolkit'), $rating);
            $class = 'excellent';
        } elseif ($rating >= 6.0 && $rating <= 6.9) {
            /* translators: %.1f is the hotel rating number (e.g., 6.8) */
            $text  = sprintf(__('SpaRator %.1f: Very Good', 'spa-hotel-toolkit'), $rating);
            $class = 'very-good';
        } else {
            /* translators: %.1f is the hotel rating number (e.g., 5.9) */
            $text  = sprintf(__('SpaRator %.1f: Good', 'spa-hotel-toolkit'), $rating);
            $class = 'good';
        }

        return ['text' => $text, 'class' => $class];
    }
}
