export function socialAnalysis() {
  const socialHoverBox = document.getElementsByClassName('chromaShareData')
  if (socialHoverBox.length > 0) {
    let hovBox = null
    Array.prototype.forEach.call(socialHoverBox, (e) => {
      e.parentNode.querySelector('.social-button').addEventListener('click', (ev) => {
        ev.preventDefault()
        if (hovBox == null || typeof hovBox == 'undefined') {
          hovBox = document.createElement('div')
          hovBox.classList = 'social-hov-box is-active'
          hovBox.innerHTML =
            `<span class="social-hov-title">${e.parentNode.parentNode.querySelector('.title .row-title').innerHTML}</span>`+
            '<span class="social-hov-box-close">x</span>' +
            '<div style="font-weight: 800;">Views: ' + e.dataset.views + '</div>' +
            '<div style="font-weight: 800;">Total Engagement: ' + e.dataset.total + '</div>' +
            'FB Shares: ' + e.dataset.facebook + '</br>' +
            'FB Reactions: ' + e.dataset.facebookr + '</br>' +
            'FB Comments: ' + e.dataset.facebookc + '</br>' +
            'Twitter: ' + e.dataset.twitter + '</br>' +
            'Reddit: ' + e.dataset.reddit + '</br>' +
            'Flipboard: ' + e.dataset.flipboard + '</br>' +
            'Copy Link: ' + e.dataset.copylink + '</br>' +
            'Comment: ' + e.dataset.comment + '</br>' +
            'Email: ' + e.dataset.email + '</br>' +
            'Messenger: ' + e.dataset.messenger + '</br>' +
            'Whatsapp: ' + e.dataset.whatsapp + '</br>' +
            'Pinterest: ' + e.dataset.pinterest + '</br>' +
            'Linkedin: ' + e.dataset.linkedin + '</br>' +
            'Pocket: ' + e.dataset.pocket + '</br>' +
            'Line: ' + e.dataset.line + '</br>' +
            'Print: ' + e.dataset.print + '</br>' +
            'More: ' + e.dataset.more
          document.body.appendChild(hovBox)
          hovBox.querySelector('.social-hov-box-close').addEventListener('click', (ev) => {
            hovBox.classList.remove('is-active')
          })
        } else {
          hovBox.classList.add('is-active')
          hovBox.innerHTML =
            `<span class="social-hov-title">${e.parentNode.parentNode.querySelector('.title .row-title').innerHTML}</span>`+
            '<span class="social-hov-box-close">x</span>' +
            '<div style="font-weight: 800;">Views: ' + e.dataset.views + '</div>' +
            '<div style="font-weight: 800;">Total Shares: ' + e.dataset.total + '</div>' +
            'FB Shares: ' + e.dataset.facebook + '</br>' +
            'FB Reactions: ' + e.dataset.facebookr + '</br>' +
            'FB Comments: ' + e.dataset.facebookc + '</br>' +
            'Twitter: ' + e.dataset.twitter + '</br>' +
            'Reddit: ' + e.dataset.reddit + '</br>' +
            'Flipboard: ' + e.dataset.flipboard + '</br>' +
            'Copy Link: ' + e.dataset.copylink + '</br>' +
            'Comment: ' + e.dataset.comment + '</br>' +
            'Email: ' + e.dataset.email + '</br>' +
            'Messenger: ' + e.dataset.messenger + '</br>' +
            'Whatsapp: ' + e.dataset.whatsapp + '</br>' +
            'Pinterest: ' + e.dataset.pinterest + '</br>' +
            'Linkedin: ' + e.dataset.linkedin + '</br>' +
            'Pocket: ' + e.dataset.pocket + '</br>' +
            'Line: ' + e.dataset.line + '</br>' +
            'Print: ' + e.dataset.print + '</br>' +
            'More: ' + e.dataset.more
          hovBox.querySelector('.social-hov-box-close').addEventListener('click', (ev) => {
            hovBox.classList.remove('is-active')
          })
        }
      })
    })
  }
}
