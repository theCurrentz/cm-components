<?php

/*
Plugin Name: Parkers-TinyMCE-buttons
Author: Parker Westfall
Version: 2.0
*/

add_action('admin_head', 'chromapro_add_my_tc_button');

function chromapro_add_my_tc_button() {
    global $typenow;

    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
   	return;
    }

    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
        return;

	if ( get_user_option('rich_editing') == 'true') {
		add_filter('mce_external_plugins', 'chromapro_add_tinymce_plugin');
		add_filter('mce_buttons_3', 'chromapro_register_my_tc_button');
	}
}
function chromapro_add_tinymce_plugin($plugin_array) {
   	$plugin_array['chromapro_tc_button'] = plugins_url( '/parkersbutton.js', __FILE__ );
   	return $plugin_array;
}
function chromapro_register_my_tc_button($buttons) {
   array_push($buttons, 'coolquotes', 'dropcap', 'question', 'coolbutton', 'bubble', 'table', 'anchor', 'image-container', 'clear-html', 'gift-card', 'rating-card', 'add-unique-id', 'auto-correct');
   return $buttons;
}
//Editor Custom styles
function parks_editor_styles() {
  add_editor_style( plugins_url('/parks-buttons.css', __FILE__ ) );
  wp_enqueue_style( 'admin-css', plugins_url( '/parks-buttons.css', __FILE__ ), '', '', false );
}
add_action( 'admin_init', 'parks_editor_styles' );


//Remove some buttons from wordpres tinymce editor
include( plugin_dir_path( __FILE__ ) . '/remove-buttons.php');


add_filter( 'acf/fields/wysiwyg/toolbars' , 'chroma_toolbars'  );
function chroma_toolbars( $toolbars ) {

	// Add a new toolbar called "Very Simple"
	// - this toolbar has only 1 row of buttons
	$toolbars['Chroma Toolbar' ] = array();
  $toolbars['Chroma Toolbar' ][1] = $toolbars['full'][1];
	$toolbars['Chroma Toolbar' ][2] = array('coolquotes', 'dropcap', 'question', 'coolbutton', 'bubble', 'table', 'anchor', 'image-container');
	$toolbars['Chroma Toolbar' ][3] = array('clear-html', 'gift-card', 'rating-card', 'add-unique-id', 'auto-correct');

	// return $toolbars - IMPORTANT!
	return $toolbars;
}
