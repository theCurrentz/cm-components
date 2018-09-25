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
      .catch(error => console.log('error is', error))
  }

  function formSuccess (text) {
    text = text.replace(/"/g,"")
    var errorMSG = document.getElementById('errorMessage')
    errorMSG.classList.remove('is-active')
    errorMSG.innerText = text
    errorMSG.classList.add('is-active')
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
