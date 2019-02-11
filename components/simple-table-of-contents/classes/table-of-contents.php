<?php
//class definition for table of contents plugin
if ( !class_exists( 'table_of_contents' ) )
{
	class table_of_contents
  {
    //fields
    private $toc_tree_html = "";
    private $processed_content = "";

    //default constructor
    public function __construct($content)
    {
        $this->toc_tree_html = $this->toc_tree_html;
        $this->processed_content = $this->processed_content;
        //look through $content to find all matches for <h header tags
        $this->processed_content = $this->find_headings($content);
    }

    //deconstructor
    function __destruct()
    {
    }

    //retrieve toc html
    public function get_toc_tree_html()
    {
        //apply container and return toc tree html
        return '<nav class="anchor-nav"><div class="toctitle">Jump To:</div><ol class="anchor-nav__ol">' . $this->toc_tree_html . '</ol></nav>';
    }

    //retrieve processed content which has the anchors inserted
    public function get_processed_content()
    {
        return $this->processed_content;
    }

    //function looks through $content to find all matches for <h heading tags
    private function find_headings($content)
    {
      //declare matches to store all matches of headings
      $matches = array();
      // get all headings 1-6
      preg_match_all('|<h[^>]+>(.*)</h[^>]+>|iU', $content, $matches, PREG_SET_ORDER);
      foreach($matches as $match)
      {
          //create anchor text
          $anchor = $this->generate_anchor_text($match[1]);

          //add achor to toc html
          $this->create_toc_tree_html($anchor);

          //insert anchor tags before headings
          $content = $this->insert_anchor_name_tag($match[1], $anchor, $content);
      }
      return $content;
    }
    //function strips html and special characters from a <h> match and then replaces spaces with _ underscores, then is returned back to the function its called by
    private function generate_anchor_text($match)
    {
			if ( $match )
      {
				$match = trim( strip_tags($match) );

				// convert accented characters to ASCII
				$match = remove_accents( $match);

				// replace newlines with spaces (eg when headings are split over multiple lines)
				$match = str_replace( array("\r", "\n", "\n\r", "\r\n"), ' ', $match);

				// remove &amp;
				$match = str_replace( '&amp;', '', $match);

				// convert spaces to _
				$match = str_replace( array('  ', ' '), '_', $match);

				// remove trailing - and _
				$match = rtrim( $match, '-_' );
      }
      return $match;
    }

    private function create_toc_tree_html($anchor)
    {
      // convert _ to spaces
      $anchorText = str_replace( '_', ' ', $anchor);
      $this->toc_tree_html .= '<li class="anchor-nav__li"><a class="anchor-nav__a" href="#'.html_entity_decode($anchor).'">'.html_entity_decode($anchorText).'</a></li>';
    }

    private function insert_anchor_name_tag($match, $anchor, $content)
    {
      $anchor = '<a name="'.html_entity_decode($anchor).'"></a>';
      $content = str_replace($match, $anchor . $match, $content);
      return $content;
    }
  }
}
