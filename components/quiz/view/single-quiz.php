<?php
get_header();
global $ai_wp_data;
$ai_wp_data [AI_WP_DEBUGGING] |= AI_DEBUG_NO_INSERTION;
?>
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1274542409303112');
  </script>
  <noscript>
    <img height="1" width="1" style="display:none"
         src="https://www.facebook.com/tr?id=1274542409303112&ev=PageView&noscript=1"/>
  </noscript>
  <div class="cm-quiz-single">
    <aside class="cm-quiz" id="cm-quiz" data-id="<?php the_ID();?>" data-link="<?php the_permalink(); ?>">
    <?php
         $cssTime = filemtime( plugin_dir_path( __DIR__ ) .'src/style.sass' );
        echo '<link rel="stylesheet" href="'.plugin_dir_url("cm-components") . 'cm-components/dist/cmquiz.css?'.$cssTime.'" type="text/css" media="all"/>';
      ?>
      <div class="cm-quiz-title-bar">
        <div class="cm-quiz-title"><?php the_title() ?></div>
        <div class="cm-quiz-prog" id="cm-quiz-prog">0</div>
        <div class="cm-quiz_timer"></div>
      </div>
      <div class="cm-quiz-box">
          <?php the_content(); ?>
        <div class="cm-quiz-prompt">
          <div class="cm-quiz-prompt-box">
              <?php
                getChromaForm('Subscribe to See Results', 'Automatically enter for a chance to win a free 2018 MacBook Pro!', $cta = 'Subscribe', $placeholder = 'win@example.com', 'quiz-subscribe');
                $fbLoginDefault = new fb_login_button(array('context' => 'cm-quiz_fb-login', 'msg' => 'Facebook Login'));
              ?>
          </div>
        </div>
        <div class="cm-quiz-results" data-slide="slide-z">
          <div class='cm-quiz_share-cta'>
            <span class='cm-quiz_finalscore'></span>
            <?php
            new social_share_component(
              array(
                'setFacebook' => true,
                'setEmail' => true,
                'setCopyLink' => true,
                'classList' => 'cm-quiz-share',
                'urlOverride' => true
              )
            ); ?>
          </div>
        </div>
        </div>
        <div class="cm-quiz_nav">
          <div id="cm-quiz-back" class="cm-quiz_nav-b ripple">
            <div class="cm-quiz_nav-b-a cm-quiz_nav-b-a-b">
            </div>
            <span>Back</span>
          </div>
          <div id="cm-quiz-fwd" class="cm-quiz_nav-f ripple">
            <span>Next</span>
            <div class="cm-quiz_nav-f-a cm-quiz_nav-f-a-f">
            </div>
          </div>
        </div>
        <?php $quizSocialShare = new social_share_component(
          array(
            'setFacebook' => true,
            'setFBMessenger' => true,
            'setEmail' => true,
            'setCopyLink' => true,
            'classList' => 'cm-quiz-share',
            'urlOverride' => true
          )
        );
        ?>
      </aside>
    </div>
  </div>
  <?php
        $jstime = filemtime( plugin_dir_path( __DIR__ ) .'src/quiz-app.js' );
        $jsPath = plugin_dir_url('cm-components') . 'cm-components/dist/quiz.js?' . $jstime;
        echo "<script id='cm-quiz-script'  defer src='$jsPath'></script></script>";
    ?>
<?php get_footer();
