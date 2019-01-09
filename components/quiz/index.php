<?php
if (get_option('enable_quizzes') == 'yes') {
  include plugin_dir_path( __FILE__ ) . 'inc/register-quiz-type.php';
  include plugin_dir_path( __FILE__ ) . 'inc/cm-quiz-analytics.php';
}
function register_viewport() {
  global $post;
  if ( $post->post_type == 'chroma_quizzes' )
    echo '<meta name="viewport" content="width=device-width,initial-scale=1.0">';
}
add_action('wp_head', 'register_viewport');

//reroute quiz template
function chroma_quiz_template($single) {
    global $post;
    if ( $post->post_type == 'chroma_quizzes' ) {
        if ( file_exists( plugin_dir_path( __FILE__ ) . '/view/single-quiz.php' ) ) {
            return plugin_dir_path( __FILE__ ) .  '/view/single-quiz.php';
        }
    }
    return $single;
}
add_filter('single_template', 'chroma_quiz_template');


//register endpoint route
add_action( 'rest_api_init', function () {
  register_rest_route( 'chroma', 'cmevents', array(
    'methods' => 'POST',
    'callback' => 'cm_event_processer',
  ) );
} );

function event_sanitize_input($data) {
  $data = strtolower($data);
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function cm_event_processer(WP_REST_Request $request) {
  $request = $request->get_json_params();
  try {
    if (
      $request['name']
      && !empty($request['name'])
    ) {
      //get current values
      $currValue = get_post_meta(
        $request['postID'],
        $request['name'],
        true
      );
      //LOGIC FOR INCREMENTING INSERTING ARRAY values
      if (
        $request['value1']
        && !empty($request['value1'])
        && $request['value1'] !== 'null'
        && $request['value2']
        && !empty($request['value2'])
        && $request['value2'] !== 'null'
      ) {
        if (!empty($currValue)) {
          $currValue = json_decode($currValue, true); //turn value into associative array
          foreach(array_keys($currValue) as $key) { //iterate over the keys of the array
            if($request['value1'] == $key) { //if key matches first Posted value
              foreach($key as $value) { //iterate over the node array
                if($value[0] == $request['value2']) { //evaluate second value
                  $value[1] = (int)$value[1] + 1; //increment if found
                } else { //otherwise push value to secondary array
                  array_push($value, [$request['value2'], 1]);
                }
              }
            } else { //otherwise append both new values
              $currValue[$request['value1']] = [$request['value2'], 1];
            }
          }
          $newValue = json_encode($currValue);
        } else {
          $newValue = json_encode([ $request['value1'] => [$request['value2'], 1] ]);
        }
      } else {
      //LOGIC FOR INCREMENTING SINGULAR VALUE
        //increment value
        $newValue = (!empty($currValue)) ? (int)$currValue + 1 : 1;
      }
      //update
      if (update_post_meta(
        (int)$request['postID'],
        $request['name'],
        $newValue
      ))
      //send response code
      return new WP_REST_Response('Event saved!', 200);
    }
  } catch(Exception $e) {
    return new WP_REST_Response($e->getMessage(), 400);
  }
}
