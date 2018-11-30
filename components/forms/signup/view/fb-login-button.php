<?php
//$fbLoginDefault = new fb_login_button();
class fb_login_button {
  function __construct($config = array(
    'context' => null,
    'msg' => 'Facebook Login'
  )) {
    global $page, $post, $multipage;
    $next_page = ($multipage) ? get_the_permalink($post->ID) . ($page + 1) . '/' : null;
    $context = (!empty($config['context'])) ? $context = " " . $config['context'] : $config['context'];
    $msg = $config['msg'];
    echo "<button class='btn facebook-share$context fb-arrow' data-next='$next_page'><span class='svg-container fb_svg'></span>$msg</button>";
  }
}
