<?php
/*
*  Ad Widget
*
*/
class ad_unit_widget extends WP_Widget {
	function __construct() {
		$widget_options = array(
			'classname' => 'ad_unit_widget',
			'description' => 'Extended HTML widget built specifically for ads!',
		);
		// Instantiate the parent object
		parent::__construct( false, 'Ad Unit Widget: Will not output on mobile devices');
	}
	//frontend output
	function widget($args, $instance) {

		extract( $args );

		$before_widget = '<section>';
		$html = $instance['html'];
		$after_widget = '</section>';
		if (!wp_is_mobile())
			echo stripslashes($html);
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

function ad_unit_widget_registration() {
  register_widget('ad_unit_widget');
}
add_action('widgets_init', 'ad_unit_widget_registration');
