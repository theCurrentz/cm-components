<?php
//custom class overrides default walker nav to slim down html markup for subheader menu
class chroma_Walker_Nav_Menu_Sub extends Walker_Nav_Menu {
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
      $classes = 'header-nav-list_sub-menu';
      $output  .= "<div class='".$classes."_toggle'></div><ul class='". $classes ."'><div class='".$classes."_box'>";
  }

  //process and print li element
  public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
    if ( $depth === 0 ) {
          $classes = 'header-nav-list_sub-menu_li header-nav-list_sub-menu_li--has-children' ;
          $output .= "<li class='". $classes ."'><a class='header-nav-list_sub-menu_li_a' href='". $item->url ."' >". $item->title . "</a>";
      } else {
        $classes = 'header-nav-list_sub-menu_li' ;
        $output .= "<li class='". $classes ."'><a class='header-nav-list_sub-menu_li_a' href='". $item->url ."' >". $item->title . "</a>";
    }
  }

  //process and print closing ul element / end of sub menu block
  public function end_lvl( &$output, $depth = 0, $args = array() ) {
    if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
        $t = '';
        $n = '';
    } else {
        $t = "\t";
        $n = "\n";
    }
    $indent = str_repeat( $t, $depth );
    $output .= "$indent</div></ul>{$n}";
  }

}
