<?php
/*
* Sticky Ad Widget Extends WP_Widget_Custom_HTML
*
*/
class chroma_sticky_widget extends WP_Widget {
	function __construct() {
		$widget_options = array(
			'classname' => 'chroma_sticky_widget',
			'description' => 'Extended HTML widget with extra stickiness!',
		);
		// Instantiate the parent object
		parent::__construct( false, 'Sticky Widget');
	}
	//frontend output
	function widget($args, $instance) {

		extract( $args );

		$before_widget = '<section class="sticky">';
		$html = $instance['html'];
		$before_widget = '<section class="sticky">';
		if (!wp_is_mobile())
			echo $before_widget . stripslashes($html) . $after_widget;
	}
	//Backend Form
	public function form($instance) {
		//Get html
		if(isset($instance['html'])) {
			$html = $instance['html'];
		} else {
			$html = '';
		}
 ?>
		<p>
			<label for="<?php echo $this->get_field_id('html');?>">Input:</label>
			<input type="textarea" class="widefat" id="<?php echo $this->get_field_id('html');?>" name="<?php echo $this->get_field_name('html');?>" value="<?php echo htmlentities(stripslashes($html)); ?>">
		</p>
		<?php
	}
//Update widget values
 public function update($new_instance, $old_instance) {
	$instance = array();
	$instance['html'] = (!empty($new_instance['html'])) ? addslashes($new_instance['html']) : '';
	return $instance;
  }
}

function chroma_sticky_widget_registration() {
  register_widget('chroma_sticky_widget');
}
add_action('widgets_init', 'chroma_sticky_widget_registration');
