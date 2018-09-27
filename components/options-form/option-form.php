<?php
/**
* Option settings.
*/

// create custom plugin settings menu
add_action('admin_menu', 'chroma_settings_create_menu');

function chroma_settings_create_menu() {
  //create new top-level menu
  add_menu_page('Chroma Settings', 'Chroma Settings', 'administrator', __FILE__, 'chroma_settings' , '' );
}

function chroma_settings()  {
  //must check that the user has the required capability
  if (!current_user_can('manage_options')) {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }
  //data
  if (isset($_POST['comments_button']))
    update_option('comments_button', $_POST['comments_button']);
  $comments_button_val = get_option('comments_button');
  ?>
  <div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>

  <div class="wrap">
    <h1><?php bloginfo( 'name' ); ?> Settings</h1>
    <form name="form1" method="post" action="">
      <h3>Chroma Options:</h3>
      <table class="form-table">
        <tr valign="top">
          <th scope="row">Display Comment Button?</th>
          <td>
            <input type="checkbox" name="comments_button" value="yes" <?php checked($comments_button_val,'yes'); ?>/>
          </td>
        </tr>
        <tr valign="top">
          <td>
            <div class="submit">
              <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
            </div>
          </td>
        </tr>
      </table>
    </form>

<?php
}
