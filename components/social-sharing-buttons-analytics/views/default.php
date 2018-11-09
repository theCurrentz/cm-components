<?php
/**
 * Template part for displaying Social Sharing Buttons
 */
 global $post;
?>
<div class="social-sharing-bot" id="social-share">
  <!-- <div class="share-button">
    <?php//echo get_post_meta($post->ID,'fb_share_count', true); ?>
  </div> -->

  <?php
    include plugin_dir_path( dirname(__DIR__) ) . 'buttons/facebook.php';
    include plugin_dir_path( dirname(__DIR__) ) . 'buttons/twitter.php';
    include plugin_dir_path( dirname(__DIR__) ) . 'buttons/flipboard.php';
    include plugin_dir_path( dirname(__DIR__) ) . 'buttons/reddit.php';
    include plugin_dir_path( dirname(__DIR__) ) . 'buttons/email.php';
    include plugin_dir_path( dirname(__DIR__) ) . 'buttons/whatsapp.php';
  ?>

</div>
