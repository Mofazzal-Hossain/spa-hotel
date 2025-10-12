<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function sht_add_user_designation_field( $user ) {
    ?>
    <h3><?php echo esc_html__( "Additional Information", "spa-hotel-toolkit" ); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="designation"><?php echo esc_html__( "Designation", "spa-hotel-toolkit" ); ?></label></th>
            <td>
                <input type="text" name="designation" id="designation"
                       value="<?php echo esc_attr( get_the_author_meta( 'designation', $user->ID ) ); ?>"
                       class="regular-text" /><br/>
                <span class="description"><?php echo esc_html__( "Enter your designation.", "spa-hotel-toolkit" ); ?></span>
            </td>
        </tr>
    </table>
    <?php 
    wp_nonce_field( 'sht_save_designation_action', 'sht_designation_nonce' );
}
add_action( 'show_user_profile', 'sht_add_user_designation_field' );
add_action( 'edit_user_profile', 'sht_add_user_designation_field' );

function sht_save_user_designation_field( $user_id ) {
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    if ( ! isset( $_POST['sht_designation_nonce'] ) || 
         ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sht_designation_nonce'] ) ), 'sht_save_designation_action' ) ) {
        return false;
    }

    if ( isset( $_POST['designation'] ) ) {
        update_user_meta( $user_id, 'designation', sanitize_text_field( wp_unslash( $_POST['designation'] ) ) );
    }
}
add_action( 'personal_options_update', 'sht_save_user_designation_field' );
add_action( 'edit_user_profile_update', 'sht_save_user_designation_field' );
