<?php
/*
Plugin Name: AMP Custom Layer
Author: Parker Westfall
Description: This plugin builds on top of the AMP plugin; adding additional functionality and styling.
Version: 1.0
*/

// Remove google font loading
add_action( 'amp_post_template_head', 'isa_remove_amp_google_fonts', 2 );
function isa_remove_amp_google_fonts() {
    remove_action( 'amp_post_template_head', 'amp_post_template_add_fonts' );
}

//Disable Customizer on WP backend
add_filter( 'amp_customizer_is_enabled', '__return_false' );\

// Set a custom template file
add_filter( 'amp_post_template_file', 'chroma_amp_set_custom_template', 10, 3 );

function chroma_amp_set_custom_template( $file, $type, $post ) {
	if ( 'single' === $type ) {
		$file = dirname( __FILE__ ) . '/templates/parkers-amp-template.php';
	}
	return $file;
}

// Add in google analytics support
if (get_option('googleanalytics')) {
  add_filter( 'amp_post_template_analytics', 'chroma_amp_add_custom_analytics' );
  function chroma_amp_add_custom_analytics( $analytics ) {
  	if ( ! is_array( $analytics ) ) {
  		$analytics = array();
  	}

  	// https://developers.google.com/analytics/devguides/collection/amp-analytics/
  	$analytics['chroma-googleanalytics'] = array(
  		'type' => 'googleanalytics',
  		'attributes' => array(
  			// 'data-credentials' => 'include',
  		),
  		'config_data' => array(
  			'vars' => array(
  				'account' => get_option('googleanalytics')
  			),
  			'triggers' => array(
  				'trackPageview' => array(
  					'on' => 'visible',
  					'request' => 'pageview',
  				),
  			),
  		),
  	);

  	return $analytics;
  }
}

if (get_option('schemaurl'))
{
  // Change AMP Schema data
  add_filter( 'amp_post_template_metadata', 'chroma_amp_modify_json_metadata', 99, 2 );

  function chroma_amp_modify_json_metadata( $metadata, $post ) {

    //get the logo set by customizer
    $schema_url = get_option('schemaurl');
    //metadata from schema url
    list($schema_width, $schema_height, $schema_type, $schema_attr) = getimagesize($schema_url);

    if (has_category('News')){ $type = 'NewsArticle'; } else { $type = 'Article'; }

  	$metadata['@type'] = $type;
  	$metadata['publisher']['logo'] = array(
  		'@type' => 'ImageObject',
  		'url' => $schema_url,
  		'height' => $schema_height,
  		'width' => $schema_width,
  	);

  	return $metadata;
  }
}

function chroma_amp_add_ad_sanitizer( $sanitizer_classes, $post ) {
	include( dirname( __FILE__ ) . '/classes/chroma-amp-ad-injection-sanitizer.php' );
	$sanitizer_classes[ 'Chroma_Amp_Ad_Injection_Sanitizer' ] = array(); // the array can be used to pass args to your sanitizer and accessed within the class via `$this->args`
	return $sanitizer_classes;
}

//Inherited Sanitizer filter and hook to add ads to amp content
add_filter( 'amp_content_sanitizers', 'chroma_amp_add_ad_sanitizer', 10, 2 );

//override CSS
add_filter( 'amp_post_template_file', 'chroma_amp_set_custom_css', 10, 3 );

function chroma_amp_set_custom_css( $file, $type, $post ) {
	if ( 'style' === $type ) {
		$file = dirname( __FILE__ ) . '/amp/style.php';
	}
	return $file;
}

//options panel
include( plugin_dir_path( __FILE__ ) . '/includes/custom_amp_admin_page.php');

//sanitizer helper function for custom fields
// function custom_amp_content_sanitized( $data ) {
//   $amp_content =
//   new AMP_Content(
//     $data,
//     apply_filters(
//       'amp_content_sanitizers',
//       array(
//         'AMP_Blacklist_Sanitizer' => array(),
//         'AMP_Img_Sanitizer' => array(),
//         'AMP_Video_Sanitizer' => array(),
//         'AMP_Audio_Sanitizer' => array(),
//         'AMP_Iframe_Sanitizer' => array( 'add_placeholder' => true, ),
//       )
//     )
//   );
//   return $amp_content->get_amp_content();
// }
