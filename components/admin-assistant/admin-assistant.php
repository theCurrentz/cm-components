<?php
function admin_assistant_front_end() {
  if ( current_user_can('editor') || current_user_can('administrator') || is_admin() ) {
    wp_enqueue_script( 'admin-assistant', plugins_url( '/assets/js/admin-assistant.js' , __FILE__ ), '', '', true);
    wp_enqueue_style( 'admin-css', plugins_url( '/assets/css/admin-assistant.css', __FILE__ ), '', '', false );
  }
}
function admin_assistant_back_end() {
  if ( current_user_can('editor') || current_user_can('administrator') || is_admin() ) {
    wp_enqueue_style( 'admin-assistant-css', plugins_url( '/assets/css/admin-assistant.css', __FILE__ ), '', '', false );
    wp_enqueue_script( 'admin-assistant', plugins_url( '/assets/js/admin-assistant.js' , __FILE__ ), '', '', true);
    $properNouns = explode(PHP_EOL,get_option('proper_nouns'));
    for($i = 0; $i < count($properNouns); $i++) {
      $properNouns[$i] = str_replace("\r", '', $properNouns[$i]);
    }
    wp_localize_script('admin-assistant', 'properNouns', $properNouns);
  }
}
add_action( 'wp_enqueue_scripts', 'admin_assistant_front_end' );
add_action( 'admin_enqueue_scripts', 'admin_assistant_back_end' );

//convert admin script to a module
function module_formatter($tag, $handle, $src) {
  if ( $handle === 'admin-assistant' ) {
    return '<script type="module" src="' . $src . '"></script>';
  } else
    return $tag;
}
add_filter( 'script_loader_tag', 'module_formatter', 10, 3 );

//remove menu items from admin bar
function remove_admin_icons( $wp_admin_bar ) {
  $nodeArray = array("ai-toolbar-settings", 'comments', 'updates', 'customize', 'wp-logo', 'search');
  foreach ($nodeArray as $node)
	 $wp_admin_bar->remove_node( $node );
}
add_action( 'admin_bar_menu', 'remove_admin_icons', 999 );

//Editor Custom styles
function wpdocs_theme_add_editor_styles() {
  add_editor_style( plugins_url('/assets/css/editor-style.css', __FILE__ ) );
}
add_action( 'admin_init', 'wpdocs_theme_add_editor_styles' );

/* Disable Admin Bar for non admins */
function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
    show_admin_bar(false);
	}
}
add_action('after_setup_theme', 'remove_admin_bar');
