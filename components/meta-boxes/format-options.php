<?php
/////////////////////////////////////
// Add Custom Meta Box
/////////////////////////////////////

//Fire our meta box setup function on the post editor screen.
add_action( 'load-post.php', 'chromma_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'chromma_post_meta_boxes_setup' );

//Meta box setup function.
function chromma_post_meta_boxes_setup() {

	//Add meta boxes on the 'add_meta_boxes' hook.
	add_action( 'add_meta_boxes', 'chromma_add_post_meta_boxes' );

}

//searches for a match in post meta data to display checked
function chromma_is_checked($needle, $haystack)
{
  echo ( in_array($needle, $haystack) ) ? 'checked' : '';
}

//Create one or more meta boxes to be displayed on the post editor screen.
if ( !function_exists( 'chromma_add_post_meta_boxes' ) ) {
  function chromma_add_post_meta_boxes() {
		
    add_meta_box(
      'format_options',			// Unique ID
      'Format Options',		// Title
      'chromma_format_options_meta_box',		// Callback function
      'post',					// Admin page (or post type)
      'side',					// Context
      'core'					// Priority
    );
  }
}
// Display the post meta box for ads toggling
function chromma_format_options_meta_box( $post ) {
  wp_nonce_field( basename( __FILE__ ), 'chromma_format_options_nonce' );
  $selected = get_post_meta( $post->ID, 'chromma-format_options', true );
  ?>
  <p>
    <input type="checkbox" name="chromma-format_options_1" id="chromma-format_options_1" value="Dark Mode" <?php chromma_is_checked('Dark Mode', $selected); ?>>Dark Mode
    <br />
    <input type="checkbox" name="chromma-format_options_2" id="chromma-format_options_2" value="No Sidebar" <?php chromma_is_checked('No Sidebar', $selected); ?>>No Sidebar
    <br />
  </p>
<?php  }

// Save the ad toggle meta box's post metadata.
function chromma_format_options_save_meta( $post_id, $post ) {
  global $post;
  // verify meta box nonce
  if ( !isset( $_POST['chromma_format_options_nonce'] ) || !wp_verify_nonce( $_POST['chromma_format_options_nonce'], basename( __FILE__ ) ) ) {
  	return;
  }

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
  		return;
  }

  if ( !current_user_can( 'edit_post', $post->ID ) ) {
   	return;
  }

  $format_options_checkbox_values = array($_POST['chromma-format_options_1'], $_POST['chromma-format_options_2']);
  update_post_meta( $post->ID, 'chromma-format_options', $format_options_checkbox_values );

}
add_action( 'save_post', 'chromma_format_options_save_meta', 10, 2 );
