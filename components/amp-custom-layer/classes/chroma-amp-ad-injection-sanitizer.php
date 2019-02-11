<?php

class Chroma_Amp_Ad_Injection_Sanitizer extends AMP_Base_Sanitizer {
      public function sanitize() {
        $body = $this->get_body_node();

        //Build AMP Ad DOM Node
        $ad_node = AMP_DOM_Utils::create_node( $this->dom, 'amp-ad', array(
    			'width' => 336,
    			'height' => 280,
    			'type' => 'adsense',
          'data-ad-client' => get_option('adsenseclient'),
    			'data-ad-slot' => get_option('adsenseslot'),
          'class' => 'ampad'
    		) );


          // If we have more than 4 paragraphs, insert before the 3rd one.
      		$p_nodes = $body->getElementsByTagName( 'p' );
      		if ( $p_nodes->length > 3 ) {
      			$p_nodes->item( 3 )->parentNode->insertBefore( $ad_node, $p_nodes->item( 3 ));
      		} else {
      			$body->appendChild( $ad_node );
      		}

      }
}


?>
