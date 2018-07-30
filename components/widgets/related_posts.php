<?php
/*
 * Extend Recent Posts Widget
 *
 * Adds different formatting to the default WordPress Recent Posts Widget
 */

 // Exit if accessed directly.
 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

function related_posts_widget_registration() {
 //unregister_widget('WP_Widget_Recent_Posts');
 register_widget('chroma_related_posts');
}
add_action('widgets_init', 'related_posts_widget_registration');

class chroma_related_posts extends WP_Widget {

	//constructor
	function __construct() {
			parent::__construct(
					'chroma_related_posts', //Base ID
					'Chroma Related Posts',
					array(
							'description' => 'Display related posts.'
					)
			);
	}

//frontend output
	function widget($args, $instance) {

		extract( $args );

		$title = apply_filters('widget_title', empty($instance['title']) ? null : $instance['title'], $instance, $this->id_base);
		$count = esc_attr($instance['count']) ? esc_attr($instance['count']) : 4;

		$related = $this->chroma_get_related_posts( get_the_ID(), $count );
		if( $related->have_posts() ) {
			if(!empty($title)) {
				echo $args['before_title'] . $title .$args['after_title'];
			}
			?>
			<div class="side_list" id="related_content">
				<?php while( $related->have_posts() ) { $related->the_post(); ?>
					<a href="<?php the_permalink(); ?>" class="side_list__a">
						<img class="side_list__img lazyload-img llreplace" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?php the_post_thumbnail_url('atl-tiny')?>"/>
						<span class="side_list__title"><?php the_title(); ?></span>
					</a>
			<?php } ?>
		</div>
		<?php
		wp_reset_postdata();
		}
	}

	//Backend Form
	public function form($instance) {

		//get & set title
		if (isset($instance['title'])) {
			$title = $instance['title'];
		} else {
			$title = 'Related';
		}

		//Get count
		if(isset($instance['count'])){
				$count = $instance['count'];
		}else{
				$count = 6;
		}
 ?>
		<p>
			<label for="<?php echo $this->get_field_id('title');?>">Title</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" value="<?php echo esc_html__($title);?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('count');?>">Number of Posts</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('count');?>" name="<?php echo $this->get_field_name('count');?>" value="<?php echo esc_html__($count);?>">
		</p>
		<?php
	}
//Update widget values
  public function update($new_instance, $old_instance){
      $instance = array();
      $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
      $instance['count'] = (!empty($new_instance['count'])) ? strip_tags($new_instance['count']) : '';
      return $instance;
  }

	//related posts query
	function chroma_get_related_posts( $post_id, $related_count, $args = array() ) {
		$args = wp_parse_args( (array) $args, array(
			'orderby' => 'rand',
			'return'  => 'query', // Valid values are: 'query' (WP_Query object), 'array' (the arguments array)
		) );

		$related_args = array(
			'post_type'      => get_post_type( $post_id ),
			'posts_per_page' => $related_count,
			'post_status'    => 'publish',
			'post__not_in'   => array( $post_id ),
			'orderby'        => $args['orderby'],
			'tax_query'      => array()
		);

		$post       = get_post( $post_id );
		$taxonomies = get_object_taxonomies( $post, 'names' );

		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_the_terms( $post_id, $taxonomy );
			if ( empty( $terms ) ) {
				continue;
			}
			$term_list                   = wp_list_pluck( $terms, 'slug' );
			$related_args['tax_query'][] = array(
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $term_list
			);
		}

		if ( count( $related_args['tax_query'] ) > 1 ) {
			$related_args['tax_query']['relation'] = 'OR';
		}

		if ( $args['return'] == 'query' ) {
			return new WP_Query( $related_args );
		} else {
			return $related_args;
		}
	}
}
