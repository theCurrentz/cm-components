//init state
export var initState = {
  questions: {
  },
  count: 1
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
    case 'count':
      nextState = currentState.count + action.count
      return nextState
      break;
    default:
      return currentState
  }
}
//action handlers
// [].forEach.call(document.querySelectorAll('button[data-share="comment"]'), (e) => {
//   e.addEventListener('click', (ev) => {
//     cmQuizStore.dispatch({
//       type : 'commentOn'
//     })
//   })
// })
