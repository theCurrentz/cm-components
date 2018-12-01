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
  if( isset($_POST[ 'hidden_field' ]) && $_POST[ 'hidden_field' ] == 'Y' ) {
    if (isset($_POST['comments']) && $_POST['comments'])
      update_option('comments_button', $_POST['comments']);
    else
      update_option('comments_button', null);
    if (isset($_POST['fb-api-key']) && $_POST['fb-api-key'])
      update_option('fb_api_key', $_POST['fb-api-key']);
    else
      update_option('fb_api_key', null);
    if (isset($_POST['fb-api-secret']) && $_POST['fb-api-secret'])
      update_option('fb_api_secret', $_POST['fb-api-secret']);
    else
      update_option('fb_api_secret', null);
    if (isset($_POST['proper_nouns']) && $_POST['proper_nouns'])
      update_option('proper_nouns', $_POST['proper_nouns']);
    else
      update_option('proper_nouns', null);
    if (isset($_POST['theme_color']) && $_POST['theme_color'])
      update_option('theme_color', $_POST['theme_color']);
    else
      update_option('theme_color', null);
    if (isset($_POST['icon_url']) && $_POST['icon_url'])
      update_option('icon_url', $_POST['icon_url']);
    else
      update_option('icon_url', null);
  }


  $comments_button_val = get_option('comments_button');
  $fb_api = get_option('fb_api_key');
  $fb_secret = get_option('fb_api_secret');
  $proper_nouns = get_option('proper_nouns');
  $theme_color = get_option('theme_color');
  $icon_url = get_option('icon_url');

  ?>
  <div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>

  <div class="wrap">
    <h1><?php bloginfo( 'name' ); ?> Settings</h1>
    <form name="form1" method="post" action="">
      <input type="hidden" name="<?php echo 'hidden_field'; ?>" value="Y"/>
      <h3>Chroma Options:</h3>
      <table class="form-table">
        <tr valign="top">
          <th scope="row">Display Comment Button?</th>
          <td>
            <input type="checkbox" name="comments" <?php checked($comments_button_val,'yes'); ?> value="yes"/>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">Facebook API Key</th>
          <td>
            <input type="text" name="fb-api-key" value="<?php echo $fb_api; ?>"/>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">Facebook API Secret</th>
          <td>
            <input type="text" name="fb-api-secret" value="<?php echo $fb_secret; ?>"/>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">Proper Nouns</th>
          <td>
            <?php $proper_nouns = str_replace(' ', '', $proper_nouns); ?>
            <textarea type="textarea" class="widefat" cols="50" rows="5" wrap="hard" name="proper_nouns" value="<?php echo trim(htmlentities(stripslashes($proper_nouns)) ); ?>"><?php echo trim(htmlentities(stripslashes($proper_nouns))); ?></textarea>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">Web App Theme Color</th>
          <td>
            <input type="text" name="theme_color" value="<?php echo htmlentities(stripslashes($theme_color) ); ?>"/>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">Web App Icon URL (*512x512)</th>
          <td>
            <input type="text" name="icon_url" class="widefat" value="<?php echo htmlentities(stripslashes($icon_url) ); ?>"/>
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
  </div>
<?php
  //generate manifest.json
  if (isset($_POST['icon_url']) && $_POST['icon_url']) {
    function getAppShortName($words) {
      if(count(explode(" ",$words)) > 1 &&  strlen($words) > 8) {
        $abbr = '';
        $words = explode(" ",$words);
        foreach($words as $word) {
          $abbr .= strtoupper($word[0]);
        }
        return $abbr;
      } else
        return $words;
    }

    $appName = get_bloginfo( 'name' );
    $appShortName = getAppShortName($appName);

    $manifest_data =
    "{
    'name': '$appName',
    'short_name': '$appShortName',
    'lang': 'en-US',
    'start_url': '/',
    'display': 'minimal-ui',
    'orientation': 'any',
    'theme_color': '$theme_color',
    'icons': [
      {
        'src': '$icon_url',
        'sizes': '512x512',
        'type': 'image/png'
      }
    ],
    'background_color': '#fff'
    }";
    $manifest_data = str_replace("'", '"', $manifest_data);
    $manifest_file = dirname(__DIR__) .
  '/web-app-manifest/manifest.json';
    $handle = fopen($manifest_file, 'w') or die('Cannot open file:  '.$manifest_file);
    fwrite($handle, $manifest_data);
    fclose($handle);
  }
}
