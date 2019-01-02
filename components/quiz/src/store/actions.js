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
export function countSlide(count) {
  return {
    type: 'count',
    visibleSlide: count + 1
  }
}
