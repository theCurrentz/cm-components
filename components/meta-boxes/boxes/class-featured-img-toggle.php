<?php
abstract class meta_box_featured_img {

  public static function add_box() {
    add_meta_box(
  		'chromma-featured-image',			// Unique ID
  		esc_html__( 'Featured Image Show/Hide', 'chroma-text' ),		// Title
  		'self::display_box',		// Callback function
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
    $selected = get_post_meta( $post->ID, 'chromma-featured-image', true );
    ?>
    <p>
      <label for="chromma-featured-image">
        <?php esc_html_e( "Select to show or hide the featured image from automatically displaying in this post.", 'chroma-text' ); ?>
      </label>
      <br />
      <br />
      <select class="widefat" name="chromma-featured-image" id="chromma-featured-image">
          <option value="show" <?php selected( $selected, 'show' ); ?>>Show</option>
          <option value="hide" <?php selected( $selected, 'hide' ); ?>>Hide</option>
      </select>
    </p>
  <?php
  }

  //check posted values
  public static function check_posted_values( $post ) {
    if ( isset( $_POST['chromma-featured-image'] ) ) {
    	update_post_meta( $post->ID, 'chromma-featured-image', $_POST['chromma-featured-image']  );
    }
  }
}
