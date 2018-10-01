function chromaFormHandler () {
  function submitForm (formIdVal, type) {
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
        formSuccess(msg)
      })
      .catch(error => console.log('Form submission error: ' + error))
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
  }

  //main
  try {
    var postURL = location.protocol + '//' + window.location.hostname + '/wp-json/chroma/form-processer/',
        currURL = encodeURI(window.location)
    if (document.getElementById('subscribe') != null) {
      document.getElementById('subscribe').addEventListener('submit', (event) => {
        event.preventDefault()
        submitForm(document.getElementById('subscribeEmail').value, 'subscribe')
      })
    }
    if (document.getElementById('unsub') != null) {
      document.getElementById('unsub').addEventListener('submit', (event) => {
        event.preventDefault()
        submitForm(document.getElementById('unsubEmail').value, 'unsubscribe')
      })
    }
  } catch (e) {
  }
}
chromaFormHandler();
