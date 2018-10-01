<?php
//popular widget
// Register and load the widget
function popular_posts_load_widget() {
    register_widget( 'popular_posts' );
}
add_action( 'widgets_init', 'popular_posts_load_widget' );

// Creating the widget
class popular_posts extends WP_Widget {
  function __construct() {
    parent::__construct(

    // Base ID of your widget
    'popular_posts',

    // Widget name will appear in UI
    'Popular Posts',

    // Widget description
    array( 'description' => 'Displays the 5 most popular posts.' )
    );
  }

  // Creating widget front-end
  public function widget( $args, $instance ) {
    global $post;
    extract( $args );

    $title = apply_filters('widget_title', empty($instance['title']) ? null : $instance['title'], $instance, $this->id_base);
    $count = esc_attr($instance['count']) ? esc_attr($instance['count']) : 4;
    $daysAgo = esc_attr($instance['daysAgo']) ? esc_attr($instance['daysAgo']) : 4;
    $daysAgoInput = $daysAgo . " days ago";

    $popularPosts = new WP_Query(
      array (
      'posts_per_page' => $count,
      'orderby' => 'meta_value_num',
      'order' => 'DESC',
      'meta_key' => 'post_views',
      'category__not_in' => 1290,
      'post__not_in' => array(get_the_ID($post)),
      'date_query' => array( array( 'after' => $daysAgoInput ))
      )
    );

    if($popularPosts->have_posts()) {
      if(!empty($title)) {
        echo $args['before_title'] . $title .$args['after_title'];
      }
      echo '<div class="side_list">';
        	while ( $popularPosts->have_posts() ) {
        		$popularPosts->the_post(); ?>
            <a class="side_list__a" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
              <img class="side_list__img lazyload-img llreplace" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?php the_post_thumbnail_url('atl-tiny')?>"/>
              <!-- <div class="side_list__cat"><?php //echo get_the_category()[0]->name ?></div> -->
              <?php the_title('<span class="side_list__title">', '</span>'); ?>
            </a>
    <?php } // end while
  }
  wp_reset_postdata();
  echo '</div>';
  }

  //Backend Form
  public function form($instance) {
    //get & set title
    if (isset($instance['title'])) {
      $title = $instance['title'];
    } else {
      $title = 'Buzzing';
    }

    //Get count
    if(isset($instance['count'])) {
      $count = $instance['count'];
    } else {
      $count = 5;
    }

    //Get daysAgo
    if(isset($instance['daysAgo'])) {
      $daysAgo = $instance['daysAgo'];
    } else {
      $daysAgo = 5;
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
    <p>
      <label for="<?php echo $this->get_field_id('count');?>"># Days Ago</label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id('daysAgo');?>" name="<?php echo $this->get_field_name('daysAgo');?>" value="<?php echo esc_html__($daysAgo);?>">
    </p>
    <?php
  }
//Update widget values
  public function update($new_instance, $old_instance) {
    $instance = array();
    $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
    $instance['count'] = (!empty($new_instance['count'])) ? strip_tags($new_instance['count']) : '';
    $instance['daysAgo'] = (!empty($new_instance['daysAgo'])) ? strip_tags($new_instance['daysAgo']) : '';
    return $instance;
  }
}
