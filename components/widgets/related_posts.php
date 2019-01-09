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
  public function update($new_instance, $old_instance) {
      $instance = array();
      $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
      $instance['count'] = (!empty($new_instance['count'])) ? strip_tags($new_instance['count']) : '';
      return $instance;
  }

	//related posts query
	function chroma_get_related_posts( $post_id, $related_count ) {

    //fields
    $LIMIT = $related_count;
    $remainder = $LIMIT;
    $count = 0;
    $matched_posts = array();

    //focus keyword matching
    $focus_keyword = get_post_meta($post_id, '_yoast_wpseo_focuskw', true);
    $focus_args = array(
      'post_type'      => get_post_type( $post_id ),
			'posts_per_page' => $LIMIT,
			'post_status'    => 'publish',
			'post__not_in'   => array( $post_id ),
			'orderby'        => 'date',
			'meta_key' => '_yoast_wpseo_focuskw',
      'meta_value' => $focus_keyword
    );
    $focus_query = new WP_Query($focus_args);
    if ($focus_query->have_posts()) {
      while ($focus_query->the_posts()) {
        array_push($matched_posts, $focus_query->the_posts());
        $count++;
      }
      wp_reset_postdata();
    }
    $remainder = $remainder - $count;
    if ($count >= $LIMIT)
      return $matched_posts;

    //tag matching
    $tags = wp_get_post_tags($post_id);
    $tag_args = array(
      'post_type'      => get_post_type( $post_id ),
			'posts_per_page' => $remainder,
			'post_status'    => 'publish',
			'post__not_in'   => array( $post_id ),
			'orderby'        => 'date',
			'tag' => $tags
    );
    $tag_query = new WP_Query($tag_args);
    if ($tag_query->have_posts()) {
      while ($tag_query->the_posts()) {
        array_push($matched_posts, $tag_query->the_posts());
        $count++;
      }
      wp_reset_postdata();
    }
    $remainder = $remainder - $count;
    if ($remainder <= 0)
      return $matched_posts;

    //category matching
    $cats = wp_get_post_categories($post_id);
    $cat_args = array(
      'post_type'      => get_post_type( $post_id ),
			'posts_per_page' => $remainder,
			'post_status'    => 'publish',
			'post__not_in'   => array( $post_id ),
			'orderby'        => 'date',
			'category_name' => $cats
    );
    $cat_query = new WP_Query($cat_args);
    if ($cat_query->have_posts()) {
      while ($cat_query->the_posts()) {
        array_push($matched_posts, $cat_query->the_posts());
        $count++;
      }
      wp_reset_postdata();
    }

    return $matched_posts;
	}

}
