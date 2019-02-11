<?php
//custom filters for content
function chroma_apple_news_content_filter($content) {
    $content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
    $dom = new DOMDocument();
    $dom->loadHTML($content);
    $links = $dom->getElementsByTagName('a');
    if ($links->length <= 0)
      return $content;
    foreach($links as $a) {
      if ($a->parentNode->nodeName === 'figcaption') {
        $figcaption = $a->parentNode;
        $newTextContent = $a->textContent;
        $figcaption->removeChild($a);
        $figcaption->textContent = $newTextContent;
        $dom->saveHTML($figcaption);
      }
    }
    $content = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $dom->saveHTML()));
    return $content;
}
//test filter on the_content hook
//add_filter( 'the_content', 'chroma_apple_news_content_filter', 100 );
if (is_plugin_active("publish-to-apple-news/apple-news.php")) {
  add_filter('apple_news_exporter_content_pre', 'chroma_apple_news_content_filter');
}
