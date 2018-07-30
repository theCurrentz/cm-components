<?php
//custom filters for content

//filter for view as one page slider options
function convert_multipage_post( $content ) {
  $content = str_replace('<!--nextpage-->', '', $content);
  return $content;
}

function add_comments_icon($content) {
    try {
      if(empty($content) || (!is_single()) || ( get_post_type( get_the_ID() ) != 'post' ) )
        return $content;
      $content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
      $dom = new DOMDocument();
      $dom->loadHTML($content);
      $comment = $dom->createElement('button');
      $comment_text = $dom->createTextNode("Comment");
      $comment->setAttribute('class', 'comments-icon');
      $comment->appendChild($comment_text);
      $ps = $dom->getElementsByTagName('p');
      if ($ps->length < 2) {
        return $content;
      }
      $targetP = $ps[($ps->length) - 1];
      $targetP->parentNode->insertBefore($comment, $targetP);
      $content = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $dom->saveHTML()));
      return $content;
    } catch(Exception $e) {
      if(!$e->getMessage().contains('figure')) {
        echo $e->getMessage();
      }
    }
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
    $content = str_replace('<p>&nbsp;</p>', '', $content);
    //never ever use text-align justify
    $content = str_replace('text-align: justify;', '', $content);
    $content = str_replace('text-transform: uppercase;', '', $content);
    //remove these BS <p>&nbsp;</p>
    $content = str_replace('<p>&nbsp;</p>', '', $content);
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

//override filter that accepts the_content and processes the caption shortcode for a custom layout
add_filter( 'img_caption_shortcode', 'chroma_img_caption_shortcode', 10, 25 );

function chroma_img_caption_shortcode( $empty, $attr, $content ) {

  extract(shortcode_atts(array(
      'id'	=> '',
      'align'	=> '',
      'width'	=> '',
      'caption' => ''
    ), $attr));

  $content = do_shortcode( $content );

  //parse out the desired dimensions and apply the dimensions as an aspect ratio to the figure
  $aspect_ratio = get_option('chromma-load-ar');
  $aspect_ratio = str_replace('-', '', $aspect_ratio);
  $aspect_ratio = str_replace('x', ',', $aspect_ratio);
  $aspect_ratio_array = explode(',', $aspect_ratio);
  $width = $aspect_ratio_array[0];
  $height = $aspect_ratio_array[1];

  $aspectRatio = ($height > 0 && $width > 0) ? ($height / $width) * 100 : 101;

  $aspectThresholdfix = 'height: auto; padding: 0px; max-height: '.$height.'px; max-width: '.$width.'px;';

  $content = (string)$content;
  $caption = (string)'<figcaption class="figcaption">'.trim($caption).'</figcaption>';
  $content = (string)$content  . $caption;
  $content = '<figure class="entry-content_figure fig-wcaption">' . $content . '</figure>';
  $content = preg_replace('/<\/figure>/', '', $content, 1);

	return $content;
}