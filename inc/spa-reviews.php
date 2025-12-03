<?php

// Don't load directly
defined('ABSPATH') || exit;

// Review submission via AJAX
add_action('wp_ajax_sht_submit_review', 'sht_submit_review');
add_action('wp_ajax_nopriv_sht_submit_review', 'sht_submit_review');

function sht_submit_review()
{

    if (!isset($_POST['sht_review_nonce']) || !wp_verify_nonce($_POST['sht_review_nonce'], 'sht_review_nonce')) {
        wp_send_json_error('Invalid request.');
    }

    $post_id   = intval($_POST['sht_comment_post_ID']);
    $parent_id = intval($_POST['sht_comment_parent']);
    $author    = sanitize_text_field($_POST['first_name']);
    $email     = sanitize_email($_POST['user-email']);
    $comment   = sanitize_textarea_field($_POST['sht_comment']);
    $visit_date = !empty($_POST['sht_visit_date']) ? sanitize_text_field($_POST['sht_visit_date']) : '';

    $existing_comments = get_comments([
        'post_id' => $post_id,
        'author_email' => $email,
        'status' => 'approve',
        'count' => true
    ]);

    if ($existing_comments > 0) {
        wp_send_json_error(['message' => 'You have already submitted a review for this post.']);
    }

    $ratings = [];
    if (!empty($_POST['sht_comment_meta'])) {
        foreach ($_POST['sht_comment_meta'] as $key => $value) {
            $ratings[sanitize_key($key)] = intval($value);
        }
    }

    if (empty($ratings)) {
        wp_send_json_error(['message' => 'Please select at least one star rating.']);
    }

    $commentdata = [
        'comment_post_ID'      => $post_id,
        'comment_parent'       => $parent_id,
        'comment_author'       => $author,
        'comment_author_email' => $email,
        'comment_content'      => $comment,
        'comment_approved'     => 1,
    ];

    $comment_id = wp_insert_comment($commentdata);

    if (!$comment_id) {
        wp_send_json_error(['message' => 'Failed to submit comment.']);
    }

    // Save custom meta
    update_comment_meta($comment_id, 'sht_visit_date', $visit_date);
    update_comment_meta($comment_id, 'sht_ratings', $ratings);

    // Handle file uploads
    if (!empty($_FILES['review_media']) && !empty($_FILES['review_media']['name'][0])) {

        $uploaded_files = [];

        foreach ($_FILES['review_media']['name'] as $i => $name) {
            if ($_FILES['review_media']['error'][$i] !== 0) continue;

            $file = [
                'name'     => $_FILES['review_media']['name'][$i],
                'type'     => $_FILES['review_media']['type'][$i],
                'tmp_name' => $_FILES['review_media']['tmp_name'][$i],
                'error'    => $_FILES['review_media']['error'][$i],
                'size'     => $_FILES['review_media']['size'][$i],
            ];

            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';

            $attach_id = media_handle_sideload($file, $post_id);

            if (!is_wp_error($attach_id)) {
                $uploaded_files[] = $attach_id;
            }
        }

        if (!empty($uploaded_files)) {
            update_comment_meta($comment_id, 'sht_review_media', $uploaded_files);
        }
    }

    wp_send_json_success(['message' => 'Review submitted successfully!']);
}


// Add comment meta box
add_action('add_meta_boxes_comment', 'sht_add_comment_edit_fields');
function sht_add_comment_edit_fields()
{

    add_meta_box(
        'sht_comment_meta_box',
        __('SpaRator Review Data'),
        'sht_render_comment_meta_box',
        'comment',
        'normal',
        'high'
    );
}

