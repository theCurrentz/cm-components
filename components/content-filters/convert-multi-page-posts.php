<?php
//function that removes nextpage tags in a multipage posts
function chroma_convert_multipage_post( $post ) {
		$post->post_content = str_replace('<!--nextpage-->', '', $post->post_content);

		$i = 0;
    	do {
			$found = preg_match_all('/<h2>/', $post->post_content, $h2Matches, PREG_OFFSET_CAPTURE);

				  if ($i < 2) {
					$post->post_content = substr_replace($post->post_content, '<div class="floatfixAd ad-box">
						<ins class="adsbygoogle lazyad"
								 style="display:block"
								 data-ad-client="ca-pub-4229549892174356"
								 data-ad-slot="6336901827"
								 data-ad-format="rectangle"></ins>
					</div><h2>', $h2Matches[0][$i][1], strlen('<h2>'));
					}
					else if ($i % 2 == 0)
					{
						$post->post_content = substr_replace($post->post_content, '<div class="floatfixAd ad-box">
								<ins class="adsbygoogle lazyad"
								     style="display:block"
								     data-ad-client="ca-pub-4229549892174356"
								     data-ad-slot="6336901827"
								     data-ad-format="rectangle"></ins>
							</div><h2>', $h2Matches[0][$i][1], strlen('<h2>'));
					}
					else if ($i % 2 != 0)
					{
						$post->post_content = substr_replace($post->post_content, '<div class="floatfixAd ad-box">
								<ins class="adsbygoogle lazyad"
								     style="display:block"
								     data-ad-client="ca-pub-4229549892174356"
								     data-ad-slot="6336901827"
								     data-ad-format="horizontal"></ins>
							</div><h2>', $h2Matches[0][$i][1], strlen('<h2>'));
					}
				$i++;
		}
		while($i < $found && $found !== false );

		return $post->post_content;
}
