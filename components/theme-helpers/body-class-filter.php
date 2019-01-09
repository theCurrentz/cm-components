<?php
//body class filter
add_filter('body_class', 'category_to_single');
function category_to_single($classes) {
  if(is_single()) {
    global $post;
    foreach((get_the_category($post->ID)) as $category) {
      //add category slug to the $classes array
      $classes[] = $category->category_nicename;
    }
  }
  return $classes;
}
