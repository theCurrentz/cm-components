//action creators
export function addQuestion(index, question, answer, correct) {
  return {
    type: 'question',
    questions: {
      index: index,
      title: question,
      answer: answer,
      correct: correct
    }
  }
}

export function slideSettings(currentSlide, previousSlide, nextSlide, currentProgress) {
  return {
    type: 'slideSettings',
    currentSlide: 0,
    previousSlide: 0,
    nextSlide: 0,
    currentProgress: 0
  }
}
