import fbInitializer from "/var/www/html/wp-content/plugins/cm-components/components/forms/signup/facebook-api-init.js"
const fbApiInit = new fbInitializer()

function chromaFormHandler (callBack = null) {
  //data
  var postURL = location.protocol + '//' + window.location.hostname + '/wp-json/chroma/form-processer/',
      currURL = encodeURI(window.location)

  function submitForm (formIdVal, type = 'subscribe') {
    var dataBody = '&email=' + formIdVal + '&type=' + type + '&currURL=' + currURL
    var formOptions = {
      method: 'post',
      headers: { 'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8' },
      body: dataBody
    }
    fetch(postURL, formOptions)
      .then(response => { return response.text() })
      .then(text => {
        let msg = text
        if (formSuccess(msg) && callBack !== null) {
          let duplicateEmail = (msg.split(',')[0] === '["Already Subscribed!"') ? true : false
          callBack(duplicateEmail)
        }
      })
      .catch(error => console.log('Form submission error: ' + error))
  }

  this.facebookSignup = (el) => {
    FB.api("/me", "GET", {
      fields: "id,name,email"
    },
    function(e) {
      var t = "https://" + window.location.hostname + "/form-processing/",
          n = encodeURI(window.location),
          type = 'fblogin'
          var dataBody = `&email=${e.email}&type=fblogin&currURL=${n}`
          var formOptions = {
            method: 'post',
            headers: { 'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8' },
            body: dataBody
          }
          fetch(postURL, formOptions)
            .then(response => { return response.text() })
            .then(text => {
              let msg = text
              if (formSuccess(msg) && callBack !== null) {
                let duplicateEmail = (msg.split(',')[0] === '["Already Subscribed!"') ? true : false
                callBack(duplicateEmail)
              }
              if (el.getAttribute('data-next') !== null && el.getAttribute('data-next').indexOf('http') !== -1)
                window.location = el.getAttribute('data-next')
            })
            .catch(error => {alert('Facebook Login Error. Please try again later.'); console.log(e)})
    })
  }

  function formSuccess (text) {
    text = text.split(',')
    if (Array.isArray(text)) {
      for(let i = 0, l = text.length; i < l; i++) {
          text[i] = text[i].replace(/"/g,"")
          text[i] = text[i].replace(/]/g,"")
          text[i] = text[i].replace(/\[/g,"")
      }
      let errorMSG = document.getElementById('errorMessage'),
          errorMSG2 = document.getElementById('errorMessage2')
      errorMSG.classList.remove('is-active')
      errorMSG.innerText = text[0]
      errorMSG2.innerText = text[1]
      errorMSG.classList.add('is-active')
    } else {
      text = text.replace(/"/g,"")
      let errorMSG = document.getElementById('errorMessage')
      errorMSG.classList.remove('is-active')
      errorMSG.innerText = text
      errorMSG.classList.add('is-active')
    }
    return true
  }
  this.init = function() {
    //main
    try {
      if (doesExist(document.getElementById('subscribe'))) {
        var subscriber = document.getElementById('subscribe')
        subscriber.addEventListener('submit', (event) => {
          event.preventDefault()
          //gather context data
          var type = subscriber.getAttribute('data-type')
          type = (type !== null) ? type : 'subscribe'
          //@params value, type
          submitForm(document.getElementById('subscribeEmail').value, type)
        })
      }
      if (document.getElementsByClassName('fb-arrow').length > 0) {
        [].forEach.call(document.getElementsByClassName('fb-arrow'), (el) => {
          el.addEventListener('click', (event) => {
            event.preventDefault()
            fbApiInit.fbLogin(el)
          })
        })
      }

      if (doesExist(document.getElementById('unsub'))) {
        document.getElementById('unsub').addEventListener('submit', (event) => {
          event.preventDefault()
          submitForm(document.getElementById('unsubEmail').value, 'unsubscribe')
        })
      }

    } catch (e) {
      console.log(e)
    }
  }
}
export default chromaFormHandler;
