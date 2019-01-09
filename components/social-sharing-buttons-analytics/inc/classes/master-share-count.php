<?php
/**
 * Master
 */
abstract class MasterShareCount implements Share_Counter {
	public static function get_share_count( $url ) {
    $post_id = url_to_postid( $url );
    $fbCheck = FacebookShareCount::get_share_count($url);
    $fbCheck = (!empty($fbCheck['error'])) ? get_post_meta($post_id, '_facebook_share', true) : FacebookShareCount::get_share_count($url)['engagement'];
    $share_data = array(
      'fb' => $fbCheck['share_count'],
      'fbr' => $fbCheck['reaction_count'],
      'fbc' => $fbCheck["comment_count"],
      'tw' => intval(get_post_meta($post_id, '_twitter_share', true)),
      'flip' => intval(get_post_meta($post_id, '_flipboard_share', true)),
      'email' => intval(get_post_meta($post_id, '_email_share', true)),
      'reddit' => intval(get_post_meta($post_id, '_reddit_share', true))
    );
    $share_data['total'] = array_sum($share_data);
    $share_span = "<span class='chromaShareData' data-facebook='".$share_data['fb']."' data-facebookr='".$share_data['fbr']."' data-facebookc='".$share_data['fbc']."' data-twitter='".$share_data['tw']."' data-flipboard='".$share_data['flip']."' data-email='".$share_data['email']."' data-reddit='".$share_data['reddit']."' data-total='".$share_data['total']."'>".$share_data['total']."</span>";
    return $share_span;
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
