<?php
// Add Social Share Count Column
function social_share_columns_head($defaults) {
  $defaults['social_shares'] = 'Shares';
  return $defaults;
}

// SHOW THE FEATURED IMAGE
function social_share_columns_content($column_name, $post_ID) {
  if ($column_name == 'social_shares') {
    $datetime = (strtotime(get_the_modified_date('Y-m-d', $post_ID)) > strtotime(get_the_date('Y-m-d', $post_ID)))
      ? strtotime(get_the_modified_date('Y-m-d', $post_ID))
      : strtotime(get_the_date('Y-m-d', $post_ID));
    $yesterday = strtotime('-2 days');
    if ( $datetime > $yesterday ) {
      $url = str_replace('http://34.227.68.226', 'https://www.idropnews.com', get_the_permalink($post_ID));
      $shares = MasterShareCount::get_share_count($url);
      echo $shares;
    }
  }
}

add_filter('manage_posts_columns', 'social_share_columns_head');
add_action('manage_posts_custom_column', 'social_share_columns_content', 10, 2);