function sht_render_comment_meta_box($comment)
{

    $ratings = get_comment_meta($comment->comment_ID, 'sht_ratings', true);
    $visit_date = get_comment_meta($comment->comment_ID, 'sht_visit_date', true);
    $media = get_comment_meta($comment->comment_ID, 'sht_review_media', true);
    wp_nonce_field('sht_comment_edit_nonce', 'sht_comment_edit_nonce_field');

?>
    <p style="font-size: 18px; margin: 10px 0 5px;"><strong><?php echo esc_html__('Ratings', 'spa-hotel-toolkit'); ?></strong></p>
    <?php
    if (!empty($ratings)) { ?>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <?php
            foreach ($ratings as $key => $value) {
                $label = str_replace('-', ' ', $key); ?>
                <div>
                    <label style="font-size: 15px;"><?php echo esc_html(ucwords($label)); ?></label>
                    <input type="number" name="sht_ratings[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($value); ?>" step=".1" min="1" max="10" style="width:100%;margin:5px 0 0px;">
                </div>
            <?php
            }
            ?>
        </div>
    <?php
    }

    ?>

    <p style="font-size: 18px; margin: 10px 0 4px; display: block;"><strong><?php echo esc_html__('Uploaded Media', 'spa-hotel-toolkit'); ?></strong></p>
    <div class="uploaded-images" style="display:flex; gap:16px; flex-wrap:wrap;">
        <?php
        foreach ($media as $id) {
            $url  = wp_get_attachment_url($id);
            $mime = get_post_mime_type($id);

            echo '<div style="margin-bottom:10px;">';

            if (strpos($mime, 'video') !== false) {
                echo '<video width="100" height="100" controls style="border-radius:5px;border:.2px solid #eae1d7;">
                <source src="' . esc_url($url) . '" type="' . esc_attr($mime) . '">
              </video>';
            } else {
                echo '<img src="' . esc_url($url) . '" style="width:100px;height:100px;object-fit:cover;border-radius:5px;border:.2px solid #eae1d7;">';
            }

            echo '<label style="display:block;cursor:pointer;margin:2px 0 0;">
            <input type="checkbox" name="sht_remove_media[]" value="' . $id . '" style="margin: -2px 0 0;"> Remove
          </label>';
            echo '</div>';
        }
        ?>
    </div>
    <p>
        <label style="font-size: 18px; margin: 10px 0 4px; display: block;"><strong><?php echo esc_html__('Upload New Media:', 'spa-hotel-toolkit'); ?></strong></label>
        <input type="file" name="sht_new_media[]" multiple accept="image/*,video/*">
    </p>

    <p>
        <label style="font-size: 18px; margin: 10px 0 4px; display: block;"><strong><?php echo esc_html__('Visit Date:', 'spa-hotel-toolkit'); ?></strong></label>

        <input type="text"
            name="sht_visit_date"
            id="sht_visit_date"
            class="tf-visit-date-input"
            value="<?php echo esc_attr($visit_date); ?>" readonly style="width:100%; background:#fff;">
    </p>


<?php
}

// Save the comment meta data
add_action('edit_comment', 'sht_save_comment_edit_fields');
function sht_save_comment_edit_fields($comment_id)
{
    if (
        !isset($_POST['sht_comment_edit_nonce_field']) ||
        !wp_verify_nonce($_POST['sht_comment_edit_nonce_field'], 'sht_comment_edit_nonce')
    ) {
        return;
    }

    // Save ratings
    if (!empty($_POST['sht_ratings'])) {
        $clean_ratings = [];
        foreach ($_POST['sht_ratings'] as $key => $value) {
            $clean_ratings[sanitize_key($key)] = $value;
        }
        update_comment_meta($comment_id, 'sht_ratings', $clean_ratings);
    }

    // Save visit date
    if (isset($_POST['sht_visit_date'])) {
        update_comment_meta($comment_id, 'sht_visit_date', sanitize_text_field($_POST['sht_visit_date']));
    }

    // Existing media
    $existing_media = get_comment_meta($comment_id, 'sht_review_media', true);
    if (!is_array($existing_media)) $existing_media = [];

    $new_media_uploaded = !empty($_FILES['sht_new_media']['name'][0]);

    // Remove all previous media 
    if ($new_media_uploaded) {
        foreach ($existing_media as $id) {
            wp_delete_attachment($id, true);
        }
        $existing_media = [];
    }

    // Remove selected media manually
    if (!empty($_POST['sht_remove_media'])) {
        foreach ($_POST['sht_remove_media'] as $remove_id) {
            wp_delete_attachment(intval($remove_id), true);
            $existing_media = array_diff($existing_media, [intval($remove_id)]);
        }
    }

    // Upload new media
    if ($new_media_uploaded) {
        $files = $_FILES['sht_new_media'];

        foreach ($files['name'] as $i => $name) {
            if ($files['error'][$i] === 0) {

                $_FILES['temp_file'] = [
                    'name'     => $files['name'][$i],
                    'type'     => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error'    => 0,
                    'size'     => $files['size'][$i],
                ];

                $upload_id = media_handle_upload('temp_file', 0);

                if (!is_wp_error($upload_id)) {
                    $existing_media[] = $upload_id;
                }
            }
        }
    }

    // Save final media list
    update_comment_meta($comment_id, 'sht_review_media', array_values($existing_media));
}


// Modify comment form to support file uploads
add_action('admin_footer', function () {
    if (get_current_screen()->id === 'comment') {
        echo '<script>
            document.getElementById("post").setAttribute("enctype", "multipart/form-data");
        </script>';
    }
});
