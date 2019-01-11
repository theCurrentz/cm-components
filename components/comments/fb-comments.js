'use strict'
class commentCreater {
  //create component
  constructor() {
    this.flag = true
    this.body = document.getElementsByTagName('body')[0]
    this.comments = document.getElementById('comments')
    this.commentsOverlay = document.getElementsByClassName('comments_overlay')[0]
    this.commentsClose = document.getElementsByClassName('comments_close')[0]
    this.commentsInner = document.getElementById('commments-in')
    this.page = (doesExist(document.querySelector('meta[property="og:url"]')))
      ? document.querySelector('meta[property="og:url"]').getAttribute('content')
      : window.location
  }
  create(targetsParent) {
    if(this.flag) {
      this.flag = false
      if(doesExist(document.querySelector('.fb-comments'))) {
        let fbComments = document.querySelector('.fb-comments'),
            fbroot = document.createElement('div'),
            fbi = document.createElement('script')

        fbComments.setAttribute('data-href', this.page)
        fbComments.setAttribute('data-width', '100%')
        fbComments.setAttribute('data-numposts', '20')

        fbroot.id = 'fb-root'
        fbi.src = `https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=${chromaApp.fbAppID}&version=v2.0&autoLogAppEvents=1`

        document.body.appendChild(fbroot)
        document.body.appendChild(fbi)
      }
      //comment close listeners
      this.commentsClose.addEventListener('click', (ev) => {
        this.comments.classList.toggle('is-active')
        this.commentsOverlay.classList.toggle('is-active')
      })
      this.commentsOverlay.addEventListener('click', (ev) => {
        this.comments.classList.toggle('is-active')
        this.commentsOverlay.classList.toggle('is-active')
      })
    }
    //toggle active
    this.comments.classList.toggle('is-active')
    this.commentsOverlay.classList.toggle('is-active')
  }

  //comment button listener
  commentTrigger() {
    const commentTriggers = document.querySelectorAll('.comments-icon');
    if (doesExist(commentTriggers)) {
      [].forEach.call(commentTriggers, (e) => {
        //reposition comment icon if awkward
        if (e.id != 'comments-anchor') {
          if (e.nextSibling != null && e.nextSibling.nodeName != 'P')
            e.classList.add('expand')
          else if( !e.parentNode.classList.contains('entry-content')) {
            e.classList.add('expand')
            document.querySelector('.entry-content').appendChild(e)
          }
        }
        e.addEventListener('click', (ev) => {
          ev.preventDefault()
          this.create()
        })
      })
    }
  }
}

const theComments = new commentCreater()
theComments.commentTrigger()
