<?php
class FacebookShareCount implements Share_Counter {
	public static function get_share_count( $url ) {
    $post_id = url_to_postid( $url );
		$facebook_app_id = get_option('fb_api_key');
		$facebook_app_secret = get_option('fb_api_secret');
		$access_token = $facebook_app_id . '|' . $facebook_app_secret;
		$check_url = 'https://graph.facebook.com/v3.2/?id=' . urlencode(  $url ) . '&fields=engagement&access_token=' . $access_token;
		$response = wp_remote_retrieve_body( wp_remote_get( $check_url ) );
		$encoded_response = json_decode( $response, true );
		$share_info = $encoded_response;
    $datetime = (strtotime(get_the_modified_date('Y-m-d', $post_id)) > strtotime(get_the_date('Y-m-d', $post_id)))
      ? strtotime(get_the_modified_date('Y-m-d', $post_id))
      : strtotime(get_the_date('Y-m-d', $post_id));
    $yesterday = strtotime('-2 days');
    if (!empty($share_info['error']) || $datetime < $yesterday  ) {
      $share_info = get_post_meta($post_id, 'facebook_share_info', true);
      return (!empty($share_info)) ? $share_info : "";
    } else {
      update_post_meta($post_id, 'facebook_share_info', $share_info);
      return $share_info;
    }
	}

  public static function get_share_count_endpoint(WP_REST_Request $request) {
    $accepted_origins = array('https://idropnews.com','http://34.227.68.226');
    if(!in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins))
      return new WP_REST_Response('You are definitely not allowed to do this. Get out of here!', 403);

    if ( $request->get_param('share_url') && !empty($request->get_param('share_url')) ) {
      $share_info = $self->get_share_count($request->get_param('share_url'));
      return new WP_REST_Response($share_info, 200);
    }
  }
}
