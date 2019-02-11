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

//rename dashboard menu
add_action('admin_menu', 'rename_dashboard', 999);
function rename_dashboard() {
	global $menu;
	// Pinpoint menu item
	$pick = recursive_array_search_php_91365( 'Dashboard', $menu );
	$menu[$pick][0] = get_bloginfo('name');
}

// http://www.php.net/manual/en/function.array-search.php#91365
function recursive_array_search_php_91365( $needle, $haystack )
{
    foreach( $haystack as $key => $value )
    {
        $current_key = $key;
        if(
            $needle === $value
            OR (
                is_array( $value )
                && recursive_array_search_php_91365( $needle, $value ) !== false
            )
        )
        {
            return $current_key;
        }
    }
    return false;
}

function disable_default_dashboard_widgets() {
	global $wp_meta_boxes;
	// wp..
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
	// yoast seo
	unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);
}
remove_action( 'welcome_panel', 'wp_welcome_panel' );
add_action('wp_dashboard_setup', 'disable_default_dashboard_widgets', 999);
remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );

function helloMessage() {
	$user = wp_get_current_user();
	echo "<h1 style='font-size: 2rem;font-weight: 800;line-height: 1.35;'>Hello ".$user->display_name."</h1>";
  if(is_admin()) {
    chroma_get_client_errors();
  }
}
add_action( 'welcome_panel', 'helloMessage' );
function chroma_get_client_errors() {
  echo "<h3>Today's Client Side Errors:</h3>";
  try {
    //initialize server sql connection variables
    $servername = DB_HOST;
    $username = DB_USER;
    $password = DB_PASSWORD;
    $db_name = DB_NAME;

    $conn = new mysqli($servername, $username, $password, $db_name);
    // Check connection
    if ($conn->connect_error)
      die("Connection failed: " . $conn->connect_error);

    //prepare errors from database table
    $error_msg_query = "SELECT error_msg FROM chromaErrors";
    $error_msg = $conn->query($error_msg_query);
    $body_stmt = '';
    if ($error_msg->num_rows > 0) {
      $i = 0;
      while( ($row = $error_msg->fetch_assoc()) && ($i < 15) ) {
        // output data of each row
        $body_stmt .= $row['error_msg'].'<hr>';
        $i++;
      }
    }
    if($body_stmt != '') {
      echo $body_stmt;
    } else {
      echo "<span style='color: green; font-weight: 700;'>There are no client side errors. Good Job!</span>";
    }

  //if date on most recent row is > 1 day before
  //clear rows
  $conn->query("DELETE FROM chromaErrors WHERE date_err < DATE_ADD(curdate(),INTERVAL -1 day)");
  $conn->close();
  } catch(Exception $e) {
    echo $e->getMessage();
  }
}

//implement reddit trending topic api
//https://www.reddit.com/r/Apple/top/.json?limit=10
