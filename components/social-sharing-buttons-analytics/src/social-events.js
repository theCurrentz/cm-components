function socialShareMaster() {
  var socialButtons = document.querySelectorAll('.share-button')

  if (document.getElementsByClassName('email-share').length > 0)
    document.getElementsByClassName('email-share')[0].href = 'mailto:?subject='+ escape(document.title) + '&body=' + escape(window.location.href)

  if (socialButtons.length > 0) {
    for(var i = 0, l = socialButtons.length; i < l; i++) {
      socialButtons[i].addEventListener('click', function(event) {
        event.preventDefault()
        var et = this,
            shareType = 'no type',
            shareThisUrl = null

        if ( (" " + et.className + " ").replace(/[\n\t]/g, " ").indexOf(' email-share ') > -1 ) {
          shareType = 'Email Share'
        } else if ( (" " + et.className + " ").replace(/[\n\t]/g, " ").indexOf(' facebook-share ') > -1 ) {
          shareThisUrl = 'https://www.facebook.com/sharer.php?u=' + escape(window.location.href) + '&amp;t=' + escape(document.title)
          shareType = 'Facebook Share'
        } else if ((" " + et.className + " ").replace(/[\n\t]/g, " ").indexOf(' twitter-share ') > -1 ) {
          shareThisUrl = 'https://twitter.com/share?text=' + escape(document.title) + '&amp;url=' + escape(window.location.href)
          shareType = 'Twitter Share'
        } else if ((" " + et.className + " ").replace(/[\n\t]/g, " ").indexOf(' google-share ') > -1 ) {
          shareThisUrl = 'https://plus.google.com/share?url=' + escape(window.location.href)
          shareType = 'Google Share'
        } else if ((" " + et.className + " ").replace(/[\n\t]/g, " ").indexOf(' reddit-share ') > -1 ) {
          shareThisUrl = '//www.reddit.com/submit?url=' + encodeURIComponent(window.location)
          shareType = 'Reddit Share'
        } else if ((" " + et.className + " ").replace(/[\n\t]/g, " ").indexOf(' flipboard-share ') > -1 ) {
          //shareThisUrl = 'https://share.flipboard.com/bookmarklet/popout?v=2' + '&title=' + escape(document.title) + '&url=' + encodeURIComponent(window.location) + '&utm_campaign=tools&utm_medium=article-share&utm_source=www.idropnews.com'
          shareType = 'Flipboard Share'
        }
        //open new tab
        if (shareThisUrl != null)
          window.open(shareThisUrl, '_blank')

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

        dataLayer.push({
          'event': {
            'eventName': 'socialShare',
            'shareType': shareType,
            'originURL': escape(window.location.href)
          }
        })
      })
    }
  }
  return false
}
socialShareMaster()
