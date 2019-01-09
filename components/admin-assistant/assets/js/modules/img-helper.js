export function imgHelper() {

  const copyToClipboard = function() {
    this.previousSibling.select()
    document.execCommand('copy')
    this.innerText = "Copied!"
  }

  //provide global scope for this var
  var imgManager;

  const adminAssistant = function() {

    //check and trigger steps to close
    if (this.innerHTML == "Close") {
      imgManager.style.display = 'none'
      this.innerHTML = "Get Images"
      return
    }

    //reopen instead of recreating feature
    if (imgManager && imgManager.style.display == 'none') {
      imgManager.style.display = 'block'
      this.innerHTML = "Close"
      return
    }

    //change button text to close
    this.innerHTML = "Close"

    let allImages = document.getElementsByClassName('the_post')[0].querySelectorAll('img')

    //create imgManger & append
    imgManager = document.createElement('div')
    imgManager.classList.add('img_manager')
    document.body.appendChild(imgManager)


    //append an image thumbnail, as well as the recommended img source and img srcset
    for(let i = 0, imgLength = allImages.length; i < imgLength; i++)
    {
      //create container
      let imgCont = document.createElement('div')
      imgCont.classList.add('img_cont')
      imgManager.appendChild(imgCont)

      //create thumb
      let imgThumb = document.createElement('img')
      imgThumb.classList.add('img_thumb')
      imgThumb.srcset = allImages[i].dataset.srcset || allImages[i].dataset.src || allImages[i].srcset || allImages[i].src
      imgCont.appendChild(imgThumb)

      //create src code field
      let imgSrc = document.createElement('textarea')
      imgSrc.classList.add('img_src')
      imgCont.appendChild(imgSrc)
      //get a preliminary src value
      let imgSrcValue = allImages[i].dataset.srcset || allImages[i].dataset.src || allImages[i].srcset || allImages[i].src
      imgSrcValue = imgSrcValue.split(',')
      //get an alt text value
      let imgAltValue = allImages[i].alt

      //remove undesired dimensions from srcset string
      for(let length = imgSrcValue.length, i = length; i >= 0; i--) {
        if( imgSrcValue[i] && ( (imgSrcValue[i].indexOf('560') > 0) || (imgSrcValue[i].indexOf('768') > 0)) ) {
          imgSrcValue = imgSrcValue[i]
        }
      }
      //convert back to string and remove media query
      imgSrcValue = imgSrcValue.toString().replace(' 768w', '').replace(' 560w', '').replace(' ', '').replace('%20','').trim()
      imgSrcValue = '<img src="'+imgSrcValue+'" alt="'+imgAltValue+'" title="'+imgAltValue+'" class="mcnImage" width="100%" height="" border="0" style="width: 100%; height: auto; max-width: 600px; display: block; border: 0; outline: 0;" />'
      //set the value
      imgSrc.value = imgSrcValue

      //add copy to clipboard button
      let imgCopy = document.createElement('button')
      imgCopy.classList.add('img_copy')
      let copyText = document.createTextNode('Copy Source')
      imgCopy.appendChild(copyText)
      imgCont.appendChild(imgCopy)
      imgCopy.addEventListener('click', copyToClipboard)
    }

  }
  if(document.getElementsByClassName('the_post')[0]) {
    //create admin assistant button that launches the img Manager
    let adminAssistButton = document.createElement('div'),
    adminAssistTextNode = document.createTextNode("Get Images")
    adminAssistButton.classList.add('admin_assistant')
    adminAssistButton.appendChild(adminAssistTextNode)
    document.body.appendChild(adminAssistButton)
    adminAssistButton.addEventListener('click', adminAssistant)
  }
}
