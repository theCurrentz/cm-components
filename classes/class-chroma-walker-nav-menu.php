<?php
//custom class overrides default walker nav to slim down html markup
class chroma_Walker_Nav_Menu extends Walker_Nav_Menu {
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $classes = 'nav-list_sub-menu' ;
    		$output  .= "<ul class='". $classes ."'><div class='".$classes."_toggle'></div>";
    }

    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
      if ( $depth === 0 ) {
            $classes = 'nav-list_sub-menu_li nav-list_sub-menu_li--has-children' ;
            $output .= "<li class='". $classes ."'><a class='nav-list_sub-menu_li_a' href='". $item->url ."' >". $item->title . "</a>";
        } else {
        $classes = 'nav-list_sub-menu_li' ;
        $output .= "<li class='". $classes ."'><a class='nav-list_sub-menu_li_a' href='". $item->url ."' >". $item->title . "</a>";
      }
  }

}
