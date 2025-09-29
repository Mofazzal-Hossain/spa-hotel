<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// Add field to "Add New Location" form
add_action( 'hotel_location_add_form_fields', function() {
    ?>
    <div class="form-field">
        <label for="sht_location_featured"><?php _e( 'Featured Text', 'spa-hotel-toolkit' ); ?></label>
        <input type="text" name="sht_location_featured" id="sht_location_featured" value="" />
        <p class="description"><?php _e( 'Add a featured text for this location (e.g., Popular, Top Rated).', 'spa-hotel-toolkit' ); ?></p>
    </div>
    <?php
});


// Add field to "Edit Location" form
add_action( 'hotel_location_edit_form_fields', function( $term ) {
    $featured_text = get_term_meta( $term->term_id, 'sht_location_featured', true );
    ?>
    <tr class="form-field">
        <th scope="row"><label for="sht_location_featured"><?php _e( 'Featured Text', 'spa-hotel-toolkit' ); ?></label></th>
        <td>
            <input type="text" name="sht_location_featured" id="sht_location_featured" value="<?php echo esc_attr( $featured_text ); ?>" />
            <p class="description"><?php _e( 'Add a featured text for this location (e.g., Popular, Top Rated).', 'spa-hotel-toolkit' ); ?></p>
        </td>
    </tr>
    <?php
}, 10, 1 );


// Save Featured Text field
add_action( 'created_hotel_location', 'sht_save_hotel_location_meta' );
add_action( 'edited_hotel_location', 'sht_save_hotel_location_meta' );

function sht_save_hotel_location_meta( $term_id ) {
    if ( isset( $_POST['sht_location_featured'] ) ) {
        update_term_meta(
            $term_id,
            'sht_location_featured',
            sanitize_text_field( $_POST['sht_location_featured'] )
        );
    }
}
