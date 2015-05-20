<?php
/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function myplugin_add_meta_box() {

	$screens = array( 'post', 'page' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'myplugin_sectionid',
			__( 'Background Cover', 'myplugin_textdomain' ),
			'coverbg_meta_box_callback',
			$screen
		);
	}
}
add_action( 'add_meta_boxes', 'myplugin_add_meta_box' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function coverbg_meta_box_callback( $post ) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'coverbg_meta_box', 'coverbg_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = get_post_meta( $post->ID, 'coverbg', true );

	echo '<label for="coverbginput">';
	_e( 'Current backgrounds', 'myplugin_textdomain' );
	echo '</label> ';
	echo '<input type="text" class="regular-text" id="coverbginput" name="coverbginput" value="' . esc_attr( $value ) . '" size="25" />';
	
	
	$path = '../wp-content/gallery/cover/thumbs/';

	clearstatcache();

	echo '<p style="font-style:italic">Covers are in ' . $path . ' </p>';
	$d = dir($path);
	while (false !== ($entry = $d -> read())) {
		$filepath = "{$path}/{$entry}";
		//Check whether the entry is a file etc.:
		if (is_file($filepath)) {
			$latest_filename = $entry;
			$file_name = basename($filepath);
			$name = basename($filepath, '.jpg');
			$file_type = filetype($filepath);
			//get file type.
			$file_size = filesize($filepath);
			//get file size.
			echo "<img class=\"coverthumb\" src='../wp-content/gallery/cover/thumbs/$file_name' name=" . $name . ">";
		}//end if is file etc.
	}//end while going over files in uploads dir.
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function myplugin_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['coverbg_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['coverbg_meta_box_nonce'], 'coverbg_meta_box' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */
	
	// Make sure that it is set.
	if ( ! isset( $_POST['coverbginput'] ) ) {
		return;
	}

	// Sanitize user input.
	$my_data = sanitize_text_field( $_POST['coverbginput'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, 'coverbg', $my_data );
}
add_action( 'save_post', 'myplugin_save_meta_box_data' );
?>


