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
    echo "<button class='btn facebook-share$context fb-arrow box-shadow-default ripple' data-next='$next_page'><svg class='svg-shadow share-svg' viewBox='0 0 1792 1792' xmlns='http://www.w3.org/2000/svg'><path d='M1343 12v264h-157q-86 0-116 36t-30 108v189h293l-39 296h-254v759h-306v-759h-255v-296h255v-218q0-186 104-288.5t277-102.5q147 0 228 12z'></path></svg>$msg</button>";
  }
}
