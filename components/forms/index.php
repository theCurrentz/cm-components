<?php
add_action('plugins_loaded', 'chroma_form_handle');
function chroma_form_handle() {
  include plugin_dir_path( __FILE__ ) . 'signup/form-validation-post.php';
  include plugin_dir_path( __FILE__ ) . 'signup/view/fb-login-button.php';
}
