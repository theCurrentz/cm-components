'use strict'
var socialShareMaster = function() {
  var socialButtons = document.querySelectorAll('.share-button')
  if ( !(socialButtons.length > 0) )
    return
  var shareTypeMsg = 'no type',
        shareThisUrl = null,
        selfRef = this
  if (document.getElementsByClassName('email-share').length > 0)
    document.getElementsByClassName('email-share')[0].href = 'mailto:?subject='+ escape(document.title) + '&body=' + escape(window.location.href)
  for(var i = 0, l = socialButtons.length; i < l; i++) {
    socialButtons[i].addEventListener('click', function(event) {
      event.preventDefault()
      selfRef.shareRouter(this.getAttribute('data-share'))
    })
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
    shareThisUrl = 'https://www.facebook.com/sharer.php?u=' + escape(window.location.href) + '&amp;t=' + escape(document.title),
    shareTypeMsg = 'Facebook'
  }
  this['twitter'] = () => {
    shareThisUrl = `https://twitter.com/intent/tweet?text=${escape(document.title)}&url=${escape(window.location.href)}`
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
    shareThisUrl = '//www.reddit.com/submit?url=' + encodeURIComponent(window.location)
    shareTypeMsg = 'Reddit'
  }
  this['flipboard'] = () => {
    shareThisUrl = 'https://share.flipboard.com/bookmarklet/popout?v=2' + '&title=' + escape(document.title) + '&url=' + encodeURIComponent(window.location) + '&utm_campaign=tools&utm_medium=article-share&utm_source=www.idropnews.com&t=' + Date.now()
    shareTypeMsg = 'Flipboard'
  }
  this['copylink'] = () => {
    let urlInput = document.createElement('input')
    urlInput.setAttribute('value', window.location)
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
    if(moreSharing == null)
      throw " No other sharing options are available."
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
    shareThisUrl = `https://www.linkedin.com/shareArticle?mini=true&url=${encodeURIComponent(window.location)}&title=${escape(document.title)}`
    shareTypeMsg = 'Linked In'
  }
  this['pinterest'] = () => {
    shareThisUrl = `https://www.pinterest.com/pin/create/bookmarklet/?url=${encodeURIComponent(window.location)}&media=${encodeURIComponent(document.querySelector('meta[property="og:image"]').content)}&description=${escape(document.title)}`
    shareTypeMsg = 'Pinterest'
  }
  this['pocket'] = () => {
    shareThisUrl = `https://getpocket.com/save?url=${encodeURIComponent(window.location)}`
    shareTypeMsg = 'Pocket'
  }
  this['line'] = () => {
    shareThisUrl = `https://lineit.line.me/share/ui?url=${encodeURIComponent(window.location)}&text=${escape(document.title)}`
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
    shareThisUrl = `fb-messenger://share?link=${encodeURIComponent(window.location)}&app_id=${encodeURIComponent(chromaApp.fbAppID)}`
    shareTypeMsg = 'FB Messenger'
  }
  this['whatsapp'] = () => {
    if ( !(/Mobi|Android/i.test(navigator.userAgent)) ) {
      shareThisUrl == null
      this.feedbackMsg("WhatsApp is only available on mobile devices.")
      return
    }
    shareThisUrl = 'whatsapp://send?text=' + escape(document.title) + '%20' + encodeURIComponent(window.location)
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
        ga('send', 'socialShare', `${shareTypeMsg} social click`, escape(window.location.href));
      var dataLayer = dataLayer || []
      if (typeof dataLayer != 'undefined') {
        dataLayer.push({
          'event': {
            'eventName': 'socialShare',
            'shareType': `${shareTypeMsg} social click`,
            'originURL': escape(window.location.href)
          }
        })
      }
    } catch(err) {
        this.feedbackMsg('There was a problem sharing this content.' + err)
      }
    }
  }

        // if (shareType == 'Email Share' || shareType == 'Twitter Share' || shareType == 'Flipboard Share' || shareType == 'Reddit Share') {
        //   let fetch_prepare = location.protocol + '//' + window.location.hostname + '/wp-json/chroma/social-count/'
        //   fetch(fetch_prepare, {
        //     method: 'post',
        //     headers: {
        //       "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
        //     },
        //     body:
        //       `share_type=${shareType}&post_id=${chromaPost['ID']}`
        //   })
        //   .then( (response) => {
        //     try {
        //      console.log(response.status)
        //      return response.text().then(function (text) {
        //       console.log(text)
        //      });
        //     } catch(e) {
        //       console.log(e, response);
        //     }
        //   })
        //   .catch( (error) => {
        //     console.log(error)
        //   })
        // }
