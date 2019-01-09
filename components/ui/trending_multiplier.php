<?php
//trending
abstract class trending_multiplier {

  public static function set($post_id) {

    $current_date = strtotime(date('Y/m/d g:i:s A'));
    $posted_date = strtotime(get_the_date('Y/m/d g:i:s A', $post_id));
    $time_diff = round(($current_date - $posted_date) / 86400 );

    $wall_views = (float)get_post_meta(get_the_ID(), 'post_views')[0];

    $trending_multiplier = round($wall_views / $time_diff, 2);
    update_post_meta(get_the_ID(), 'trending_multiplier', $trending_multiplier);

  }

}
