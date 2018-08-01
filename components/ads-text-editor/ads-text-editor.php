<?php
//Ads.txt editor, make sure file permissions are open

 // create custom plugin settings menu
add_action('admin_menu', 'ads_text_create_menu');

function ads_text_create_menu() {
 	//create new top-level menu
 	add_menu_page('Ads.txt Settings', 'Ads.txt Editor', 'administrator', __FILE__, 'ads_text_options' , 'dashicons-list-view' );
}

function ads_text_options() {
  if (!current_user_can('manage_options'))
    wp_die( __('You do not have sufficient permissions to access this page.') );
  if (isset($_POST[ 'ads_text' ])) {
    $ads_text_posted_val = $_POST[ 'ads_text' ];
    update_option( 'ads_text', $ads_text_posted_val );
  }
  $ads_text_data = get_option('ads_text');
  echo (isset($_POST[ 'ads_text' ]) && count($ads_text_data) > 0 ) ? '<div class="updated"><p><strong>Ads.txt Saved</strong></p></div>' : '';
  ?>
  <form name="form1" method="post" action="">
    <h1>Ads.txt</h1>
    <table class="form-table">
      <tr valign="top">
        <td>
          <textarea id="toSort" style="min-height: 500px; background: #000 !important; color: springgreen;" type="textarea" class="widefat" cols="50" rows="5" wrap="hard" name="ads_text" value="<?php echo htmlentities ( stripslashes($ads_text_data) ); ?>"><?php echo htmlentities ( stripslashes($ads_text_data) ); ?></textarea>
        </td>
      </tr>
      <tr>
        <td>
          <div class="submit">
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
            &nbsp;&nbsp;
            <button id="sortAds" type="button" class="button-primary" onClick="sortAdsText()">
              Sort
            </button>
          </div>
        </td>
      </tr>
    </table>
  </form>
  <script>
  //ads.txt
  const sortAdsText = () => {
    if ( (window.location.href).includes('ads-text') ) {
      let sortAdsText = document.getElementById('sortAds')
      let
        textArea = document.getElementById('toSort'),
        toSort = textArea.value,
        splitted = toSort.split('\n'),
        newArray = []
      splitted.forEach( (item, index) => {
        item = item + '\n'
        if(item != "\n" && item != "" && item != " ")
          newArray.push(item.toLowerCase())
      })
      newArray.sort()
      textArea.innerText = newArray.join('')
      textArea.value = newArray.join('')
    }
  }
  </script>
<?php
  if (isset($_POST['ads_text'])) {
    $write_file = "/var/www/html/ads.txt";
    $file_opened = fopen($write_file, "w");
    if ($file_opened != false) {
      fwrite($file_opened, $ads_text_data);
      fclose($file_opened);
    } else
      exit();
  }
}
