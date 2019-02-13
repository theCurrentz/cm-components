<?php

/**
 * Removes buttons from the first two rows of the tiny mce editor
 *
 * @link     http://thestizmedia.com/remove-buttons-items-wordpress-tinymce-editor/
 *
 * @param    array    $buttons    The default array of buttons in tinymce
 * @return   array                The updated array of buttons that exludes some items
 */
add_filter( 'mce_buttons', 'jivedig_remove_tiny_mce_buttons_from_editor');
function jivedig_remove_tiny_mce_buttons_from_editor( $buttons ) {
   $remove_buttons = array(
       //'bold',
       //'italic',
       //'strikethrough',
       //'bullist',
       //'numlist',
       //'blockquote',
       //'hr', // horizontal line
       //'alignleft',
       //'aligncenter',
       //'alignright',
       //'link',
       //'unlink',
       'wp_more', // read more link
       //'spellchecker',
       //'dfw', // distraction free writing mode
       //'wp_adv', // kitchen sink toggle (if removed, kitchen sink will always display)
   );
   foreach ( $buttons as $button_key => $button_value ) {
       if ( in_array( $button_value, $remove_buttons ) ) {
           unset( $buttons[ $button_key ] );
       }
   }
   return $buttons;
}


add_filter( 'mce_buttons_2', 'chroma_remove_tiny_mce_buttons');
function chroma_remove_tiny_mce_buttons( $buttons ) {
    $remove_buttons = array(
        //'formatselect', // format dropdown menu for <p>, headings, etc
        //'underline',
        //'alignjustify',
        //'forecolor', // text color
        //'pastetext', // paste as text
        //'removeformat', // clear formatting
        //'charmap', // special characters
        'outdent',
        'indent',
        //'undo',
        //'redo',
        //'wp_help', // keyboard shortcuts
    );
    foreach ( $buttons as $button_key => $button_value ) {
        if ( in_array( $button_value, $remove_buttons ) ) {
            unset( $buttons[ $button_key ] );
        }
    }
    return $buttons;
}
