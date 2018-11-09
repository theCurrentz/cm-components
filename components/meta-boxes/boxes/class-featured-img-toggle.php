<?php
abstract class meta_box_featured_img_toggle {

  public static function add_box() {
    add_meta_box(
  		'chromma-featured-image',			// Unique ID
  		esc_html__( 'Featured Image Settings', 'chroma-text' ),		// Title
  		'meta_box_featured_img_toggle::display_box',		// Callback function
  		'post',					// Admin page (or post type)
  		'side',					// Context
  		'core'					// Priority
  	);
  }

  // Display the post meta box for ads toggling
  public static function display_box( $post ) {
  	//conditional logic for this functionality exists within  single.php, header.php, content-header.php and content-footer.php
    $selected = get_post_meta( $post->ID, 'chromma-featured-image', true );
    $embedCode = get_post_meta( $post->ID, 'embed-code', true );
    ?>
    <p>
      <label for="chromma-featured-image">
        Show / Hide Featured Image
      </label>
      <br />
      <select class="widefat" name="chromma-featured-image" id="chromma-featured-image">
        <option value="show" <?php selected( $selected, 'show' ); ?>>Show</option>
        <option value="hide" <?php selected( $selected, 'hide' ); ?>>Hide</option>
      </select>
      <label>Video Embed Override</label>
      <br />
      <textarea type="textarea" class="widefat" cols="30" rows="10" wrap="hard" name="embed-code" value="<?php echo trim(htmlentities(stripslashes($embedCode))); ?>"/>
        <?php echo trim(htmlentities(stripslashes($embedCode))); ?>
      </textarea>
    </p>
  <?php
  }

  //check posted values
  public static function check_posted_values( $post ) {
    if ( isset( $_POST['chromma-featured-image'] ) )
    	update_post_meta( $post->ID, 'chromma-featured-image', $_POST['chromma-featured-image']  );
    if ( isset( $_POST['embed-code'] ) )
      update_post_meta( $post->ID, 'embed-code', $_POST['embed-code']  );
  }
}
