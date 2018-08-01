<?php
abstract class meta_box_plag_warn {

  public static function add_box() {
  add_meta_box( 'plag-meta-box-id', esc_html__( 'Plagiarism Warning', 'text-domain' ), 'self::display_box', 'post', 'side', 'high' );
  }

  // Display the post meta box for ads toggling
  public static function display_box( $post ) {
    $wordConcat = $post->post_content;
    $wordConcat = preg_replace("/(?![.=$'€%-])\p{P}/u", "",$wordConcat);
    $wordConcat = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i",'<$1$2>', $wordConcat);
    //replace spaces with +
    $wordConcat = preg_replace("/\s+/", "+", $wordConcat);
    //replace ' apostraphes with %27
    $wordConcat = preg_replace("/'/", "%27", $wordConcat);
    $wordConcat = trim($wordConcat);
    $wordConcat = html_entity_decode($wordConcat);
    $wordConcat = strip_tags($wordConcat);
    ?>
    <p style="color: #e80056; font-weight: 600;">Do not plagiarize. You will not be payed for plagiarized contributions.</p>
    <p>
      <a target="_blank" href=\"https://visual.ly/blog/plagiarism-what-it-is-what-it-isnt-and-how-to-avoid-it-in-content-marketing/\">Find out what is and what isn't plagiarism.</a>
    </p>
    <p>
      <div class="button-primary" id="check-plag" data-content="<?php echo $wordConcat; ?>">
        Check Plagiarization
      </div>
    </p>
    <p>
      To fully enable the above tool, open browser "popup" settings and whitelist <?php echo get_bloginfo('name'); ?>.
    </p>
    <p>
    <strong>Learn How:</strong> <a target="_blank" href="https://toolset.com/documentation/user-guides/enable-pop-ups-browser/">All Browsers</a> | <a target="_blank" href="https://toolset.com/documentation/user-guides/enable-pop-ups-browser/">Chrome</a>
    </p>
  <?php
  }

}
