<?php
//filter ACF post object query so that it querys in chronological order
add_filter( 'acf/fields/post_object/query', 'change_post_object_query' );
function change_post_object_query( $args ) {
	$args['orderby'] = 'date';
	$args['order'] = 'DESC';
	return $args;
}
