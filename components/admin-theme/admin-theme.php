<?php
/**
 * Plugin Name: Chroma Admin Theme
 * Plugin URI: http://wordpress.org/plugins/admin-color-schemes/
 * Description: Even more admin color schemes
 * Version: 2.2
 * Author: Parker Westfall
 * Author URI: http://wordpress.org/
 * Text Domain: admin_schemes
 * Domain Path: /languages
 */

class chroma_admin_theme {

	/**
	 * List of colors registered in this plugin.
	 *
	 * @since 1.0
	 * @access private
	 * @var array $colors List of colors registered in this plugin.
	 *                    Needed for registering colors-fresh dependency.
	 */
	private $colors = array(
		'vinyard', 'primary', '80s-kid', 'aubergine',
		'cruise', 'flat', 'lawn', 'seashore'
	);

	function __construct() {
		add_action( 'admin_init' , array( $this, 'add_colors' ) );
	}

	/**
	 * Register color schemes.
	 */
	function add_colors() {
    wp_admin_css_color(
      'chroma', __( 'chroma', 'admin_schemes' ),
      plugins_url( "chroma/colors.css", __FILE__ ),
      array( '#000000', '#000000', '#000000', '#000000' ),
      array( 'base' => '#000000', 'focus' => '#000000', 'current' => '#000000' )
    );
	}
}
global $acs_colors;
$acs_colors = new chroma_admin_theme;

function force_default_admin_theme( $result ) {
    return 'chroma';
}
add_filter( 'get_user_option_admin_color', 'force_default_admin_theme' );
