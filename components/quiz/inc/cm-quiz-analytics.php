<?php
add_action('admin_menu', 'quiz_analytics_create_menu');
function quiz_analytics_create_menu() {
  $menu = add_menu_page('Quiz Analytics', 'Quiz Analytics', 'administrator', __FILE__, 'quiz_analytics' , '' );
  add_action('admin_enqueue_scripts', 'enqueue_quiz_dash_scripts');
}

function quiz_analytics() {
  echo '<h1>Quiz Analytics</h1>';
  echo '<div id="app">
  <v-app id="inspire">
    <v-data-table
      :headers="headers"
      :items="quizzes"
      class="elevation-1"
    >
      <template slot="items" slot-scope="props">
        <td>{{ props.item.title }}</td>
        <td>{{ props.item.views }}</td>
        <td>{{ props.item.answered }}</td>
        <td>{{ props.item.completed }}</td>
        <td>{{ props.item.subscribed }}</td>
        <td>{{ props.item.duplicateSubscribe }}</td>
      </template>
    </v-data-table>
  </v-app>
</div>';
}

//enqueue script and styles
function enqueue_quiz_dash_scripts($hook) {
  if ( 'toplevel_page_cm-components/components/quiz/inc/cm-quiz-analytics' != $hook )
    return;
  if (is_admin()) {
    wp_enqueue_script( 'vue', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js', null, null, true );
    wp_enqueue_script( 'vuetify', 'https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.js', 'vue', null, true );
    wp_enqueue_script('cm-dash', plugin_dir_url(__DIR__) . 'src/cm-admin-dash.js', ['vue', 'vuetify'], null, true);
    wp_enqueue_style('vuetify-css', 'https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.min.css');
    wp_enqueue_style('vue-icons', 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons');
    // WP_Query arguments
    $args = array(
    	'post_type'              => array( 'cm-quiz' ),
    	'post_status'            => array( 'published' ),
    );
    // The Query
    $query = new WP_Query( $args );
    $data = [];
    if ($query->have_posts()) {
      while ($query->have_posts()) {
        $query->the_post();
        $quizArray = [
          'title' => get_the_title(),
          'views' => get_post_meta(
            get_the_ID(),
            'Views',
            true
          ),
          'answered' => get_post_meta(
            get_the_ID(),
            'answered',
            true
          ),
          'completed' => get_post_meta(
            get_the_ID(),
            'completed',
            true
          ),
          'subscribed' => get_post_meta(
            get_the_ID(),
            'subscribed',
            true
          ),
          'duplicateSubscribe' => get_post_meta(
            get_the_ID(),
            'duplicateSubscribe',
            true
          ),
          // 'question' => json_decode(stripslashes(get_post_meta(
          //   get_the_ID(),
          //   'question',
          //   true
          // )))
      ];
        array_push($data, $quizArray);
      }
    }
    wp_localize_script('cm-dash', 'cmQuiz', [
      'quizzes' => $data
    ]);
  }
}
