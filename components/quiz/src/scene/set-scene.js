import {doesExist, getRandomInt} from '../utility/functions.js'
/**
 * Set Scene
 */
export const setScene = (function() {
  try {
    let targetNode,
    darken = ''
    if (doesExist(document.getElementsByClassName('cm-quiz-single')[0])) {
      targetNode = document.getElementsByClassName('cm-quiz-single')[0]
    } else if (doesExist(document.getElementsByClassName('cm-quiz')[0])) {
     targetNode = document.getElementsByClassName('cm-quiz')[0]
     darken = 'linear-gradient(90deg, rgba(0,0,0, 0.56),rgba(0,0,0,0.56)),'
    }
    let gradientArray = ['#f0f,blue', '#ff512f, #dd2476', '#5433ff, #20bdff, #a5fecb', '#f79d00, #64f38c', '#396afc, #2948ff']
    let randomSelect = gradientArray[getRandomInt(5)]
    let choice = `${darken}linear-gradient(335deg,${randomSelect})`
    let primaryColor = randomSelect.split(',')[1]
    targetNode.style.background = choice
   document.getElementById('cm-quiz').style.setProperty("--main-color", primaryColor)
  } catch (e) {
      console.log(e.message)
    }
})()
