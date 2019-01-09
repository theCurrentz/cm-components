'use strict'
var errorArray = []
// Add a new listener to track event immediately, then send errors after a certain time threshold
const chromaErrorHandler = () => {
  //listen for error events
  window.addEventListener('error', (ev) => {
    errorArray.push(ev.error + " | " + event.error.stack)
    postErrors(errorArray)
  })
  function postErrors(arr) {
    if(!(arr.length > 0))
      return
    arr = arr.filter(e=>e != null)
    //dump all errors into a string
    var errorMsg = ''
    arr.forEach( (e) => {
      e = e.toString()
      errorMsg += e + ' | ' + window.location.href + ' | Browser: ' + navigator.userAgent
    })
    if(errorMsg.length > 0) {
      var fetch_prepare = location.protocol + '//' + window.location.hostname + '/wp-json/chroma/ecollector/',
          body = `client_error=${errorMsg}`,
          xmlHttp = new XMLHttpRequest()
      xmlHttp.open('POST', fetch_prepare, true)
      xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8")
      xmlHttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
         //console.log(xmlHttp.responseText)
        }
      }
      xmlHttp.send(body);
    }
  }
}
//handler starts listening on load
chromaErrorHandler()
