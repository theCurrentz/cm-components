<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/*
Plugin Name:	Simple Table of Contents
Plugin URI: 	https:/github.com/timbral/
Description: 	A simple way to add a table of contents to a post, page or category by evaluating headers tags in content.
Author: 		Parker Westfall
Author URI: 	https:/github.com/timbral/
Text Domain:	simple-table-of-contents
Domain Path:	/languages
Version: 		1.0
License:		GPL2
*/

/*  Copyright 2018 Parker Westfall  (timbralpw@gmail.com)

*/
//enqueue script and styles
function enqueue_simple_table_of_contents_scripts() {
}

add_action('admin_enqueue_scripts', 'enqueue_simple_table_of_contents_scripts');

include( plugin_dir_path( __FILE__ ) . '/classes/table-of-contents.php');
include( plugin_dir_path( __FILE__ ) . '/includes/simple-table-of-contents-meta-box.php');

//rendering logic
function simple_table_of_contents_render($content)
{
  global $post;
  if (get_post_meta( $post->ID, 'stoc-simple_toc', true ) == "enabletoc")
  {
    $simple_toc = new table_of_contents($content);
    $toc_html = $simple_toc->get_toc_tree_html();
    $content = $simple_toc->get_processed_content();
    echo $toc_html;
	}
  return $content;
}
