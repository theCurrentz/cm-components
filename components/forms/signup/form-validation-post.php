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
        //prepare to insert post result values into database
        $stmt = $conn->prepare("INSERT INTO signups (email,subscribe_type,ip_address,web_property,signup_url) VALUES (?, ?, ?, ?, ?)");
        $type = $request->get_param('type');
        $currURL = $request->get_param('currURL');
        $ip = getenv('HTTP_CLIENT_IP')?:getenv('HTTP_X_FORWARDED_FOR')?:getenv('HTTP_X_FORWARDED')?:getenv('HTTP_FORWARDED_FOR')?:getenv('HTTP_FORWARDED')?:getenv('REMOTE_ADDR');
        $prop = "HealthiGuide.com";
        $stmt->bind_param("sssss", $email, $type, $ip, $prop, $currURL);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        if ( $type == "unsubscribe" ) {
          $emailErr = "Successfully Unsubscribed!";
        } else if ( $type == "subscribe" ) {
          $emailErr = "One More Step!";
        } else {
          $emailErr = "Invalid email format.";
        }
        //echo final success/fail message to front end
        return new WP_REST_Response($emailErr, 200);
      }
    }
  } catch(Exception $e) {
    return new WP_REST_Response($e->getMessage(), 400);
  }
}
