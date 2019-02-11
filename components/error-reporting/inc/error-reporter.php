<?php
require(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))) . '/wp-config.php');

//Load Composer's autoloader
require(dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php');

function chroma_error_reporter() {
  try {
    //initialize server sql connection variables
    $servername = DB_HOST;
    $username = DB_USER;
    $password = DB_PASSWORD;
    $db_name = DB_NAME;

    $conn = new mysqli($servername, $username, $password, $db_name);
    // Check connection
    if ($conn->connect_error)
      die("Connection failed: " . $conn->connect_error);

    //prepare errors from database table
    $error_msg_query = "SELECT error_msg FROM chromaErrors";
    $error_msg = $conn->query($error_msg_query);
    $body_stmt = '';
    if ($error_msg->num_rows > 0) {
      //remove dupicates from array
      $error_msg = array_unique($error_msg->fetch_assoc());
      // output data of each row
      foreach($error_msg as $e) {
        $body_stmt .= $e.'<br>';
      }
      if($body_stmt != '') {
        echo $body_stmt;
      } else {
        return;
      }

    } else {
      echo "0 results";
      return;
    }

    //select host url
    $host_query = "SELECT option_value FROM wordpress.wp_options WHERE option_name = 'siteurl' LIMIT 1";
    $host_query = $conn->query($host_query);
    $host_result = '';
    if ($host_query->num_rows > 0) {
        $row = $host_query->fetch_row();
        $host_result = $row[0];
        $host_result = preg_replace("/http?s:\/\//", '', $host_result);
    }

    //clear database table
    $conn->query("DELETE FROM chromaErrors");
    $conn->close();

  //send the message, check for errors
  if($body_stmt != '') {
    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    // More headers
    $headers .= 'From: <monitor@'.$host_result.'.com>' . "\r\n";
    mail(
      'parker.westfall@amobee.com',
      'Error Report',
      $body_stmt,
      $headers
    );
  }

  } catch(Exception $e) {
    echo $e->getMessage();
  }
}
chroma_error_reporter();
