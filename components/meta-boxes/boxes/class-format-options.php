<?php
abstract class meta_box_format_options {

  public static function add_box() {
    add_meta_box(
      'format_options',			// Unique ID
      'Format Options',		// Title
      'meta_box_format_options::display_box',		// Callback function
      'post',					// Admin page (or post type)
      'side',					// Context
      'core'					// Priority
    );
  }

  // Display the post meta box for ads toggling
  public static function display_box( $post ) {
    $selected = get_post_meta( $post->ID, 'chromma-format_options', true );
    ?>
    <p>
      <input type="checkbox" name="chromma-format_options_1" id="chromma-format_options_1" value="Dark Mode" <?php chromma_is_checked('Dark Mode', $selected); ?>>Dark Mode
      <br />
      <input type="checkbox" name="chromma-format_options_2" id="chromma-format_options_2" value="No Sidebar" <?php chromma_is_checked('No Sidebar', $selected); ?>>No Sidebar
      <br />
    </p>
  <?php
  }

  //check posted values
  public static function check_posted_values( $post ) {
    if ( isset( $_POST['chromma-format_options_1']) || isset($_POST['chromma-format_options_2'] ) ) {
      $format_options_checkbox_values = array($_POST['chromma-format_options_1'], $_POST['chromma-format_options_2']);
      update_post_meta( $post->ID, 'chromma-format_options', $format_options_checkbox_values );
    }
    else {
      update_post_meta( $post->ID, 'chromma-format_options', null );
    }
  }

}
