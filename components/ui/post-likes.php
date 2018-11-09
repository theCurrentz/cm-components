<?php
//Like Button
add_action('wp_ajax_nopriv_post-like', 'post_like');
add_action('wp_ajax_post-like', 'post_like');
add_action('wp_ajax_nopriv_post_like_update', 'post_like_update');
add_action('wp_ajax_post_like_update', 'post_like_update');

function post_like_update() {
	$post_id = $_POST['post_id'];
	$love_count = get_post_meta($post_id, "votes_count", true);

			if(hasAlreadyVoted($post_id)) {
					echo $love_count .':true';
			} else {
					echo $love_count .':false';
				}
}

function post_like() {
    // Check for nonce security
    $nonce = $_POST['nonce'];

    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
        die ( 'Busted!');

    if(isset($_POST['post_like'])) {
        // Retrieve user IP address
        $ip = $_SERVER['REMOTE_ADDR'];
        $post_id = $_POST['post_id'];

        // Get voters'IPs for the current post
        $meta_IP = get_post_meta($post_id, "voted_IP");
        $voted_IP = $meta_IP[0];

        if(!is_array($voted_IP))
            $voted_IP = array();

        // Get votes count for the current post
        $love_count = get_post_meta($post_id, "votes_count", true);

        // Use has already voted ?
        if(!hasAlreadyVoted($post_id)) {
            $voted_IP[$ip] = time();

            // Save IP and increase votes count
            update_post_meta($post_id, "voted_IP", $voted_IP);
            update_post_meta($post_id, "votes_count", ++$love_count);

            // Display count (ie jQuery return value)
            echo $love_count;
        } else {
            echo "already";
				}
    exit;
 }
}
	$timebeforerevote = 12000;
	function hasAlreadyVoted($post_id) {
    global $timebeforerevote;

    // Retrieve post votes IPs
    $meta_IP = get_post_meta($post_id, "voted_IP");
    $voted_IP = $meta_IP[0];

    if(!is_array($voted_IP))
        $voted_IP = array();

    // Retrieve current user IP
    $ip = $_SERVER['REMOTE_ADDR'];

    // If user has already voted
    if(in_array($ip, array_keys($voted_IP))) {
        $time = $voted_IP[$ip];
        $now = time();

        // Compare between current time and vote time
        if(round(($now - $time) / 60) > $timebeforerevote)
            return false;

        return true;
    }

    return false;

	}
function getPostLikeLink($post_id) {
		$post_id = get_the_ID();
    $vote_count = get_post_meta($post_id, "votes_count", true);

    $output = '<div class="post-like">';
    if(hasAlreadyVoted($post_id)) {
        $output .= '<span class="heart" data-post_id="'.$post_id.'"><span title="I like this article" class="like alreadyvoted">
						<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			 			viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve" width="18px" height="18px">
								<path style="fill:#ec2e44;" d="M24.85,10.126c2.018-4.783,6.628-8.125,11.99-8.125c7.223,0,12.425,6.179,13.079,13.543
								c0,0,0.353,1.828-0.424,5.119c-1.058,4.482-3.545,8.464-6.898,11.503L24.85,48L7.402,32.165c-3.353-3.038-5.84-7.021-6.898-11.503
								c-0.777-3.291-0.424-5.119-0.424-5.119C0.734,8.179,5.936,2,13.159,2C18.522,2,22.832,5.343,24.85,10.126z"/>
						</svg>
				</span>';
    } else {
        $output .= '<span class="heart" data-post_id="'.$post_id.'">
                    <span  title="I like this article" class="qtip like">
											<svg class="heart-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 51.997 51.997" style="enable-background:new 0 0 51.997 51.997;" xml:space="preserve" width="18px" height="18px">
												 <path d="M51.911,16.242C51.152,7.888,45.239,1.827,37.839,1.827c-4.93,0-9.444,2.653-11.984,6.905   c-2.517-4.307-6.846-6.906-11.697-6.906c-7.399,0-13.313,6.061-14.071,14.415c-0.06,0.369-0.306,2.311,0.442,5.478   c1.078,4.568,3.568,8.723,7.199,12.013l18.115,16.439l18.426-16.438c3.631-3.291,6.121-7.445,7.199-12.014   C52.216,18.553,51.97,16.611,51.911,16.242z M49.521,21.261c-0.984,4.172-3.265,7.973-6.59,10.985L25.855,47.481L9.072,32.25   c-3.331-3.018-5.611-6.818-6.596-10.99c-0.708-2.997-0.417-4.69-0.416-4.701l0.015-0.101C2.725,9.139,7.806,3.826,14.158,3.826   c4.687,0,8.813,2.88,10.771,7.515l0.921,2.183l0.921-2.183c1.927-4.564,6.271-7.514,11.069-7.514   c6.351,0,11.433,5.313,12.096,12.727C49.938,16.57,50.229,18.264,49.521,21.261z" fill="#bdbdbd"/>
											</svg>
										</span>
                </span>';
							}
		if ($vote_count > 0) {
				$output .= '<span class="count">'.$vote_count.' Likes</span></div>';
			} else {
				$output .= '<span class="count">Like</span></div>';
			}
    return $output;
}
