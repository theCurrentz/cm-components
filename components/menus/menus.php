<?php
add_action( 'after_setup_theme', function () {
  //retrieve required  function () {subclasses
  require plugin_dir_path( dirname(__DIR__) ) . 'classes/class-chroma-walker-nav-menu.php';
  require plugin_dir_path( dirname(__DIR__) ) . 'classes/class-chroma-walker-nav-menu-sub.php';

  //register sites dynamic navigation menus
  function register_my_menus() {
    register_nav_menus(
      array(
        'header-menu' => __( 'Header Menu' ),
        'footer-menu'=> __( 'Footer Menu' ),
        'sub-header-menu'=> __( 'Sub Header Menu' )
      )
    );
  }
  add_action( 'init', 'register_my_menus' );
});
