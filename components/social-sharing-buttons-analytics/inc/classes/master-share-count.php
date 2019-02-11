<?php
/**
 * Master
 */
abstract class MasterShareCount implements Share_Counter {
	public static function get_share_count( $url ) {
    $post_id = url_to_postid( $url );
    $fbCheck = FacebookShareCount::get_share_count($url);
    $share_data = array(
      'fb' => (!empty($fbCheck["engagement"])) ? $fbCheck["engagement"]['share_count'] : 0,
      'fbr' => (!empty($fbCheck["engagement"])) ? $fbCheck["engagement"]['reaction_count'] : 0,
      'fbc' => (!empty($fbCheck["engagement"])) ?  $fbCheck["engagement"]["comment_count"] : 0,
      'tw' => intval(get_post_meta($post_id, '_twitter_click', true)),
      'flip' => intval(get_post_meta($post_id, '_flipboard_click', true)),
      'email' => intval(get_post_meta($post_id, '_email_click', true)),
      'copylink' => intval(get_post_meta($post_id, '_copy_link_click', true)),
      'comment' => intval(get_post_meta($post_id, '_comment_click', true)),
      'reddit' => intval(get_post_meta($post_id, '_reddit_click', true)),
      'whatsapp' => intval(get_post_meta($post_id, '_whatsapp_click', true)),
      'messenger' => intval(get_post_meta($post_id, '_messenger_click', true)),
      'linkedin' => intval(get_post_meta($post_id, '_linked_in_click', true)),
      'pinterest' => intval(get_post_meta($post_id, '_pinterest_click', true)),
      'pocket' => intval(get_post_meta($post_id, '_pocket_click', true)),
      'line' => intval(get_post_meta($post_id, '_line_click', true)),
      'print' => intval(get_post_meta($post_id, '_print_click', true)),
      'views' => intval(get_post_meta($post_id, 'post_views', true)),
      'more' => intval(get_post_meta($post_id, '_more_click', true)),
    );
    $share_data['total'] = array_sum($share_data) - $share_data['views'] - $share_data['more'];
    $share_stats =
    "<div class='chromaShareData'
      data-facebook='".$share_data['fb']."'
      data-facebookr='".$share_data['fbr']."'
      data-facebookc='".$share_data['fbc']."'
      data-twitter='".$share_data['tw']."'
      data-flipboard='".$share_data['flip']."'
      data-email='".$share_data['email']."'
      data-reddit='".$share_data['reddit']."'
      data-copylink='".$share_data['copylink']."'
      data-comment='".$share_data['comment']."'
      data-messenger='".$share_data['messenger']."'
      data-whatsapp='".$share_data['whatsapp']."'
      data-pinterest='".$share_data['pinterest']."'
      data-linkedin='".$share_data['linkedin']."'
      data-pocket='".$share_data['pocket']."'
      data-line='".$share_data['line']."'
      data-print='".$share_data['print']."'
      data-more='".$share_data['more']."'
      data-views='".$share_data['views']."'
      data-total='".$share_data['total']."'>
        <span>Views: ".$share_data['views']." </span> |
        <span>Engagment: ".$share_data['total']."</span>"
      ."</div>";
    return $share_stats;
	}
  public static function update_share_count_endpoint(WP_REST_Request $request) {
    $accepted_origins = array('https://idropnews.com','http://34.227.68.226');
    if(!in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins))
      return new WP_REST_Response('You are definitely not allowed to do this. Get out of here!', 403);

    if ( $request->get_param('share_type') && !empty($request->get_param('share_type')) ) {
      $post_id = $request->get_param('post_id');
      $share = "_" . strtolower(str_replace(" ", "_", $request->get_param('share_type')));
      $ogShareCount = intval(get_post_meta($post_id, $share));
      update_post_meta($post_id, $share, $ogShareCount + 1);
      return new WP_REST_Response($share, 200);
    }
  }
  public static function get_url_from_id(WP_REST_Request $request) {
    $accepted_origins = array('https://idropnews.com','http://34.227.68.226');
    if(!in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins))
      return new WP_REST_Response('You are definitely not allowed to do this. Get out of here!', 403);
      if ( $request->get_param('share_type') && !empty($request->get_param('share_type')) ) {
        $post_id = $request->get_param('post_id');
        $url = get_the_permalink($post_id);
        return new WP_REST_Response($url, 200);
      } else {
        return new WP_REST_Response('Not found', 400);
      }
  }
}
