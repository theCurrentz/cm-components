<?php
/////////////////////////////////////
// Add Custom Meta Box
/////////////////////////////////////

//Fire our meta box setup function on the post editor screen.
add_action( 'load-post.php', 'stoc_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'stoc_post_meta_boxes_setup' );

//Meta box setup function.
function stoc_post_meta_boxes_setup() {
	//Add meta boxes on the 'add_meta_boxes' hook.
	add_action( 'add_meta_boxes', 'stoc_add_post_meta_boxes' );
}

//searches for a match in post meta data to display checked
function stoc_is_checked($needle, $haystack)
{
  echo ( $needle == $haystack ) ? 'checked' : '';
}

//Create one or more meta boxes to be displayed on the post editor screen.
if ( !function_exists( 'stoc_add_post_meta_boxes' ) ) {
  function stoc_add_post_meta_boxes() {

    add_meta_box(
      'simple_toc',			// Unique ID
      'Simple ToC',		// Title
      'stoc_simple_toc_meta_box',		// Callback function
      'post',					// Admin page (or post type)
      'side',					// Context
      'core'					// Priority
    );
  }
}
// Display the post meta box for ads toggling
function stoc_simple_toc_meta_box( $post ) {
  wp_nonce_field( basename( __FILE__ ), 'stoc_simple_toc_nonce' );
  $selected = get_post_meta( $post->ID, 'stoc-simple_toc', true );
  ?>
  <p>
    <input type="checkbox" name="stoc_simple_toc" id="stoc_simple_toc" value="enabletoc" <?php stoc_is_checked('enabletoc', $selected); ?>>Enable Simple ToC
    <br />
  </p>
<?php  }

// Save the ad toggle meta box's post metadata.
function stoc_simple_toc_save_meta( $post_id, $post ) {
  global $post;
  // verify meta box nonce
  if ( !isset( $_POST['stoc_simple_toc_nonce'] ) || !wp_verify_nonce( $_POST['stoc_simple_toc_nonce'], basename( __FILE__ ) ) ) {
  	return;
  }

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
  		return;
  }

  if ( !current_user_can( 'edit_post', $post->ID ) ) {
   	return;
  }

  $simple_toc_checkbox_values = $_POST['stoc_simple_toc'];
  update_post_meta( $post->ID, 'stoc-simple_toc', $simple_toc_checkbox_values );

}
add_action( 'save_post', 'stoc_simple_toc_save_meta', 10, 2 );
