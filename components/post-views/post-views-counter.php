<?php
// Post Views Counter
if ( !function_exists( 'getPostViews' ) ) {
  function getPostViews($postID) {
      $count_key = 'post_views';
      $count = get_post_meta($postID, $count_key, true);
      if($count=='') {
          delete_post_meta($postID, $count_key);
          add_post_meta($postID, $count_key, '0');
          return "0 View";
      }
      return $count.' Views';
  }
}

if ( !function_exists( 'setPostViews' ) ) {
  function setPostViews($postID) {
      $count_key = 'post_views';
      $count = get_post_meta($postID, $count_key, true);
      if($count=='') {
          $count = 0;
          delete_post_meta($postID, $count_key);
          add_post_meta($postID, $count_key, '0');
      } else {
          $count++;
          update_post_meta($postID, $count_key, $count);
      }
  }
}

if ( !function_exists( 'post_views' ) ) {
  function post_views() {
  	$post_id = get_the_ID();
  	$count_key = 'post_views';
  	$n = get_post_meta($post_id, $count_key, true);
    if ($n > 999999) {
      //if millions of views
  		$n_format = number_format($n / 1000000, 1) . 'M';
      //if thosuands of views
  	} else if ($n > 999) {
          	$n_format = number_format($n / 1000, 1) . 'K';
  	} else {
  		$n_format = $n;
    }

  	echo $n_format;
  }
}
