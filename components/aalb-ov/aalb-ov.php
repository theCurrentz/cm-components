<?php
/*
* Override the default behavior of Amazon Affiliate Link Builder, by dequeueing their assets, appending oneLink.js(an amazon geo autolinking service) where necessary
*/

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

//be sure all logic is wrapped in this conditional
if( is_plugin_active('amazon-associates-link-builder/amazon-associates-link-builder.php') && !is_admin()) {

  function aabl_override_dequeue() {
    wp_dequeue_style( 'aalb_basics_css' );
  }
  add_action( 'wp_enqueue_scripts', 'aabl_override_dequeue', 100 );

  function amazon_one_link_scoper($content) {
    if ( strpos($content, 'amazon_link') ) {
      add_action('wp_footer', function() { return '<div id="amzn-assoc-ad-f5b799e6-abd0-4736-9052-69d5b390f261"></div><script async src="//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US&adInstanceId=f5b799e6-abd0-4736-9052-69d5b390f261"></script>'; }, 100 );
    }
    return $content;
  }
  add_filter('the_content', 'amazon_one_link_scoper', 1);
}
