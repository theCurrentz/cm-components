import {formatOptionsHandler} from './modules/format-options-handler.js'
formatOptionsHandler()
import {imgHelper} from './modules/img-helper.js'
imgHelper()
import {checkPlag} from './modules/check-plag.js'
checkPlag()

if(document.getElementsByClassName('gutenberg-editor-page').length > 0) {
  window.send_to_editor = function(shortcode) {
    let aablBox = document.getElementsByClassName('aalb-admin-searchbox')[0],
      aablShortcode = document.createElement('p')
    aablShortcode.innerHTML = shortcode
    aablBox.appendChild(aablShortcode)
  }
}
