<?php

//if it is not a single post, exit
if ( !is_single() ) {
  return;
}

//retrieve the global $post object
global $post;
//retrieve categories assosiated with the current post
$cat = wp_get_post_categories( $post->ID );
if ( ! $cat ) {
    return;
}

//query posts based on current posts primary category, date published
$args = array(
	'posts_per_page'   => 3,
	'offset'           => 0,
	'category'         => $cat[0],
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'post',
	'post_status'      => 'publish',
	'suppress_filters' => true,
  'post__not_in' => array ($post->ID),
);
$related_posts = get_posts( $args );

//Dynamic generate html list provided that related posts query is filled
if ($related_posts) { ?>
  <ol id="amp-related">
    <?php foreach($related_posts as $post)
    {
       setup_postdata($post); ?>
       <li class="related-li"><a id="related-link" href="<?php echo esc_url( amp_get_permalink( get_the_id() ) ); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
     <?php }
     wp_reset_postdata(); ?>
  </ol>
<?php } ?>
