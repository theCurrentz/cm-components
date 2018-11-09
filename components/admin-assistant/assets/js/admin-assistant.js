import {formatOptionsHandler} from './modules/format-options-handler.js'
formatOptionsHandler()
import {imgHelper} from './modules/img-helper.js'
imgHelper()
import {checkPlag} from './modules/check-plag.js'
checkPlag()
import {tabsBox} from './modules/tabs-box.js'
tabsBox()
import {socialAnalysis} from './modules/social-sharing-analysis.js'
socialAnalysis()

if(document.getElementsByClassName('gutenberg-editor-page').length > 0) {
  window.send_to_editor = function(shortcode) {
    let aablBox = document.getElementsByClassName('aalb-admin-searchbox')[0],
      aablShortcode = document.createElement('p')
    aablShortcode.innerHTML = shortcode
    aablBox.appendChild(aablShortcode)
  }
}

let adminMenus = document.querySelectorAll('a.wp-has-submenu');
[].forEach.call( adminMenus, (el) => {
  el.addEventListener('click', (e) => {
    e.stopPropagation();
    e.preventDefault();
    [].forEach.call( adminMenus, (el2) => {
      el2.parentNode.children[1].setAttribute('style', '')
    })
    e.target.parentNode.querySelector('.wp-submenu').setAttribute('style', 'max-height: 500px !important;padding: 12px !important;')
  })
})
