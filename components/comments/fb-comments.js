'use strict'
class fbComments {
  //create component
  createComments(targetsParent) => {
    if (store.getState().commentStatus)
      return;
    store.dispatch({
      type : 'commentOn'
    })
    var commentsTitle = document.createElement('span'),
        commentsClose = document.createElement('button'),
        disqus = document.createElement('div'),
        body = document.getElementsByTagName('body')[0]
    disqus.id = 'disqus_thread'
    comments = document.createElement('div')
    comments.classList.add('comments-area')
    comments_overlay = document.createElement('div')
    comments_overlay.classList.add('comments_overlay')
    commentsTitle.classList.add('comments_title')
    commentsClose.setAttribute('class', 'comments_close box-shadow-default')
    commentsTitle.innerHTML = 'Comments'
    commentsTitle.appendChild(commentsClose)
    comments.appendChild(commentsTitle)
    comments.appendChild(disqus)
    comments.id = "comments"
    comments.setAttribute('name', 'comments')
    body.appendChild(comments_overlay)
    body.appendChild(comments)
    //create disqus script
    this.disqus()
    //comment close listeners
    commentsClose.addEventListener('click', (ev) => {
      comments.classList.toggle('is-active')
      comments_overlay.classList.toggle('is-active')
    })
    comments_overlay.addEventListener('click', (ev) => {
      comments.classList.toggle('is-active')
      comments_overlay.classList.toggle('is-active')
    })
  }

  fb() {
    //disqus data is set up seperately via localize script
    let disqus_shortname = 'idropnews'
    if (!disqus_loaded)  {
      disqus_loaded = true
      var e = document.createElement("script")
      e.type = "text/javascript"
      e.async = true
      e.src = "//" + disqus_shortname + ".disqus.com/embed.js";
      (document.getElementsByTagName("head")[0] ||
      document.getElementsByTagName("body")[0])
      .appendChild(e);
    }
  }

  //comment button listener
  static commentTrigger() {
    var commentTriggers = document.querySelectorAll('.comments-icon, #comments-anchor');
    if (doesExist(commentTriggers)) {
      [].forEach.call(commentTriggers, (e) => {
        //reposition comment icon if awkward
        if (e.id != 'comments-anchor') {
          if (e.nextSibling != null && e.nextSibling.nodeName != 'P')
            e.classList.add('expand')
          else if(e.parentNode.id !== 'content-main') {
            e.classList.add('expand')
            document.getElementById('content-main').appendChild(e)
          }
        }
        e.addEventListener('click', (ev) => {
          ev.preventDefault()
          this.createCommentComponent(ev.target.parentNode)
          if (comments != null) {
            comments.classList.toggle('is-active')
            comments_overlay.classList.toggle('is-active')
          }
        })
      })
    }
  }
}
