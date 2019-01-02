const cmEvent = function(name = '', postID = '', value = '') {
  if (name === null)
    return
  var dataBody = (Array.isArray(value))
    ? {
      name: name,
      postID: postID,
      value1: value[0],
      value2: value[1],
    }
    : {
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
      //console.log(formOptions.body+ " | " + text)
    })
    .catch(error => console.log('Event Error: ' + error))
}

export default cmEvent;
