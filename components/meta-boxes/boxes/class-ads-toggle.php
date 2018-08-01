<?php
abstract class meta_box_ads_toggle {

  public static function add_box() {
    add_meta_box(
      'toggle-ads',			// Unique ID
      'Toggle Ads',		// Title
      'meta_box_ads_toggle::display_box',		// Callback function
      'post',					// Admin page (or post type)
      'side',					// Context
      'core'					// Priority
    );
  }

  // Display the post meta box for ads toggling
  public static function display_box( $post ) {
    //Include a nonce on the first meta box only
    wp_nonce_field( basename( __FILE__ ), 'chromma_nonce' );
  	//conditional logic for this functionality exists within  single.php, header.php, content-header.php and content-footer.php
    $selected = get_post_meta( $post->ID, 'chromma-toggle-ads', true );
    ?>
    <p>
      <label for="chromma-toggle-ads">
        Toggle all ads on or off
      </label>
      <br />
      <br />
      <select class="widefat" name="chromma-toggle-ads" id="chromma-toggle-ads">
          <option value="on" <?php selected( $selected, 'on' ); ?>>On</option>
          <option value="off" <?php selected( $selected, 'off' ); ?>>Off</option>
  				<option value="auto_adsense" <?php selected( $selected, 'auto_adsense' ); ?>>Auto Adsense</option>
      </select>
    </p>
  <?php
  }

  //check posted values
  public static function check_posted_values( $post ) {
    if ( isset( $_POST['chromma-toggle-ads'] ) ) {
      update_post_meta( $post->ID, 'chromma-toggle-ads', $_POST['chromma-toggle-ads'] );
    }
  }

}
