<?php
function getChromaForm($title = null, $msg = null, $cta = null, $placeholder = null, $dataAttr = null) {
  echo "<form id='subscribe' class='cm_form' method='post' autocomplete='on' action='submitForm' data-type=$dataAttr>
   <label id='errorMessage' class='cm_form-label'>$title</label>
   <span id='errorMessage2' class='cm_form-desc'>$msg</span>
   <input id='subscribeEmail' class='cm_form-email' type='email' name='email' autocomplete='home email' pattern='^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$' required='' minlength='6' placeholder='$placeholder'>
   <input type='submit' value='$cta' class='cm_form-submit box-shadow-default ripple' />
  </form>";
}
