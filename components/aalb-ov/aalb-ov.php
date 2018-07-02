<?php
/*
* Override the default behavior of Amazon Affiliate Link Builder, by dequeueing their assets. Necessary assets will be incorporated within "the build" via node.js
*/

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if( is_plugin_active('amazon-associates-link-builder/amazon-associates-link-builder.php') && !is_admin()) {
  function aabl_override_dequeue() {
    wp_dequeue_style( 'aalb_basics_css' );
  }
  add_action( 'wp_enqueue_scripts', 'aabl_override_dequeue', 100 );
}
