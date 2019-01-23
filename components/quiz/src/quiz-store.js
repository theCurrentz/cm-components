import merge from 'lodash/merge'

//init state
export var initState = {
  questions: {
  },
  count: 1,
  currentSlide: 0,
  previousSlide: 0,
  nextSlide: 0,
  slideLength: document.getElementsByClassName('cm-quiz-slide').length,
  currentProgress: 0
}
//store
export var cmQuizStore = Redux.createStore(questionTracker, initState)
export function render() {
  var renderState = cmQuizStore.getState()
}

//reducer
export function questionTracker(currentState = initState, action) {
  var nextState
  switch (action.type) {
    case 'question':
      nextState = Object.assign({}, currentState, {
        questions: [
          ...currentState.questions,
          {
            index: action.questions.index,
            title: action.questions.title,
            answer: action.questions.answer,
            correct: action.questions.correct
          }
        ]
      })
      return nextState
      break;
    default:
      return currentState
  }
}
