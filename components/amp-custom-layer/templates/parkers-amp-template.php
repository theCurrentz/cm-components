<!doctype html>
<html amp <?php echo AMP_HTML_Utils::build_attributes_string( $this->get( 'html_tag_attributes' ) ); ?>>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<?php do_action( 'amp_post_template_head', $this ); ?>
	<style amp-custom>
  <?php $this->load_parts( array( 'style' ) ); ?>
  <?php do_action( 'amp_post_template_css', $this ); ?>
	</style>
	<script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>
	<script async custom-element="amp-social-share" src="https://cdn.ampproject.org/v0/amp-social-share-0.1.js"></script>
	<script async
	 custom-element="amp-auto-ads"
	 src="https://cdn.ampproject.org/v0/amp-auto-ads-0.1.js">
</script>
</head>

<body class="<?php echo esc_attr( $this->get( 'body_class' ) ); ?>">
  <?php
    if (get_option('google_analytics_code')) {
      echo trim(stripslashes(get_option('google_analytics_code')),'"');
    }
  ?>
	<amp-auto-ads type="adsense" data-ad-client="<?php echo get_option('adsenseclient'); ?>"></amp-auto-ads>
	<a name="top"></a>
  <a class="amp-wp-header" href="<?php echo esc_url( $this->get( 'home_url' ) ); ?>">
    <amp-img src="<?php echo trim(stripslashes(get_option('schemaurl'))); ?>" width="170" height="29"></amp-img>
  </a>

  <article class="amp-wp-article">

    <header class="amp-wp-article-header pad">
    		<h1 class="amp-wp-title"><?php echo wp_kses_data( $this->get( 'post_title' ) ); ?></h1>
    		<?php $this->load_parts( apply_filters( 'amp_post_article_header_meta', array( 'meta-author', 'meta-time' ) ) ); ?>
    </header>

	<?php $this->load_parts( array( 'featured-image' ) ); ?>

		<!-- ad slot
			<div class="ampad">
        <amp-ad  width=336 height=280 type="adsense" data-ad-client="<?php // get_option('adsenseclient'); ?>" data-ad-slot="<?php // get_option('adsenseslot'); ?>"></amp-ad>
      </div>
      <div class="clear"></div>
    ad slot-->


<?php	if (get_field('table_of_contents') ) {

		if(have_rows('table_of_contents') ) { ?>

				<nav class="anchor-nav pad">
					<div class="toctitle">Jump To:</div>
						<ol>

								<?php while(have_rows('table_of_contents') ) { the_row(); ?>
										<li class="anchor__li">  <?php if(get_field('allow_link_out') == 'Allow') { ?>
														<a href="<?php the_sub_field('content_anchor') ?>">
															<?php the_sub_field('content_anchor'); ?>
														</a>
													<?php } else { ?>
													<a href="#<?php the_sub_field('content_anchor') ?>">
														<?php the_sub_field('content_anchor'); ?>
													</a>
												<?php } ?>
										</li>
								<?php } ?>

					  </ol>
				</nav>

<?php } } ?>

