export function socialAnalysis() {
  const socialHoverBox = document.getElementsByClassName('chromaShareData')
  if (socialHoverBox.length > 0) {
    [].forEach.call(socialHoverBox, (e) => {
      let hovBox = null
      e.parentNode.addEventListener('mouseover', (ev) => {
        if (e.parentNode.querySelector('.social-hov-box') == null || typeof e.parentNode.querySelector('.social-hov-box') == 'undefined') {
          hovBox = document.createElement('div')
          hovBox.classList = 'social-hov-box is-active'
          hovBox.innerHTML =
            '<span style="color: #0600ff; display: block;">Total Shares: ' + e.dataset.total + '</span>' +
            'FB Shares: ' + e.dataset.facebook + '</br>' +
            'FB Reactions: ' + e.dataset.facebookr + '</br>' +
            'FB Comments: ' + e.dataset.facebookc + '</br>' +
            'Twitter: ' + e.dataset.twitter + '</br>' +
            'Reddit: ' + e.dataset.reddit + '</br>' +
            'Flipboard: ' + e.dataset.flipboard + '</br>' +
            'Email: ' + e.dataset.email
          e.parentNode.style.position = 'relative'
          e.parentNode.appendChild(hovBox)
        } else {
          hovBox.classList.add('is-active')
        }
      })
      e.parentNode.addEventListener('mouseout', (ev) => {
        hovBox.classList.remove('is-active')
      })
    })
  }
}
