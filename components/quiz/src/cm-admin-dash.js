'use strict'

new Vue({
  el: '#app',
  data () {
    return {
      headers: [
        {
          text: 'Quizzes',
          align: 'left',
          sortable: true,
          value: 'title'
        },
        { text: 'Views', value: 'views' },
        { text: 'Answered', value: 'answered' },
        { text: 'Completed', value: 'question' },
        { text: 'Subscribed', value: 'subscribed' },
        { text: 'Duplicate Sub', value: 'duplicateSubscribe' },
      ],
      quizzes: cmQuiz.quizzes,
      pagination: {
        sync: {
          rowsPerPage: -1
        }
      }
    }
  }
})
