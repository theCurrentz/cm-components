<?php
//require(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))) . '/wp-config.php');
function chroma_share_count_cron() {
  try {
    //initialize server sql connection variables
    $servername = 'rds_ssl.idropnews.com';
    $username = 'root';
    $password = 'idrop*news';
    $db_name = 'wordpress_idrop';

    $conn = new mysqli($servername, $username, $password, $db_name);
    // Check connection
    if ($conn->connect_error)
      echo "Connection failed: " . $conn->connect_error;


    // get offset
    // get number of posts
    // set offset to increment by 100 every time script is run
    // query OFFSET 100 LIMIT 100 posts to update

    // get offset
    $target_offset = "SELECT option_value FROM wp_options WHERE option_name = '_chroma_social_offset'";
    $target_offset = $conn->query($target_offset);
    $offset_result = '';
    if ($target_offset->num_rows > 0) {
      $row = $target_offset->fetch_row();
      $offset_result = intval($row[0]);
    }

    // get number of posts
    $post_quantity_query = "SELECT COUNT(*) FROM wp_posts WHERE post_type = 'post'";
    $post_quantity_query = $conn->query($post_quantity_query);
    $quantity_result = '';
    if ($post_quantity_query->num_rows > 0) {
      $row = $post_quantity_query->fetch_row();
      $quantity_result = $row[0];
      $quantity_result = intval(preg_replace("/http?s:\/\//", '', $quantity_result));
    }

    // update offset to increment by 100 every time script is run, if offset(-100) equals is less than the quanity, reset to 0
    $update_offset = intval(($offset_result - 100 <= $quantity_result) ? $quantity_result/100 + $offset_result : 0);
    $update_offset_query = "UPDATE wp_options SET option_value = $update_offset WHERE option_name = '_chroma_social_offset'";
    // Prepare statement
    $stmt = $conn->prepare($update_offset_query);
    // execute the query
    $stmt->execute();
    $selectIds = "SELECT ID FROM wp_posts WHERE post_type = 'post' LIMIT 100 OFFSET $update_offset";
    $selectIds = $conn->query($selectIds);
    $selectIds_results = "";
    if ($selectIds->num_rows > 0) {
      while($selectIds_result = $selectIds->fetch_assoc()) {
        $post_id = $selectIds_result['ID'];
        //get url
        $url_from_id = "SELECT meta_value FROM wp_postmeta WHERE meta_key = '_chroma_permalink' AND post_id = $post_id";
        $url_from_id = $conn->query($url_from_id);
        //var_dump($url_from_id);
        $url_from_id_result = '';
        if ($url_from_id->num_rows > 0) {
          $row = $url_from_id->fetch_row();
          $url_from_id_result = $row[0];
        }
        //curl to facebook to get data
        // create curl resource
        try {
          $ch = curl_init();
          // set url
          $url = "https://graph.facebook.com/v3.2/?id=$url_from_id_result&fields=engagement&access_token=589890624510367|ed6b2fa114da39894c06b462e022f9c9";
          curl_setopt($ch, CURLOPT_URL, $url);
          //return the transfer as a string
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          // $output contains the output string
          $output = curl_exec($ch);
          $output = json_decode($output, true);
          // close curl resource to free up system resources
          curl_close($ch);
          if (!array_key_exists('error', $output)) {
            $share_value = $output['engagement']['share_count'];
            $update_query = "UPDATE wp_postmeta SET meta_value='$share_value' WHERE meta_key = '_facebook_share' AND ID = '$post_id'";
            echo $share_value;
          } else {
            var_dump($output['error']);
          }
        } catch(Exception $e) {
          echo $e;
        }
      }
    }
    //close connection
    $conn->close();

  } catch(Exception $e) {
    echo $e->getMessage();
  }
}
chroma_share_count_cron();
