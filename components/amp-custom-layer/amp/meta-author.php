<?php $post_author = $this->get( 'post_author' ); ?>
<?php if ( $post_author ) : ?>
	<div class="amp-wp-meta amp-wp-byline">
		<span class="amp-wp-author author vcard">By <?php echo esc_html( $post_author->display_name ); ?></span>
	</div>
<?php endif; ?>
