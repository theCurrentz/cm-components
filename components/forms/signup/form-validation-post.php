<?php
//validate email form serverside then connect and post to the wordpress database
//register endpoint route
add_action( 'rest_api_init', function () {
  register_rest_route( 'chroma', 'form-processer', array(
    'methods' => 'POST',
    'callback' => 'chroma_form_processer',
  ) );
} );

function chroma_sanitize_input($data) {
  $data = strtolower($data);
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function chroma_form_processer(WP_REST_Request $request) {
  try {
    if (
      $request->get_param('email')
      && !empty($request->get_param('email'))
      && $request->get_param('type')
      && !empty($request->get_param('type'))
      && $request->get_param('currURL')
      && !empty($request->get_param('currURL'))
    ) {
      //initialize server sql connection variables
      $servername = DB_HOST;
      $username = DB_USER;
      $password = DB_PASSWORD;
      $db_name = DB_NAME;

      $conn = new mysqli($servername, $username, $password, $db_name);

      // Check connection
      if ($conn->connect_error) {
        return new WP_REST_Response('You may not connect to this service!' . $conn->connect_error, 500);
        exit();
      }

      //initialize email address variable
      $email = $request->get_param('email');
      //validate and sanitize the posted email before submission
      if (empty($email)) {
        $emailErr = "Email is required";
      } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // check if e-mail address is well-formed
          $emailErr = "Invalid email format";
      } elseif ($conn->connect_error) {
          $emailErr = "Connection Error. Try back later.";
          die("Connection failed: " . $conn->connect_error);
      } else {
        //sanitize email of extra html and standardize
        $email = chroma_sanitize_input($email);
        $type = chroma_sanitize_input($request->get_param('type'));
        $currURL = chroma_sanitize_input($request->get_param('currURL'));
        $ip = getenv('HTTP_CLIENT_IP')?:getenv('HTTP_X_FORWARDED_FOR')?:getenv('HTTP_X_FORWARDED')?:getenv('HTTP_FORWARDED_FOR')?:getenv('HTTP_FORWARDED')?:getenv('REMOTE_ADDR');
        $prop = "HealthiGuide.com";
        //prepare to insert post result values into database
        $stmt = "INSERT INTO signups (email, subscribe_type, ip_address, web_property, signup_url) VALUES ('$email', '$type', '$ip', '$prop', '$currURL') ON DUPLICATE KEY UPDATE email='$email', subscribe_type='$type'";
        if ($conn->query($stmt) === TRUE) {
          if ( $type == "unsubscribe" ) {
            $emailErr = "Successfully Unsubscribed!";
          } else if ( $type == "subscribe" ) {
            $emailErr = ["Subscribed!", "Please check your email for confirmation."];
          } else {
            $emailErr = "Invalid email format.";
          }
          return new WP_REST_Response($emailErr, 200);
        } else {
          return new WP_REST_Response("Error updating record: ". $conn->error, 404);
        }
        $conn->close();
      }
    }
  } catch(Exception $e) {
    return new WP_REST_Response($e->getMessage(), 400);
  }
}
