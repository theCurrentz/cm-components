import 'promise-polyfill/src/polyfill';
import 'whatwg-fetch'

var errorArray = []
//console error override
// define a new console which will allow us to listen to custom console errors
var console = (function(oldCons){
    return {
        log: function(text){
            oldCons.log(text);
            // Your code
        },
        info: function (text) {
            oldCons.info(text);
            // Your code
        },
        warn: function (text) {
            oldCons.warn(text);
            // Your code
        },
        error: function (text) {
          errorArray.push(text)
            oldCons.error(text);
            // Your code
        }
    };
}(window.console));

//Then redefine the old console
window.console = console;

// Add a new listener to track event immediately, then send errors after a certain time threshold
const chromaErrorHandler = () => {
  //listen for error events
  window.addEventListener('error', (event) => {
    errorArray.push(event.error)
  })

  function postErrors(arr) {
    if(!(arr.length > 0))
      return
    arr = arr.filter(e=>e != null)
    //dump all errors into a string
    var blackList = ['']
    var errorMsg = ''
    arr.forEach( (e) => {
      e = e.toString()
      errorMsg += e + ' | ' + window.location.href + ' | Browser Info: ' + navigator.userAgent
      console.log(errorMsg)
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
            console.log('EPS: ' + data)
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
            console.error( 'Low performing ad: Ad height below 90')
            errorArray.push('Low performing ad: Ad height below 90px | Parent Node Class & ID: ' + googleAds[i].parentNode.classList + ' ' + googleAds[i].parentNode.id)
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
