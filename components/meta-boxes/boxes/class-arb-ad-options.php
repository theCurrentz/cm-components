<?php
abstract class meta_box_arb_ad_options {

  public static function add_box() {
    add_meta_box(
      'arb_ad_options',			// Unique ID
      'Arbitrage Ad Options',		// Title
      'meta_box_arb_ad_options::display_box',		// Callback function
      'post',					// Admin page (or post type)
      'normal',					// Context
      'low'					// Priority
    );
  }

  // Display the post meta box for ads toggling
  public static function display_box( $post ) {
    $selected = get_post_meta( $post->ID, 'chromma-arb-ad_options', true );
    $native_unit_1 = get_post_meta( $post->ID, 'chroma-native-unit-1', true );
    $native_unit_2 = get_post_meta( $post->ID, 'chroma-native-unit-2', true );
    ?>
    <p>
      <label><strong>Display Variant</strong></label>
      <select class="widefat" name="chromma-arb-ad" id="chromma-toggle-ads">
        <option value="none" <?php selected( $selected, 'none' ); ?>>None (No Units Displayed)</option>
        <option value="variation_1" <?php selected( $selected, 'variation_1' ); ?>>Variation 1 (Native Unit 1 Only)</option>
        <option value="variation_2" <?php selected( $selected, 'variation_2' ); ?>>Variation 2 (Native Unit 2 Only)</option>
        <option value="variation_3" <?php selected( $selected, 'variation_3' ); ?>>Variation 3 (Both Native Units)</option>
      </select>
      <br>
      <div id="chroma_tabs_controller" class="chroma_tabs_controller">
        <span href="#" class="is_active">Native Unit 1</span>
        <span href="#">Native Unit 2</span>
      </div>
      <ul class="chroma_tabs">
        <li class="chroma_tabs_box first is_active">
          <textarea type="textarea" class="widefat" cols="50" rows="5" wrap="hard" name="chroma-native-unit-1" value="<?php echo htmlentities ( stripslashes($native_unit_1) ); ?>"><?php echo htmlentities ( stripslashes($native_unit_1) ); ?></textarea>
        </li>
        <li class="chroma_tabs_box">
          <textarea type="textarea" class="widefat" cols="50" rows="5" wrap="hard" name="chroma-native-unit-2" value="<?php echo htmlentities ( stripslashes($native_unit_2) ); ?>"><?php echo htmlentities ( stripslashes($native_unit_2) ); ?></textarea>
        </li>
      </ul>
    </p>
  <?php
  }

  //check posted values
  public static function check_posted_values( $post ) {
    if ( isset( $_POST['chromma-arb-ad'])) {
      update_post_meta( $post->ID, 'chromma-arb-ad_options', $_POST['chromma-arb-ad'] );
    }
    else {
      update_post_meta( $post->ID, 'chromma-arb-ad_options', null );
    }
    if ( isset( $_POST['chroma-native-unit-1']) && strlen($_POST['chroma-native-unit-1']) > 0 ) {
      update_post_meta( $post->ID, 'chroma-native-unit-1', $_POST['chroma-native-unit-1'] );
    } else {
      update_post_meta( $post->ID, 'chroma-native-unit-1', null );
    }
    if ( isset( $_POST['chroma-native-unit-2']) && strlen($_POST['chroma-native-unit-2']) > 0 ) {
      update_post_meta( $post->ID, 'chroma-native-unit-2', $_POST['chroma-native-unit-2'] );
    } else {
      update_post_meta( $post->ID, 'chroma-native-unit-2', null );
    }
  }

}
