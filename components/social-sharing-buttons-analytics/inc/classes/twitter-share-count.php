<?php
/**
 * Twitter Shares
 */
class TwitterShareCount implements Share_Counter {
	public static function get_share_count( $url ) {
		$tweetShare = intval(get_post_meta($post_id, '_twitter_share_count'));
		return $tweetShare;
	}
}
