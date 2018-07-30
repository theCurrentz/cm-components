<?php
function admin_assistant_front_end() {
  if ( current_user_can('editor') || current_user_can('administrator') || is_admin() ) {
    wp_enqueue_script( 'admin-assistant', plugins_url( '/assets/js/admin-assistant.js' , __FILE__ ), '', '', true);
    wp_enqueue_style( 'admin-css', plugins_url( '/assets/css/admin-assistant.css', __FILE__ ), '', '', false );
  }
}
function admin_assistant_back_end() {
  if ( current_user_can('editor') || current_user_can('administrator') || is_admin() ) {
    wp_enqueue_script( 'admin-assistant', plugins_url( '/assets/js/admin-assistant.js' , __FILE__ ), '', '', true);
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
