<?php
add_action('plugins_loaded', 'chroma_error_handler');
function chroma_error_handler() {
  include plugin_dir_path( __FILE__ ) . 'inc/error-collector.php';
}

function error_reporting_enqueue() {
  //js versioning
  $jstime = filemtime( dirname(__FILE__) . '/js/error-catcher.js' );
  wp_enqueue_script( 'error-catcher', plugin_dir_url('chroma_wp_components') .
'chroma_wp_components/dist/errorcatcher.js', '', $jstime, false );
}
add_action( 'wp_enqueue_scripts', 'error_reporting_enqueue');

////async script loading function - very important, lets make sure we aren't bottle necking load by our master script
function error_reporter_async_scripts( $tag, $handle, $src ) {
    // the handles of the enqueued scripts we want to async
    $async_scripts = array( 'error-catcher' );

    if ( in_array( $handle, $async_scripts ) ) {
        return '<script async src="' . $src . '"></script>' . "\n";
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'error_reporter_async_scripts', 10, 3 );
