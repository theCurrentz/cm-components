'use strict'
import './style.sass'
import { createStore } from 'redux';
import {addQuestion} from './store/actions'
import {initState, cmQuizStore, render, questionTracker} from './quiz-store.js'
import fbInitializer from "/var/www/html/wp-content/plugins/cm-components/components/forms/signup/facebook-api-init.js"
import chromaFormHandler from '/var/www/html/wp-content/plugins/cm-components/components/forms/signup/form-action.js'
import cmEvent from './cm-analytics.js'
import {doesExist, getRandomInt} from './utility/functions.js'
import {setScene} from "./scene/set-scene.js"
cmQuizStore.subscribe(render)

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
    qWeight = 100 / slideAmount,
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
    fwdButton = document.getElementById('cm-quiz-fwd'),
    questionHandlerActive = false
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
    progression.innerHTML = currentIndex + 1 + ' / ' + slideAmount
    watchSlide(currentIndex);
    //fire impressions/view event
    cmEvent('Views', quizID)
    fbq('track', 'Lead', {
       content_name: 'Quiz',
       content_category: 'Viewed',
       value: 0.0,
       currency: 'USD'
     });
  }

  /**
  * Watch Slide
  */
  const watchSlide = () => {
    if (
          currentIndex < slideAmount
          && currentNode.getAttribute('data-answered') == 'false'
          && !questionHandlerActive
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
    //determine new relative arrangement of nodes
    if (type == 'forward' || type=='timer' && doesExist(nextNode) && nextNode.getAttribute('data-locked') == 'false') {
      currentIndex++
      prevNode = currentNode
      currentNode = (doesExist(currentNode.nextElementSibling)) ? currentNode.nextElementSibling : null
      nextNode = (doesExist(currentNode.nextElementSibling)) ? currentNode.nextElementSibling : null
      fwdButton.focus()
    } else if (type == 'back' && doesExist(prevNode)) {
      currentIndex--
      nextNode = currentNode
      currentNode = (doesExist(currentNode.previousElementSibling)) ? currentNode.previousElementSibling : null
      prevNode = (doesExist(currentNode) && doesExist(currentNode.previousElementSibling)) ? currentNode.previousElementSibling : null
    }

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

    //reset and set position of current slide
    function resetAndSetPosition(element, newPosition) {
      if(doesExist(element)) {
        element.classList.remove('translate-pos-100')
        element.classList.remove('translate-neg-100')
        element.classList.remove('is-active')
        element.classList.add(newPosition)
      }
    }
    //extend to manage rerender of active node
    (function() {
      resetAndSetPosition(prevNode, 'translate-neg-100')
      resetAndSetPosition(currentNode, 'is-active')
      resetAndSetPosition(nextNode, 'translate-pos-100')
      //cleanup and resetting
      headerController.removeTheTime()
      if (currentIndex < slideAmount)
        progression.innerHTML = currentIndex + 1 + ' / ' + slideAmount
    })()

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
    questionHandlerActive = true
    const currentSlide = slides[currentIndex],
         answers = currentSlide.querySelectorAll('.cm-quiz-slide-ans li')
    var answerChosen = false;
    Array.prototype.forEach.call(answers, (e) => {
      e.addEventListener('click', (ev) => {
        if (answerChosen === false) {
          //fire question info event
          if (currentIndex === 0) {
            fbq('track', 'Lead', {
               content_name: 'Quiz',
               content_category: 'Started',
               value: 0.0,
               currency: 'USD'
             });
          }
          cmEvent('question', quizID, [currentSlide.querySelector('.cm-quiz-slide-q').innerHTML, e.innerHTML])
          answerChosen = true
          currentNode.setAttribute('data-answered', 'true')
          nextNode.setAttribute('data-locked', 'false')
          fwdButton.classList.add('is-active')
          fwdFlag = true
          feedBack(e, ev)
        }
      })
    })
    const feedBack = (e, ev) => {
      //remove listener as soon as clicked to prevent multiple clicks
      Array.prototype.forEach.call(answers, (el) => {el.removeEventListener('click',feedBack)})
      //hide question block
      answers[0].parentNode.style.display = 'none'
      answers[0].parentNode.previousElementSibling.style.display = 'none'
      //calc score
      if (Array.prototype.indexOf.call(answers, e) === scale[currentSlide.getAttribute('data-correct')]) {
        totalScore += qWeight
        // let scoreShout = document.createElement('div')
        //   scoreShout.className = 'cm-quiz-shout'
        //   scoreShout.innerHTML = '+' + Math.round(qWeight) + ' Points!'
        // cmQuiz.appendChild(scoreShout)
        // setTimeout(function() { scoreShout.remove() }, 2400)
      }
      //display correct/incorrect
      let correct = Array.prototype.filter.call(answers, e2 => Array.prototype.indexOf.call(answers, e2) === scale[currentSlide.getAttribute('data-correct')])[0];
      let incorrect = Array.prototype.filter.call(answers, e2 => e2 !== correct);
      correct.classList.add('correct')

      correct.setAttribute('data-correct', 'correct');
      Array.prototype.forEach.call(incorrect, i => {
        i.classList.add('incorrect')
        i.setAttribute('data-correct', 'incorrect')
      })
      //fire question answered event
      cmQuizStore.dispatch(addQuestion(currentIndex,currentSlide.querySelector('.cm-quiz-slide-q').innerHTML, e.innerHTML, e.getAttribute('data-correct')))
      cmEvent('answered', quizID)
      //display explanation transition
      explanationHandler(correct.innerHTML)
      questionHandlerActive = false
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
  this.generateResults = (duplicateEmail = false) => {
    nextNode.setAttribute('data-locked', 'false')
    updateNavNodes('forward')
    //fire subscribed event
    if (!duplicateEmail) {
      cmEvent('subscribed', quizID)
    } else {
      cmEvent('duplicateSubscribe', quizID)
    }
    //fire fb event if quiz has been completed and user has subscribed
    fbq('track', 'Lead', {
      content_name: 'Quiz',
      content_category: 'Completed',
      value: 0.0,
      currency: 'USD'}
    );
    //fire
    //fire gleam Quiz Completion Event
    // const Gleam = Gleam || []
    // Gleam.push(['quiz', document.querySelector('.cm-quiz-title').innerHTML])
    //render results
    let results = cmQuiz.getElementsByClassName('cm-quiz-results')[0]
    if (!doesExist(document.getElementById('cm-quiz-results-ul'))) {
        let resultsUL = document.createElement('ul'),
        resultsList = '<li><span>#</span><span>Question</span><span>Answer</span></li>';
      cmQuizStore.getState().questions.forEach((q) => {
        resultsList += `<li><span>${q.index}</span><span>${q.title}</span><span class="${q.correct}">${q.answer}</span></li>`
      })
      resultsUL.id = 'cm-quiz-results-ul'
      resultsUL.innerHTML = resultsList
      results.appendChild(resultsUL)
    }
    results.querySelector('.cm-quiz_finalscore').innerHTML = `Score: ${Math.round(totalScore)}%`
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
    currExp.appendChild(newAd);
    //append spacer
    const spacer = document.createElement('div')
    spacer.classList = 'cm-quiz_spacer'
    currExp.insertBefore(spacer, currExp.firstChild);
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
