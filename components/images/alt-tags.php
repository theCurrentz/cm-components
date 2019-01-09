<?php
//automatically generate alt text for images upon upload
add_action('add_attachment', 'auto_gen_alt_attr');
function auto_gen_alt_attr($post_ID) {
  //media file is an image?
  if (wp_attachment_is_image($post_ID)) {
    $selected_img_title = get_post($post_ID)->post_title;

    //sanitize by removing hyphens, underscores and extra spaces
    $selected_img_title = preg_replace( '%\s*[-_\s]+\s*%', ' ', $selected_img_title);
    //sanitize the title:  capitalize first letter of every word (other letters lower case):
		$selected_img_title = ucwords( strtolower( $selected_img_title ) );

    //modify array of image meta data
    $selected_img_meta = array(
      'ID'		=> $post_ID,			// Specify the image (ID) to be updated
      'post_title'	=> $selected_img_title,		// Set image Title to sanitized title
    );

    //set the image alt-text
    update_post_meta( $post_ID, '_wp_attachment_image_alt', $selected_img_title );

    //set the image meta (e.g. Title, Excerpt, Content)
    wp_update_post( $selected_img_meta );
  }
}

add_filter( 'wp_get_attachment_image_attributes','isa_add_img_title', 10, 2 );
//filter images to fill alt and title attributes if they are currently empty
function isa_add_img_title( $attr, $attachment = null ) {

    $img_title = trim( strip_tags( $attachment->post_title ) );

	   $attr['title'] = $img_title;

		if ( ($attr['alt'] == null) || ($attr['alt'] == '') ) {
			$attr['alt'] = $img_title;
		}

    return $attr;
}
