export const checkPlag = (content) =>  {

  if(document.getElementById('check-plag') === null)
    return;

  document.getElementById('check-plag').addEventListener("click", split_and_query, true)

  function split_and_query() {
    let content = document.getElementById('check-plag').dataset.content || "nothing + found"
    var splitArray = content.split("+") || content;
    var currentString = "";
    for (var i = 0, len = splitArray.length; i < len; i++) {
        currentString += splitArray[i] + "+";
        if (i % 32 == 0) {
          window.open('https://google.com/search?q=' + currentString + '');
          currentString = "";
        }
    }
    if (currentString !== "") {
      window.open('https://google.com/search?q=' + currentString + '');
    }
    return;
  }

}
