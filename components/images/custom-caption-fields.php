<?php
//appends some custom fields for media uploads
function chroma_caption_hyperlink_edit($form_fields, $post) {
  $form_fields['chroma-caption-hyperlink'] = array(
    'label' => 'Link',
    'input' => 'text',
    'value' => get_post_meta($post->ID, 'chroma_caption_hyperlink', true)
  );
  return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'chroma_caption_hyperlink_edit', 10, 2 );

function chroma_caption_hyperlink_save($post, $attachment) {
  if (isset($attachment['chroma-caption-hyperlink']))
    update_post_meta( $post['ID'], 'chroma_caption_hyperlink', $attachment['chroma-caption-hyperlink']);
  return $post;
}
add_filter( 'attachment_fields_to_save', 'chroma_caption_hyperlink_save', 10, 2 );

register_meta(
  'post',
  'chroma_caption_hyperlink',
  array(
    'type' => 'string',
    'single' => true,
    'show_in_rest' => true,
  )
);
