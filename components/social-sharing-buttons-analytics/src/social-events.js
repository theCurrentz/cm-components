'use strict'
const socialShareMaster = function() {
  //cache nodes and resources
  const socialButtons = document.querySelectorAll('.share-button'),
        postId = (() => {
          try {
            let bClass = Array.prototype.join.call(document.body.classList, ' ')
            return bClass.match(/postid-[0-9]*/g)[0].split('-')[1]
          } catch(err) {
            return null
          }
        })();
  if ( !(socialButtons.length > 0) )
    return
  var shareTypeMsg = 'no type',
        shareThisUrl = null,
        selfRef = this
  var linkLocation = encodeURIComponent(window.location.href),
        linkTitle = encodeURIComponent(document.title)
  if (document.getElementsByClassName('email-share').length > 0)
    document.getElementsByClassName('email-share')[0].href = 'mailto:?subject='+ linkTitle + '&body=' + linkLocation
  for(let i = 0, l = socialButtons.length; i < l; i++) {
      if (socialButtons[i].getAttribute('data-has-listener') == null) {
        socialButtons[i].addEventListener('click', function(event) {
          let hasOverride = this.parentNode.getAttribute('data-url-override')
          linkLocation = (doesExist(hasOverride)) ? encodeURIComponent(hasOverride) : encodeURIComponent(window.location.href)
          linkTitle = (doesExist(hasOverride)) ? encodeURIComponent(this.parentNode.getAttribute('data-title-override')) : encodeURIComponent(document.title)
          event.preventDefault()
          selfRef.shareRouter(this.getAttribute('data-share'))
        })
        socialButtons[i].setAttribute('data-has-listener', 'true')
    }
  }

  var closeFlag = true;
  this.closeButtonToggle = (e) => {
    if (closeFlag) {
      document.getElementById('msc').addEventListener('click', () => {
        e.classList.toggle('is_active')
          document.documentElement.classList.toggle('noscroll')
      })
      closeFlag = false;
    }
  }

  this.feedbackMsg = (msg) => {
    let copyConfirm = document.createElement('div')
    copyConfirm.classList = 'copyconfirm box-shadow-nohover'
    copyConfirm.innerHTML = msg
    document.body.appendChild(copyConfirm)
    setTimeout(()=> {
      copyConfirm.remove()
    }, 850)
  }

  this['facebook']  = () => {
    shareThisUrl = 'https://www.facebook.com/sharer.php?u=' + linkLocation + '&amp;t=' + linkTitle,
    shareTypeMsg = 'Facebook'
  }
  this['twitter'] = () => {
    shareThisUrl = `https://twitter.com/intent/tweet?text=${linkTitle}&url=${linkLocation}`
    shareTypeMsg =  'Twitter'
  }
  this['email'] = () => {
    shareTypeMsg = 'Email'
    setTimeout(function() {
      window.location = document.getElementsByClassName('email-share')[0].href
    }, 170)
    shareThisUrl = null
  }
  this['reddit'] = () => {
    shareThisUrl = `https://www.reddit.com/submit?url='${linkLocation}`
    shareTypeMsg = 'Reddit'
  }
  this['flipboard'] = () => {
    shareThisUrl = 'https://share.flipboard.com/bookmarklet/popout?v=2' + '&title=' + linkTitle + '&url=' + linkLocation + '&utm_campaign=tools&utm_medium=article-share&utm_source=www.idropnews.com&t=' + Date.now()
    shareTypeMsg = 'Flipboard'
  }
  this['copylink'] = () => {
    let urlInput = document.createElement('input')
    urlInput.setAttribute('value', decodeURIComponent(linkLocation))
    urlInput.setAttribute('style', 'opacity: 0; position: absolute; top: 0px; z-index: -100; transform: translateX(-100vw);')
    document.body.appendChild(urlInput)
    urlInput.select()
    document.execCommand('copy')
    urlInput.remove()
    this.feedbackMsg('Copied to clipboard.')
    shareThisUrl = null
    shareTypeMsg = 'Copy Link'
  }
  this['more'] = () => {
    let moreSharing = document.getElementById('more-sharing')
    if (doesExist(moreSharing) === false)
      throw " No other sharing options are available.";
    moreSharing.classList.toggle('is_active')
    if(moreSharing.getAttribute('data-focus') == 'true'){
      moreSharing.setAttribute('data-focus', 'false')
      moreSharing.blur()
    } else {
      moreSharing.setAttribute('data-focus', 'true')
      moreSharing.focus()
    }
    document.documentElement.classList.toggle('noscroll')
    this.closeButtonToggle(moreSharing)
    shareThisUrl = null
    shareTypeMsg = 'More'
  }
  this['linkedin'] = () => {
    shareThisUrl = `https://www.linkedin.com/shareArticle?mini=true&url=${linkLocation}&title=${linkTitle}`
    shareTypeMsg = 'Linked In'
  }
  this['pinterest'] = () => {
    shareThisUrl = `https://www.pinterest.com/pin/create/bookmarklet/?url=${linkLocation}&media=${encodeURIComponent(document.querySelector('meta[property="og:image"]').content)}&description=${linkTitle}`
    shareTypeMsg = 'Pinterest'
  }
  this['pocket'] = () => {
    shareThisUrl = `https://getpocket.com/save?url=${linkLocation}`
    shareTypeMsg = 'Pocket'
  }
  this['line'] = () => {
    shareThisUrl = `https://lineit.line.me/share/ui?url=${linkLocation}&text=${linkTitle}`
    shareTypeMsg = 'line'
  }
  this['print'] = () => {
    let moreSharing = document.getElementById('more-sharing')
    moreSharing.classList.toggle('is_active')
    window.print()
    shareThisUrl = null
    shareTypeMsg = 'Print'
  }
  this['messenger'] = () => {
    if ( !(/Mobi|Android/i.test(navigator.userAgent)) ) {
      shareThisUrl = null
      this.feedbackMsg("Messenger is only available on mobile devices.")
      return
    }
    if (!(chromaApp.fbAppID > 0)) {
      shareThisUrl = null
      this.feedbackMsg("API Uunavailable.")
      return
    }
    shareThisUrl = `fb-messenger://share?link=${linkLocation}&app_id=${encodeURIComponent(chromaApp.fbAppID)}`
    shareTypeMsg = 'FB Messenger'
  }
  this['whatsapp'] = () => {
    if ( !(/Mobi|Android/i.test(navigator.userAgent)) ) {
      shareThisUrl == null
      this.feedbackMsg("WhatsApp is only available on mobile devices.")
      return
    }
    shareThisUrl = 'whatsapp://send?text=' + linkTitle + '%20' + linkLocation
    shareTypeMsg = 'Whatsapp'
  }
  this['comment'] = () => {
    if (typeof ct == 'undefined')
      this.feedbackMsg("Comments are currently unavailable.")
    else {
      ct.createCommentComponent();
      [].forEach.call(document.querySelectorAll('.comments_overlay, #comments'), (e) => {
        e.classList.toggle('is-active')
      })
      document.documentElement.classList.remove('noscroll')
      document.getElementById('more-sharing').classList.remove('is_active')
    }
    shareThisUrl = null
    shareTypeMsg = 'Comment'
  }

  this.shareRouter = (shareType) => {
    try {
      //execute input function
      this[shareType]()
      //execute window
      if (shareThisUrl != null)
        window.open(shareThisUrl, '_blank')
      //execute analytics
      if (window._gaq && window._gaq._getTracker)
        ga('send', 'socialShare', `${shareTypeMsg} social click`, linkLocation);
      var dataLayer = dataLayer || []
      if (typeof dataLayer != 'undefined') {
        dataLayer.push({
          'event': {
            'eventName': 'socialShare',
            'shareType': `${shareTypeMsg} social click`,
            'originURL': linkLocation
          }
        })
        socialEvent(`_${shareTypeMsg.replace(' ','_').toLowerCase()}_click`, postId)
      }
    } catch(err) {
        this.feedbackMsg('There was a problem sharing this content.' + err)
      }
    }
  }

const socialEvent = function(name = '', postID = '') {
  if (name === null || name === '' || postID === null || postID === '')
    return
  var dataBody = {
      name: name,
      postID: postID,
    },
  formOptions = {
    method: 'post',
    headers: { 'Content-type': 'application/json' },
    body: JSON.stringify(dataBody)
  },
  eventUrl = location.protocol + '//' + window.location.hostname + '/wp-json/chroma/cmevents/'
  fetch(eventUrl, formOptions)
    .then(response => { return response.text() })
    .then(text => {
      console.log(formOptions.body+ " | " + text)
    })
    .catch(error => console.log('Event Error: ' + error))
};

//Log a post view
(() => {
  let postId = (() => {
    try {
      let bClass = Array.prototype.join.call(document.body.classList, ' ')
      return bClass.match(/postid-[0-9]*/g)[0].split('-')[1]
    } catch(err) {
      return null
    }
  })();
  if (doesExist(postId))
    socialEvent('post_views', postId)
})();
