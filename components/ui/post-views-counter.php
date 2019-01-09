<?php
//post views count
function getPostViews($postID) {
  $count_key = 'post_views';
  $count = get_post_meta($postID, $count_key, true);
  try {
    if($count=='') {
      delete_post_meta($postID, $count_key);
      add_post_meta($postID, $count_key, $count);
      return "";
    }
  } catch(Exception $e) {
    //suppressing the error
  }
  return $count.' Views';
}
function setPostViews($postID) {
  $count_key = 'post_views';
  $count = get_post_meta($postID, $count_key, true);
  if ($count=='') {
    $count = 0;
    delete_post_meta($postID, $count_key);
    add_post_meta($postID, $count_key, $count);
  } else {
    $count++;
    update_post_meta($postID, $count_key, $count);
  }
}
// Remove issues with prefetching adding extra views
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

function post_views() {
	$post_id = get_the_ID();
	$count_key = 'post_views';
	$n = get_post_meta($post_id, $count_key, true);
	if ($n > 999999999) {
		$n_format = number_format($n / 1000000000, 1) . 'B';
	} else if ($n > 999999) {
		$n_format = number_format($n / 1000000, 1) . 'M';
	} else if ($n > 999) {
        	$n_format = number_format($n / 1000, 1) . 'K';
	} else {
	   $n_format = $n;
  }
	echo $n_format;
}
