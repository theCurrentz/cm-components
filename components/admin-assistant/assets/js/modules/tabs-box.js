export const tabsBox = () => {
  let tabs = (document.getElementById('chroma_tabs_controller') != null) ? document.getElementById('chroma_tabs_controller').children : null
  if (typeof tabs != 'undefined' && tabs != null) {
    let tabsLength = tabs.length - 1,
        tabBoxes = document.getElementsByClassName('chroma_tabs_box')
    for(let i = 0, l = tabsLength; i <= l; i++) {
        tabs[i].addEventListener('click', (e) => {
          e.preventDefault()
          let index = i
          for(let j = 0, l = tabsLength; j <= l; j++) {
            if(tabBoxes[j].classList.contains('first'))
              tabBoxes[j].classList.remove('first')
            if(j != index) {
              tabs[j].classList = ''
              tabBoxes[j].classList.remove('is_active')
            }
          }
          tabs[index].classList = 'is_active'
          tabBoxes[index].classList.add('is_active')
        })
    }
  }
}
