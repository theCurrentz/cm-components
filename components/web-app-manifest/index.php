<?php
function chroma_web_app_manifest() {
  if ( file_exists(plugin_dir_path( __FILE__ ) . 'manifest.json') && strlen(get_option('icon_url') > 5)) {
    $manfiest = plugin_dir_url('cm-components') .
  'cm-components/components/web-app-manifest/manifest.json';
    $webAppName = get_bloginfo('name');
    $themeColor = get_option('theme_color');
    $icon = get_option('icon_url');
  echo "
    <link rel='manifest' href='$manfiest'>
    <meta name='mobile-web-app-capable' content='yes'>
    <meta name='apple-mobile-web-app-capable' content='yes'>
    <meta name='application-name' content='$webAppName'>
    <meta name='apple-mobile-web-app-title' content='$webAppName'>
    <meta name='theme-color' content='$themeColor'>
    <meta name='msapplication-navbutton-color' content='$themeColor'>
    <meta name='apple-mobile-web-app-status-bar-style' content='black-translucent'>
    <meta name='msapplication-starturl' content='/'>
    <link rel='icon' type='image/jpg' sizes='300 × 300' href='$icon'>
    <link rel='apple-touch-icon' type='image/jpg' sizes='300 × 300' href='$icon'>
  ";
  }
    ?>
    <?php
}
add_action('wp_head', 'chroma_web_app_manifest', 25);
