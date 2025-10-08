<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// 1. Add Designation field to user profile edit page
function sht_add_user_designation_field($user) {
    ?>
    <h3><?php echo esc_html__("Additional Information", "spa-hotel-toolkit"); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="designation"><?php echo esc_html__("Designation", "spa-hotel-toolkit"); ?></label></th>
            <td>
                <input type="text" name="designation" id="designation"
                       value="<?php echo esc_attr(get_the_author_meta('designation', $user->ID)); ?>"
                       class="regular-text"/><br/>
                <span class="description"><?php echo esc_html__("Enter the your designation.", "spa-hotel-toolkit"); ?></span>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'sht_add_user_designation_field');  
add_action('edit_user_profile', 'sht_add_user_designation_field');


// 2. Save Designation field
function sht_save_user_designation_field($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    update_user_meta($user_id, 'designation', sanitize_text_field($_POST['designation']));
}
add_action('personal_options_update', 'sht_save_user_designation_field');
add_action('edit_user_profile_update', 'sht_save_user_designation_field');
