<?php

function chroma_register_connect_widget() {
  register_widget( 'connectWidget' );
}
add_action( 'widgets_init', 'chroma_register_connect_widget' );

class connectWidget extends WP_Widget {

  function __construct() {
    $widget_options = array(
      'classname' => 'connectWidget',
      'description' => 'Displays follow links for Facebook and Twitter.',
    );
    // Instantiate the parent object
    parent::__construct( false, 'Social Connect' );
  }

  function widget( $args, $instance ) {
     $title = apply_filters( 'widget_title', $instance[ 'title' ] );
		// Widget output
    echo '<span class="widget-title">'.$title.'</span>';
    echo '<div id="connect-widget" class="connect-widget">
      <a href="'. get_option( "facebook" ) .'" class="svg-container fb_svg" target="_blank" rel="noopener"><svg class="svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 112.196 112.196"><path fill="white" d="M70.201,58.294h-10.01v36.672H45.025V58.294h-7.213V45.406h7.213v-8.34 c0-5.964,2.833-15.303,15.301-15.303L71.56,21.81v12.51h-8.151c-1.337,0-3.217,0.668-3.217,3.513v7.585h11.334L70.201,58.294z"></path></svg>Like us on Facebook</a>
      <a href="'. get_option( "flipboard" ) .'" class="svg-container flip_svg" target="_blank" rel="noopener"><img class="flip_img" src="https://alltimelists.com/wp-content/uploads/2018/05/Logomark_DIGITAL_White_50X50-px.png"/>Follow us on Flipboard</a>
    </div>';
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
    $instance = $old_instance;
    $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
    return $instance;
	}

	function form( $instance ) {
    $title = ! empty( $instance['title'] ) ? $instance['title'] : ''; ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
    </p><?php
  }
}
