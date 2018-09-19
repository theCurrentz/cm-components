<?php
/////////////////////////////////////
// Add Custom Meta Boxes
/////////////////////////////////////

//Fire our meta box setup function on the post editor screen.
add_action( 'load-post.php', 'chromma_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'chromma_post_meta_boxes_setup' );

//meta box manifest
foreach(glob(plugin_dir_path( __FILE__ ) . "boxes/*.php") as $meta_box_interface) {
	require $meta_box_interface;
}

//Meta box setup function.
function chromma_post_meta_boxes_setup() {
	//Add meta boxes on the 'add_meta_boxes' hook.
	add_action( 'add_meta_boxes', 'chromma_add_post_meta_boxes' );
}

//searches for a match in post meta data to display checked
function chromma_is_checked($needle, $haystack) {
  echo ( in_array($needle, $haystack) ) ? 'checked' : '';
}

//Create meta boxes
function chromma_add_post_meta_boxes() {
  meta_box_ads_toggle::add_box();
  meta_box_featured_img_toggle::add_box();
  meta_box_format_options::add_box();
  meta_box_plag_warn::add_box();
  meta_box_arb_ad_options::add_box();
}

// Save the meta boxs' values as post metadata.
function chromma_save_meta( $post_id, $post ) {
  global $post;
  // verify meta box nonce (field is placed in "class-ad-toggle.php")
  if ( !isset( $_POST['chroma_nonce'] ) || !wp_verify_nonce( $_POST['chroma_nonce'], 'chroma_meta') )
    return;
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return;
  if ( !current_user_can( 'edit_post', $post->ID ) )
    return;
  //check posted values
  meta_box_ads_toggle::check_posted_values($post);
  meta_box_featured_img_toggle::check_posted_values($post);
  meta_box_format_options::check_posted_values($post);
  meta_box_arb_ad_options::check_posted_values($post);
}
add_action( 'save_post', 'chromma_save_meta', 10, 2 );

//remove unneccessary metaboxes from being queued up
function chroma_remove_metaboxs() {
  remove_meta_box( 'postexcerpt','post','normal' );
  remove_meta_box( 'trackbacksdiv','post','normal' );
  remove_meta_box( 'commentsdiv','post','normal' );
  remove_meta_box( 'commentstatusdiv','post','normal' );
  remove_meta_box( 'postcustom’','post','normal' );
  remove_meta_box( 'rocket_post_exclude','post','normal' );

  //removing menus
  remove_menu_page('link-manager.php');
}
add_action('admin_menu','chroma_remove_metaboxs');
