<?php
/*
 * Extend Recent Posts Widget
 *
 * Adds different formatting to the default WordPress Recent Posts Widget
 */
add_action( 'after_setup_theme', function () {
  function my_recent_widget_registration() {
   unregister_widget('WP_Widget_Recent_Posts');
   register_widget('My_Recent_Posts_Widget');
  }
  add_action('widgets_init', 'my_recent_widget_registration');

  Class My_Recent_Posts_Widget extends WP_Widget_Recent_Posts {

  	function widget($args, $instance) {

  		extract( $args );

  		$title = apply_filters('widget_title', empty($instance['title']) ? null : $instance['title'], $instance, $this->id_base);

  		if( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
  			$number = 10;

  		$r = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
  		if( $r->have_posts() ) :

  			if( $title != null ) echo $before_title . $title . $after_title; ?>
  			<div class="side_list">
  				<?php while( $r->have_posts() ) : $r->the_post(); ?>
            <a class="side_list__a" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
              <img class="side_list__img lazyload-img llreplace" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?php the_post_thumbnail_url('chroma-tiny')?>"/>
              <?php the_title('<span class="side_list__title">', '</span>'); ?>
            </a>
  				<?php endwhile; ?>
  			</div>

  			<?php
  		wp_reset_postdata();
  		endif;
  	}
  }
});
