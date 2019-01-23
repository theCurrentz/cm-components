'use strict'
import './style.sass'
import { createStore } from 'redux';
import {addQuestion} from './store/actions'
import {initState, cmQuizStore, render, questionTracker} from './quiz-store.js'
import fbInitializer from "/var/www/html/wp-content/plugins/cm-components/components/forms/signup/facebook-api-init.js"
import chromaFormHandler from '/var/www/html/wp-content/plugins/cm-components/components/forms/signup/form-action.js'
import cmEvent from './cm-analytics.js'
cmQuizStore.subscribe(render)

/**
 * Utility Functions
 */
function doesExist(el) {
  if (el !== null && typeof el !== 'undefined')
    return true
}
function getRandomInt(max) {
  return Math.floor(Math.random() * Math.floor(max));
}

/**
 * Set Scene
 */
(function() {
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

//quiz app controller
const quizAppCont = function(cmQuiz) {
  if ( !(document.getElementsByClassName('cm-quiz-slide').length > 0))
    return
  /**
  * Nodes and Fields
  */
  const
    quizID = cmQuiz.getAttribute('data-id'),
    quizBox = document.getElementsByClassName('cm-quiz-box')[0],
    slides = document.getElementsByClassName('cm-quiz-slide'),
    slideAmount = slides.length,
    progression = document.getElementById('cm-quiz-prog'),
    theTime = document.getElementsByClassName('cm-quiz_timer')[0],
    qWeight = Math.round(100 / slideAmount),
    scale = {a: 0, b:1, c:2, d:3}
  var
    currentIndex = 0,
    totalScore = 0,
    explanationSkipped = false,
    currentNode = document.getElementsByClassName('cm-quiz-slide')[0],
    prevNode = null,
    nextNode = document.getElementsByClassName('cm-quiz-slide')[1],
    backFlag = false,
    fwdFlag = false,
    backButton = document.getElementById('cm-quiz-back'),
    fwdButton = document.getElementById('cm-quiz-fwd')
  /**
  * Quiz App Initializr
  */
  this.Init = () => {
    Array.prototype.forEach.call(slides, (slide, i, list) => {
      slide.setAttribute('data-key', i)
      slide.setAttribute('data-answered', 'false')
      //lock slides
      if (i != 0)
        slide.setAttribute('data-locked', 'true')
      else
        slide.setAttribute('data-locked', 'false')
    })
    progression.innerHTML = currentIndex + ' / ' + slideAmount
    watchSlide(currentIndex);
    //fire impressions/view event
    cmEvent('Views', quizID)
  }

  /**
  * Watch Slide
  */
  const watchSlide = () => {
    if (
          currentIndex < slideAmount
          && currentNode.getAttribute('data-answered') == 'false' 
        )
      questionHandler()
  }

    //Back button listener & control
    function backControl() {
      backButton.addEventListener('click', (ev) => {
        if (backFlag === true) {
          explanationSkipped = true
          updateNavNodes('back')
        }
      })
    } backControl()

    //Next button listener & control
    function fwdControl() {
      fwdButton.addEventListener('click', (ev) => {
        if (fwdFlag === true) {
            explanationSkipped = true
            updateNavNodes('forward')
          }
      })
    } fwdControl()
  
    /**
     * updateNavNodes - determines navigation node tree of slides, recalculates positioning, triggers sequencing
     * @param {string} type 
     */
  function updateNavNodes(type) {
    //cleanup and resetting
    headerController.removeTheTime()
    progression.innerHTML = currentIndex + ' / ' + slideAmount
    //determine new arrangement of nodes
    if (type == 'forward' || type=='timer' && doesExist(nextNode) && nextNode.getAttribute('data-locked') == 'false') {
      currentIndex++
      prevNode = currentNode
      currentNode = (doesExist(currentNode.nextElementSibling)) ? currentNode.nextElementSibling : null
      nextNode = (doesExist(currentNode.nextElementSibling)) ? currentNode.nextElementSibling : null
      fwdButton.focus()
    } else if (type == 'back' && doesExist(prevNode)) {
      console.log("what???")
      currentIndex--
      nextNode = currentNode
      currentNode = (doesExist(currentNode.previousElementSibling)) ? currentNode.previousElementSibling : null
      prevNode = (doesExist(currentNode) && doesExist(currentNode.previousElementSibling)) ? currentNode.previousElementSibling : null
    }
    if(doesExist(currentNode))
      console.log(`%c Current ${currentNode.getAttribute('data-key')}`, 'color: green')
    if(doesExist(prevNode))
      console.log(`%c Prev ${prevNode.getAttribute('data-key')}`, 'color: green')
    if(doesExist(nextNode))
      console.log(`%c Next ${nextNode.getAttribute('data-key')}`, 'color: green')


    //if the next node is not locked, activate forwards
    if (doesExist(nextNode) && nextNode.getAttribute('data-locked') == 'false') {
      fwdFlag = true
      fwdButton.classList.add('is-active')
    } else {
      fwdFlag = false
      fwdButton.classList.remove('is-active')
    }
    //
    if (doesExist(prevNode)) {
      backFlag = true
      backButton.classList.add('is-active')
    } else {
      backFlag = false
      backButton.classList.remove('is-active')
    }
   
    //extend to manage reflow of active node visibly and
    function reflow() {
      //reset and set position of current slide
      function resetAndSetPosition(element, newPosition) {
        if(doesExist(element)) {
          element.classList.remove('translate-pos-100')
          element.classList.remove('translate-neg-100')
          element.classList.remove('is-active')
          element.classList.add(newPosition)
        }
      }
      resetAndSetPosition(prevNode, 'translate-neg-100')
      resetAndSetPosition(currentNode, 'is-active')
      resetAndSetPosition(nextNode, 'translate-pos-100')
    } reflow()

     //handle either ending sequence or activate the slide watcher
     if (currentIndex >= (slideAmount)) {
      endQuizSequence()
    } else {
      watchSlide()
    }
  }

  /**
  * End Quiz Sequence
  */
  const endQuizSequence = () => {
    //fire quiz completed
    cmEvent('completed', quizID)
    var haveEmail = false
    if (!haveEmail) {
      cmQuiz.getElementsByClassName('cm-quiz-prompt')[0].classList.add('is-active')
      haveEmail = true
    } else {
      this.generateResults()
    }
  }

  /**
  * Handler for Question & Answer Control
  */
  const questionHandler = () => {
    const currentSlide = slides[currentIndex],
         answers = currentSlide.querySelectorAll('.cm-quiz-slide-ans li')
    var answerChosen = false;
    [].forEach.call(answers, (e) => {
      e.addEventListener('click', (ev) => {
        if (answerChosen === false) {
          //fire question info event
          cmEvent('question', quizID, [currentSlide.querySelector('.cm-quiz-slide-q').innerHTML, e.innerHTML])
          feedBack(e, ev)
          answerChosen = true
          currentNode.setAttribute('data-answered', 'true')
          nextNode.setAttribute('data-locked', 'false')
          fwdButton.classList.add('is-active')
          fwdFlag = true
        }
      })
    })
    const feedBack = (e, ev) => {
      //remove listener as soon as clicked to prevent multiple clicks
      [].forEach.call(answers, (el) => {el.removeEventListener('click',feedBack)})
      //calc score
      if (Array.prototype.indexOf.call(answers, e) === scale[currentSlide.getAttribute('data-correct')]) {
        totalScore += qWeight
        let scoreShout = document.createElement('div')
          scoreShout.className = 'cm-quiz-shout'
          scoreShout.innerHTML = '+' + qWeight + ' Points!'
        answers[0].parentNode.style.display = 'none'
        cmQuiz.appendChild(scoreShout)
        setTimeout(function() { scoreShout.remove() }, 2400)
      }
      //display correct/incorrect
      let correct = Array.prototype.filter.call(answers, e2 => Array.prototype.indexOf.call(answers, e2) === scale[currentSlide.getAttribute('data-correct')])[0];
      let incorrect = Array.prototype.filter.call(answers, e2 => e2 !== correct);
      correct.classList.add('correct')

      correct.setAttribute('data-correct', 'correct');
      [].forEach.call(incorrect, i => {
        i.classList.add('incorrect')
        i.setAttribute('data-correct', 'incorrect')
      })
      //fire question answered event
      cmQuizStore.dispatch(addQuestion(currentIndex,currentSlide.querySelector('.cm-quiz-slide-q').innerHTML, e.innerHTML, e.getAttribute('data-correct')))
      cmEvent('answered', quizID)
      //display explanation transition
      explanationHandler(correct.innerHTML)
    }
  }

  /* *
  *  Explanation Handler
  */
  const explanationHandler = function(correctAnswerText) {
    //nodes & fields
    const
      exp = slides[currentIndex].querySelector('.cm-quiz-slide-exp'),
      expContent = exp.querySelector('.cm-quiz-slide-exp-s'),
      correctTitle = document.createElement('div')

    //display an ad on explanation pages
    displayAd(exp)

    //display correct answer
    correctTitle.classList = 'cm-quiz-slide-exp-correct'
    correctTitle.innerHTML = `Correct Answer: ${correctAnswerText}`
    exp.insertBefore(correctTitle, expContent)
    exp.classList.add('is-active')

    //determine length of time to allot for interstial slide
    const expTime = ((exp.innerText.split(' ').length) / 260 ) * 60 * 1000
    var expTimeSeconds = Math.round(expTime/1000)

    //Execute Timer Manager
     timeManager(exp, expTime, expTimeSeconds)
  }

  const headerController = {
    addTheTime() {
      theTime.classList.add('is-active')
      progression.style.display = 'none'
    },
    removeTheTime() {
      theTime.classList.remove('is-active')
      progression.style.display = 'flex'
    }
  }

  /**
   *  Time Manager
   *     Inserts and destroys {time sweep effect, timer countdown}
   *     Deactivatess the exp (explanation of current slide)
   * @param {node} exp
   * @param {double} expTime
   * @param {double} expTimeSeconds
   */
  const timeManager = (exp, expTime, expTimeSeconds) => {
    var
      timerSweepFlag = true,
      timer
    explanationSkipped = false

    //append timer
    //setup
      headerController.addTheTime()
      const insertTime = function() {
        //visual timer sweep
        if (timerSweepFlag == true) {
          timerSweepFlag = false

          var timer = document.createElement('DIV')
            timer.className = 'cm-quiz_timer-bar'
            timer.style.animationDuration = expTime + 'ms'
              exp.append(timer)

          //remove the timer sweep
          setTimeout(function() {
              timer.remove()
          }, expTime)
        }

        //manage timer
        if (expTimeSeconds <= 0) {
          updateNavNodes('timer')
          headerController.removeTheTime()
          return
        } else if (explanationSkipped) {
           clearTimeout(insertTime)
           return
        } else {
          theTime.innerHTML = expTimeSeconds
          expTimeSeconds--
          setTimeout(insertTime, 1000)
        }
      }
      //reset explanation skipped
      explanationSkipped = false
      insertTime()
  }

  /**
  * Generate Score Results for user
  */
  this.generateResults = () => {
    //fire subscribed event
    cmEvent('subscribed', quizID)
    cmQuiz.getElementsByClassName('cm-quiz-prompt')[0].style.transform = "translateX(-100%)"
    let results = cmQuiz.getElementsByClassName('cm-quiz-results')[0],
      resultsUL = document.createElement('ul'),
      resultsList = '<li><span>#</span><span>Question</span><span>Answer</span></li>';
    cmQuizStore.getState().questions.forEach((q) => {
      resultsList += `<li><span>${q.index}</span><span>${q.title}</span><span class="${q.correct}">${q.answer}</span></li>`
    })
    resultsUL.innerHTML = resultsList
    results.innerHTML = `<span class='cm-quiz_finalscore'>Score: ${totalScore}%</span><span class='cm-quiz_share-cta'>Share your score!</span>`
    results.appendChild(resultsUL)
    results.classList.remove('translate-pos-100')
    results.classList.add('is-active')
  }

  /**
   * Create and Display Google Adsense Unit
   */
  const displayAd = (currExp) => {
    const newAd = document.createElement('div')
      newAd.classList = 'cm-quiz_ad'
    const newAdIns = document.createElement('ins')
      newAdIns.classList = 'adsbygoogle'
      newAdIns.setAttribute('style', 'display:block')
      newAdIns.setAttribute('data-ad-client', 'ca-pub-4229549892174356')
      newAdIns.setAttribute('data-ad-slot', '5741416818')
      newAdIns.setAttribute('data-ad-format', 'horizontal')
    newAd.appendChild(newAdIns)
    currExp.insertBefore(newAd, currExp.children[0]);
    //push ad
    (adsbygoogle = window.adsbygoogle || []).onload = function () {
      adsbygoogle.push({})
    }
  }

} // End App

/*
* Run App
*/
function quizMain() {
  var cmQuiz = document.getElementById('cm-quiz')
  if (!doesExist(cmQuiz))
    return;
  //init quiz
  const quizApp = new quizAppCont(cmQuiz)
  quizApp.Init()
  //init form
  const formProceszr = new chromaFormHandler(quizApp.generateResults)
  formProceszr.init()
} quizMain()
