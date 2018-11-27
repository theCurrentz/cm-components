<?php
//custom filters for content
global $post;
//filter for view as one page slider options
function convert_multipage_post( $content ) {
  $content = str_replace('<!--nextpage-->', '', $content);
  return $content;
}

if (get_option('comments_button') == 'yes') {
  function add_comments_icon($content) {
      try {
        if(empty($content) || (!is_single()) || ( get_post_type( get_the_ID() ) != 'post' ) || has_category('gallery'))
          return $content;
        $content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
        $dom = new DOMDocument();
        $dom->loadHTML($content);
        $comment = $dom->createElement('button');
        $comment_text = $dom->createTextNode("Comment");
        $comment->setAttribute('class', 'comments-icon');
        $post_id = get_the_ID($post);
        $comment->appendChild($comment_text);
        if (get_bloginfo('name') == 'iDrop News') {
          $count = $dom->createElement('span');
          $count->setAttribute('class','comments-count disqus-comment-count');
          $count->setAttribute('data-disqus-identifier', "idropnews-$post_id");
          $comment->appendChild($count);
        }
        //position comment button
        $ps = $dom->getElementsByTagName('p');
        if ($ps->length < 2) {
          return $content;
        }
        $targetP = $ps[($ps->length) - 2];
        $targetP->parentNode->insertBefore($comment, $targetP);
        $content = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $dom->saveHTML()));
        return $content;
      } catch(Exception $e) {
        if(!$e->getMessage().contains('figure')) {
          echo $e->getMessage();
        }
      }
  }
  add_filter( 'the_content', 'add_comments_icon', 99 );
}

//strip unwanted stuff from content
function chroma_custom_content_filter( $content ) {
    //remove p tags around imgs, scripts, iframes and blockquotes
		$content = preg_replace('/<p>\s*(<a .*>)?\s*(<img .*\s*\/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
    $content = preg_replace('/<p>(\s*)(<img .*\s*\/*>)(\s*)<\/p>/iU', '\2', $content);
		$content = preg_replace('/<p>\s*(<script.*>*.<\/script>)\s*<\/p>/iU', '\1', $content);
    $content = preg_replace('/<p>\s*(<a.*>*.<\/a>)\s*<\/p>/iU', '\1', $content);
	  $content = preg_replace('/<p>\s*(<iframe.*>*.<\/iframe>)\s*<\/p>/iU', '\1', $content);
		$content = preg_replace('/<p>\s*(<blockquote.*>*.<\/blockquote>)\s*<\/p>/iU', '\1', $content);
    $content = preg_replace('/CONCLUSION/', 'Conclusion', $content);
    //never ever use text-align justify
    $content = str_replace('text-align: justify;', '', $content);
    $content = str_replace('text-align: center;', '', $content);
    $content = str_replace('text-transform: uppercase;', '', $content);
    //remove these BS <p>&nbsp;</p>
    $content = str_replace('<p>&nbsp;</p>', '', $content);
    $content = str_replace('<p></p>', '', $content);

    //find all name attributes and store
		preg_match_all('/name=\".*\"/iU', $content, $names );
    foreach ($names as $name) {
      $rename = str_replace(' ', '', $name);
      $content = str_replace($name, $rename, $content);
    }

		return $content;
}
add_filter( 'the_content', 'chroma_custom_content_filter', 99 );

/**
* Add Next Page/Page Break Button
* in WordPress Visual Editor
*
* @link https://shellcreeper.com/?p=889
*/
function my_add_next_page_button( $buttons, $id ) {

   /* only add this for content editor */
   if ( 'content' != $id )
       return $buttons;

   /* add next page after more tag button */
   array_splice( $buttons, 13, 0, 'wp_page' );

   return $buttons;
}
/* Add Next Page Button in First Row */
add_filter( 'mce_buttons', 'my_add_next_page_button', 1, 2 ); // 1st row

function related_a_tag($content) {
    if(empty($content) || (!is_single()) || ( get_post_type( get_the_ID() ) != 'post' ) )
      return $content;
    $content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
    $dom = new DOMDocument();
    $dom->loadHTML($content);
    $a = $dom->getElementsByTagName('a');
    if ($a->length <= 0)
    {
      return $content;
    }
    foreach($a as $a_tag) {
      if(stripos($a_tag->textContent, 'Related') === 0)
      {
        $a_tag->setAttribute('class', 'hg_related');
        $dom->saveHTML($a_tag);
      }
    }
    $content = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $dom->saveHTML()));
    return $content;
}
add_filter( 'the_content', 'related_a_tag' );
