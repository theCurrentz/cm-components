<?php
/**
 * Template part for displaying Social Sharing Buttons
 */
 global $post;

class social_share_component {

  function setButton($key) {
    $key = strtolower(str_replace('set' ,'',$key));
    ob_start();
    include plugin_dir_path( __DIR__ ) . "buttons/$key.php";
    $contents = ob_get_contents();
    ob_end_clean();
    echo $contents;
  }

  function __construct( $config = array(
    'setFacebook' => false,
    'setTwitter' => false,
    'setFlipboard' => false,
    'setReddit' => false,
    'setEmail' => false,
    'setComment' => false,
    'setDots' => false,
    'setCopyLink' => false,
    'setWhatsApp' => false,
    'setFBMessenger' => false,
    'setLinkedIn' => false,
    'setPinterest' => false,
    'setPocket' => false,
    'setPrint' => false,
    'classList' => null,
    'id' => null,
    'moreBox' => false
  )) {
      $classList = (!empty($config['classList'])) ? $config['classList'] : '';
      $id = (!empty($config['id'])) ? $config['id'] : '';
      unset($config['classList']);
      unset($config['id']);
    echo '<div class="social-sharing-controller '.$classList.'" id="'.$id.'">';
      foreach($config as $key => $value) {
        if ($value && $key != 'moreBox') {
          $this->setButton($key);
        }
      }

    echo '</div>';
    if (!empty($config['moreBox']) && $config['moreBox'] === true) {
        $this->createMoreShareNodes();
    }
  }
  private function createMoreShareNodes() {
    echo '<div class="more-sharing" id="more-sharing"><span class="more-sharing-title">Social Sharing<span class="more-sharing-close" id="msc"></span></span>';
      $generic_config = array(
        'setFacebook',
        'setTwitter',
        'setFlipboard',
        'setReddit',
        'setEmail',
        'setComment',
        'setCopyLink',
        'setWhatsApp',
        'setFBMessenger',
        'setLinkedIn',
        'setPinterest',
        'setPocket',
        'setLine',
        'setPrint'
      );
      foreach($generic_config as $key) {
        echo '<div class="more-sharing-node">';
          $this->setButton($key);
        echo '</div>';
      }
    echo '</div>';
  }
}
