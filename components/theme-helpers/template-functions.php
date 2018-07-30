<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package chroma
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function chroma_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'chroma_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function chroma_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'chroma_pingback_header' );

//modifying conditions for my main archive query
function modify_main_query( $query ) {
if ( $query->is_archive() && $query->is_main_query() ) {
        $query->set( 'posts_per_page', 24 );
    }
}
add_action( 'pre_get_posts', 'modify_main_query' );


//modifies the post navigation content
function filter_archive_nav($archiveNav) {

	$archiveNav = str_replace('<h2 class="screen-reader-text">Posts navigation</h2>', '', $archiveNav);

	preg_match('/href=\"(?<href>.*)"\s/iU',$archiveNav, $hrefMatch);
	if (count($hrefMatch) > 0) {
	$archiveNav = str_replace('<div class="nav-previous"><a ', '<div class="nav-previous"><a id="prev-post" infinite-source="' . $hrefMatch['href'] . '" ', $archiveNav);
	}
	return $archiveNav;
}
