'use strict'
import './style.sass'
import { createStore } from 'redux';
import {addQuestion, countSlide} from './store/actions'
import './quiz-store.js'
import {initState, cmQuizStore, render, questionTracker} from './quiz-store.js'
cmQuizStore.subscribe(render)

//utilities
function doesExist(el) {
  if (el !== null && typeof el !== 'undefined')
    return true
}
function getRandomInt(max) {
  return Math.floor(Math.random() * Math.floor(max));
}

//quiz scene
const setScene = function() {
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
    let choice = `${darken}linear-gradient(335deg,${gradientArray[getRandomInt(5)]})`
    targetNode.style.background = choice
    console.log(choice)
  } catch (e) {
      console.log(e.message)
    }
}
setScene()

//quiz app controller
const quizAppCont= function(cmQuiz) {
  if ( !(document.getElementsByClassName('cm-quiz-slide').length > 0))
    return
  var quizID = cmQuiz.getAttribute('data-id'),
      quizBox = document.getElementsByClassName('cm-quiz-box')[0],
      slides = document.getElementsByClassName('cm-quiz-slide'),
      backButton = document.getElementById('cm-quiz-back'),
      fwdButton = document.getElementById('cm-quiz-fwd'),
      progression = document.getElementById('cm-quiz-prog'),
      slideAmount = slides.length,
      qWeight = Math.round(100 / slideAmount),
      currentIndex = 0,
      totalScore = 0,
      scale = {a: 0, b:1, c:2, d:3}
  this.Init = () => {
    progression.innerHTML = currentIndex + ' / ' + slideAmount
    this.watchSlide(currentIndex);
    //fire impressions/view event
    cmEvent('Views', quizID)
  }
  this.watchSlide = () => {
    if (currentIndex >= (slideAmount)) {
    } else {
      this.questionHandler()
    }
  }
  this.questionTransition = () => {
    slides[currentIndex].style.transform = 'translateX(-100%)'
    currentIndex++
    progression.innerHTML = currentIndex + ' / ' + slideAmount
    var nextSlide = slides[currentIndex]
    if (currentIndex >= (slideAmount)) {
      this.endQuizSequence()
    } else {
      nextSlide.classList.add('is-active')
      this.watchSlide()
    }
  }
  this.endQuizSequence = () => {
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
    results.classList.add('is-active')
  }
  this.questionHandler = () => {
    var currentSlide = slides[currentIndex],
         answers = currentSlide.querySelectorAll('.cm-quiz-slide-ans li'),
         answerChosen = false;
    [].forEach.call(answers, (e) => {
      e.addEventListener('click', (ev) => {
        if (answerChosen === false) {
          //fire question info event
          cmEvent('question', quizID, [currentSlide.querySelector('.cm-quiz-slide-q').innerHTML, e.innerHTML])
          feedBack(e, ev)
          answerChosen = true
        }
      })
    })
    var feedBack = (e, ev) => {
      //remove listener as soon as clicked to prevent multiple clicks
      [].forEach.call(answers, (el) => {el.removeEventListener('click',feedBack)})
      //calc score
      if (Array.prototype.indexOf.call(answers, e)  === scale[currentSlide.getAttribute('data-correct')]) {
        totalScore += qWeight
        let scoreShout = document.createElement('div')
          scoreShout.className = 'cm-quiz-shout'
          scoreShout.innerHTML = '+' + qWeight + ' Points!'
        cmQuiz.appendChild(scoreShout)
        setTimeout(function() { scoreShout.remove() }, 2000)
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
      this.explanationHandler()
    }
  }
  this.explanationHandler = () => {
    var exp = slides[currentIndex].querySelector('.cm-quiz-slide-exp')
      exp.classList.add('is-active')
    //display an ad on explanation pages
    //determine length of time to allot for interstistial slide
    var expTime = ((exp.innerText.split(' ').length) / 213 ) * 60 * 1000
    //construct explanation slide
    var expTimeS = Math.round(expTime/1000)
    this.displayAd(exp)
    //append timer
    var insertTime = () =>  {
      document.getElementsByClassName('cm-quiz_timer')[0].classList.add('is-active')
      if (expTimeS <= 0 ) {
        document.getElementsByClassName('cm-quiz_timer')[0].classList.remove('is-active')
        clearTimeout(insertTime)
        return
      }
      else {
        //manage timer
        var theTime = document.getElementsByClassName('cm-quiz_timer')[0]
        theTime.innerHTML = expTimeS
        expTimeS--
        setTimeout(insertTime, 1000)
      }
    }
    //end the exp
    var endExplanation = () =>  {
      this.questionTransition();
      //exp.classList.remove('is-active');
     // timer.remove()
    }
    //append explanation slide
    insertTime()
    var theTime = 1000
    var timer = document.createElement('DIV')
    timer.className = 'cm-quiz_timer-bar'
    timer.style.animationDuration = expTime + 'ms'
    slides[currentIndex].querySelector('.cm-quiz-slide-exp').append(timer)
    window.setTimeout(function() {
      endExplanation()
    }.bind(this), expTime)
  }
  this.displayAd = (currExp) => {
    let newAd = document.createElement('div'),
        newAdIns = document.createElement('ins')
    newAd.classList = 'cm-quiz_ad'
    newAdIns.classList = 'adsbygoogle'
    newAdIns.setAttribute('style', 'display:block')
    newAdIns.setAttribute('data-ad-client', 'ca-pub-4229549892174356')
    newAdIns.setAttribute('data-ad-slot', '5741416818')
    newAdIns.setAttribute('data-ad-format', 'horizontal')
    newAd.appendChild(newAdIns)
    currExp.insertBefore(newAd, currExp.children[0]);
    (adsbygoogle = window.adsbygoogle || []).onload = function () {
      adsbygoogle.push({})
    }
  }
  //back button
  this.quizNav = () => {
    var navFlag = null,
      current = slides[currentIndex]
    //BACK LOGIC
    backButton.addEventListener('click', (ev) => {
      if  (navFlag === 'back')
        current = current.previousElementSibling
      else
        current = slides[currentIndex]
      if (doesExist(current.previousElementSibling)) {
        current.classList.remove('is-active')
        current.previousElementSibling.style.transform = 'translateX(0px)'
        current.previousElementSibling.classList.add('is-active')
        navFlag = 'back'
        fwdButton.classList.add('is-active')
        console.log(current)
      }
    })
    //FORWARDS LOGIC
    fwdButton.addEventListener('click', (ev) => {
      if  (navFlag === 'fwd')
        current = current.nextElementSibling
      else
        current = slides[currentIndex]
      if (doesExist(current.nextElementSibling)) {
        current.classList.remove('is-active')
        current.nextElementSibling.style.transform = 'translateX(0px)'
        current.nextElementSibling.classList.add('is-active')
        navFlag = 'fwd'
        console.log(current)
      }
    })
  }
  this.quizNav()

}

import fbInitializer from "/var/www/html/wp-content/plugins/cm-components/components/forms/signup/facebook-api-init.js"
import chromaFormHandler from '/var/www/html/wp-content/plugins/cm-components/components/forms/signup/form-action.js'
import cmEvent from './cm-analytics.js'
function quizMain() {
  var cmQuiz = document.getElementById('cm-quiz')
  if (!doesExist(cmQuiz))
    return;
  //init quiz
  const quizApp = new quizAppCont(cmQuiz)
  quizApp.Init()
  //init fb api and form
  const fbApiInit = new fbInitializer()
  const formProceszr = new chromaFormHandler(quizApp.generateResults)
  formProceszr.init()
} quizMain()
