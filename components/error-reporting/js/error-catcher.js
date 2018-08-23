const trackError = (error, fieldsObj = {}) => {
  ga('send', 'event', Object.assign({
    eventCategory: 'Script',
    eventAction: 'error',
    eventLabel: (error && error.stack) || '(not set)',
    nonInteraction: true,
  }, fieldsObj))
}

// Add a new listener to track event immediately, then send errors after a certain time threshold
const chromaErrorHandler = () => {
  var errorArray = []

  //listen for error events
  window.addEventListener('error', (event) => {
    errorArray.push(event)
    trackError(event.error, fieldsObj)
  })

  function postErrors(arr) {
    if(!(errorArray.length > 0))
      return
    //dump all errors into a string
    var errorMsg = ''
    arr.forEach( (e) => {
      errorMsg += "</br>" + e
    })
    if(errorMsg.length > 0) {
      let fetch_prepare = location.protocol + '//' + window.location.hostname + '/wp-json/chroma/ecollector/'
      fetch(fetch_prepare, {
        method: 'post',
        headers: {
          "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
        },
        body:
          `client_error=${errorMsg}`
      })
      .then( (response) => {
        response.json()
          .then( (data) => {
          })
      })
      .catch( (error) => {
        console.log(error)
      })
    }
  }

  //custom error tracking for adsbygoogle
  var googleAds = document.getElementsByClassName("adsbygoogle")
  if (typeof googleAds != 'undefined' && googleAds.length > 0) {
    const trackAdErrors = (googleAds) => {
      for(let i = 0, length = googleAds.length; i < length; i++) {
        if (googleAds[i].clientHeight < 90 ) {
          ga('send', 'event', {
            eventCategory: 'Adsense Warning',
            eventAction: 'Low performing ad: Ad height below 90',
            eventLabel: "Device: " + navigator.appName + ' ' + navigator.appVersion,
            nonInteraction: true
          })
          errorArray.push('Low performing ad: Ad height below 90')
          console.error('Low performing ad: Ad height below 90')
        }
      }
    }
    trackAdErrors(googleAds) // invoke ad warning tracking
  }

  window.setTimeout(
    () => {
      postErrors(errorArray)
    },
    2000
  )
}
chromaErrorHandler()
