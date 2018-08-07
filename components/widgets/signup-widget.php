<?php

function chroma_register_signup_widget() {
  register_widget( 'signupWidget' );
}
add_action( 'widgets_init', 'chroma_register_signup_widget' );

class signupWidget extends WP_Widget {

  function __construct() {
    $widget_options = array(
      'classname' => 'signupWidget',
      'description' => 'Displays a newsletter signup box that logs entries in wp database.',
    );
    // Instantiate the parent object
    parent::__construct( false, 'Sign Up' );
  }

  function widget( $args, $instance ) {
     $title = apply_filters( 'widget_title', $instance[ 'title' ] );
		// Widget output
    echo '<div class="signup_sidebar">
    		<div class="ball">
    			<!-- sign up form with html validation -->
    			<form id="subscribe" class="signup_sidebar--form" method="post" action="'.site_url().'/form-processing/">
    				<label id="errorMessage" class="signup_sidebar--label">'.$style.'</label>
    				<input id="subscribeEmail" type="email" pattern="^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*$" name="email" required minlength="6" class="signup_sidebar--input" placeholder="you@site.com"></input>
    				<button type="submit" class="signup_sidebar--submit">
    					<span class="signup_sidebar--submit--span">Subscribe</span>
    				</button>
    			</form>
    		</div>
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
