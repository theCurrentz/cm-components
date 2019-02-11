<?php
// Add Social Share Count Column
function social_share_columns_head($defaults) {
  $defaults['social_shares'] = 'Engagement';
  return $defaults;
}

function social_share_columns_content($column_name, $post_ID) {
  if ($column_name == 'social_shares') {
      $url = get_the_permalink($post_ID);
      $shares = MasterShareCount::get_share_count($url);
      echo $shares . "<span class='social-button'>See Stats</span>";
  }
}

add_filter('manage_posts_columns', 'social_share_columns_head');
add_action('manage_posts_custom_column', 'social_share_columns_content', 10, 2);
