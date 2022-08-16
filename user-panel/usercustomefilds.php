<?php 
// Add Custome fild for user
add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

function my_show_extra_profile_fields( $user ) { ?>
<h3>Shop information</h3>
<table class="form-table">
    <tr>
        <th><label for="shop-logo">Shop logo</label></th>
        <td><input type="file" name="picture" value="" />

            <?php 
                $r = get_user_meta( $user->ID, 'picture', true );
               //print_r($r); 
                if (!isset($r['error'])) {
                    $r = $r['url'];
                    echo "<img src='$r' />";
                } else {
                    $r = $r['error'];
                    echo $r;
                }
            ?>
        </td>
    </tr>
    <tr>
        <th><label for="shop-name">Shop name</label></th>
        <td>
            <input type="text" name="shop-name" id="shop-name" value="<?php echo esc_attr( get_the_author_meta( 'shop-name', $user->ID ) ); ?>" class="regular-text" /><br />
        </td>
    </tr>
    <tr>
        <th><label for="shop-url">Shop url</label></th>
        <td>
            <input type="url" name="shop-url" id="shop-url" value="<?php echo esc_attr( get_the_author_meta( 'shop-url', $user->ID ) ); ?>" class="regular-text" /><br />
        </td>
    </tr>
    <tr>
        <th><label for="shop-desc">Shop descciption</label></th>
        <td>
            <textarea type="textarea" name="shop-desc" id="shop-desc" rows="5" cols="30" class="regular-text"><?php echo esc_attr( get_the_author_meta( 'shop-desc', $user->ID ) ); ?></textarea><br />
        </td>
    </tr>
    <tr>
        <th>
            <h3>REST API</h3>
            <label for="customer-key">Customer key</label>
        </th>
        <td>
            <input type="textarea" name="customer-key" id="customer-key" rows="5" cols="30" value="<?php echo esc_attr( get_the_author_meta( 'customer-key', $user->ID ) ); ?>" class="regular-text" /><br />
        </td>
    </tr>
    <tr>
        <th><label for="private-key">Private key</label></th>
        <td>
            <input type="textarea" name="private-key" id="private-key" rows="5" cols="30" value="<?php echo esc_attr( get_the_author_meta( 'private-key', $user->ID ) ); ?>" class="regular-text" /><br />
        </td>
    </tr>
</table>

<?php }
// save fields
add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

function my_save_extra_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;
	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_user_meta( $user_id, 'shop-logo', $_POST['shop-logo'] );
    update_user_meta( $user_id, 'shop-name', $_POST['shop-name'] );
    update_user_meta( $user_id, 'shop-url', $_POST['shop-url'] );
    update_user_meta( $user_id, 'shop-desc', $_POST['shop-desc'] );
    
    update_user_meta( $user_id, 'customer-key', $_POST['customer-key'] );
    update_user_meta( $user_id, 'private-key', $_POST['private-key'] );

    $_POST['action'] = 'wp_handle_upload';
$r = wp_handle_upload( $_FILES['picture'] );
update_user_meta( $user_id, 'picture', $r, get_user_meta( $user_id, 'picture', true ) );

}

add_action('user_edit_form_tag', 'make_form_accept_uploads');
function make_form_accept_uploads() {
    echo ' enctype="multipart/form-data"';
}