<?php if (has_category('How To')) { ?>
	<nav class="anchor-nav pad">
	<div class="toctitle">Jump To:</div>
		<ol>
		<?php if( have_rows('phase') ) { ?>
				<?php
							// loop through rows (parent repeater)
						while( have_rows('phase') ) { the_row(); ?>
									<li>
										<a href="#<?php the_sub_field('phase_title'); ?>"><?php the_sub_field('phase_title'); ?></a>
									</li>
									<?php } } ?>
		</ol>
	</nav>

	<div class="pad">
	<!-- How To Layout -->
		    <div>
		      <?php
		        if(get_field('intro')) {
		          echo '<p>' .get_field('intro'). '</p>';
		        } ?>
		    </div>

		      <?php if( have_rows('phase') ) { ?>
		          <?php
		              // loop through rows (parent repeater)
		              while( have_rows('phase') ) { the_row(); ?>
		                <div>
		                  <a name="<?php the_sub_field('phase_title'); ?>"></a>
		                  <h2><?php the_sub_field('phase_title'); ?></h2>
		                  <?php
		                  // check for rows (sub repeater)
		                  if( have_rows('step') ) { ?>
		                    <ol class="how-to-block">
		                    <?php

		                    // loop through rows (sub repeater)
		                    while( have_rows('step') ) { the_row();
		                      // display each item as a list - with a class of completed ( if completed )
		                        if( get_sub_field('step_content')) { ?>
		                          <li>
		                          	<?php the_sub_field('step_content'); ?>
		                          </li>
		                      <?php  } ?>
		                    <?php } ?>
		                  </ol>
		                  <?php } ?>
		                </div>

		              <?php } ?>

		            <?php }  ?>

		    <?php  if (get_field('conclusion')) {
		          echo '<p>' . get_field('conclusion') . '</p>';
		        } ?>
		</div>
<?php } ?>
<?php if (has_category('Reviews')) { ?>
<!-- Review Layout -->
						<div class="pad review-data">

						  <?php if (get_field('price')) { ?>
						  <div class="price"><span class="review-attribute">Price: </span>
						  	$<?php the_field('price'); ?>
						  </div>
						  <?php } ?>

						  <?php if (get_field('rating') >= 2) { ?>
						  <div>
						    <span class="review-attribute">Editor's Rating:</span>
						    <span class="rating">
						    <?php if (get_field('rating') == '1 Star') { ?>
						      <span>★</span><span>☆</span><span>☆</span><span>☆</span><span>☆</span>
						  <?php  } ?>
						  <?php if (get_field('rating') == '2 Stars') { ?>
						    <span>★</span><span>★</span><span>☆</span><span>☆</span><span>☆</span>
						  <?php  } ?>
						  <?php if (get_field('rating') == '3 Stars') { ?>
						    <span>★</span><span>★</span><span>★</span><span>☆</span><span>☆</span>
						  <?php  } ?>
						  <?php if (get_field('rating') == '4 Stars') { ?>
						    <span>★</span><span>★</span><span>★</span><span>★</span><span>☆</span>
						  <?php  } ?>
						  <?php if (get_field('rating') == '5 Stars') { ?>
						    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
						  <?php  } ?>
						  </span>
						  </div>
						  <?php } ?>

						  <?php if (get_field('pros')) { ?>
						  <div>
						    <span><span class="review-attribute">Pros: </span><?php the_field('pros'); ?></span>
						  </div>
						  <?php } ?>

						  <?php if (get_field('cons')) { ?>
						  <div>
						    <span><span class="review-attribute">Cons: </span><?php the_field('cons'); ?></span>
						  </div>
						  <?php } ?>
						  <?php if (get_field('bottom_line')) { ?>
						  <div>
						    <span><span class="review-attribute">Bottom Line: </span><?php the_field('bottom_line') ?></span>
						  </div>
						  <?php } ?>

						</div>
<?php } ?>


		  	<div class="amp-wp-article-content pad">
		  		<?php echo $this->get( 'post_amp_content' ); // amphtml content; no kses ?>
		  	</div>

				<div class="social-share-box">
					<amp-social-share class="social-share" width="100" height="32" type="facebook"
						data-param-url="<?php the_permalink() ?>" data-param-app_id="854557951369803"></amp-social-share>
					<amp-social-share class="social-share" width="100" height="32" data-param-url="<?php the_permalink() ?>" type="twitter"></amp-social-share>
				</div>

	<div class="related-title">
		Recommended:
	</div>
	<?php $this->load_parts( array( 'related-posts' ) ); ?>

  <!-- ad slot
    <div class="ampad">
      <amp-ad  width=336 height=280 type="adsense" data-ad-client="<?php // get_option('adsenseclient'); ?>" data-ad-slot="<?php // get_option('adsenseslot'); ?>"></amp-ad>
    </div>
    <div class="clear"></div>
  ad slot -->

	<!-- <footer class="amp-wp-article-footer">
		<?php //$this->load_parts( apply_filters( 'amp_post_article_footer_meta', array( 'meta-taxonomy', 'meta-comments-link' ) ) ); ?>
	</footer> -->


</article>

<?php
$this->load_parts( array( 'footer' ) ); ?>

<?php do_action( 'amp_post_template_footer', $this ); ?>

</body>
</html>
