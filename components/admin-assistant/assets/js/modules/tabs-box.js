export const tabsBox = () => {
// query all controllers as class, then query child tabs
  let tabControllers = document.getElementsByClassName('chroma_tabs_controller');
  if (tabControllers.length > 0) {
    [].forEach.call(tabControllers, (e) => {
      let tab = e.getElementsByClassName('chroma_tabs')[0]
      if (typeof tab != 'undefined') {
        let tabButtons = e.getElementsByTagName('span'),
            tabBoxes = tab.getElementsByClassName('chroma_tabs_box'),
            tabsLength = tabButtons.length - 1
        for(let i = 0, l = tabsLength; i <= l; i++) {
            tabButtons[i].addEventListener('click', (event) => {
              event.preventDefault()
              let index = i
              for (let j = 0, l = tabsLength; j <= l; j++) {
                if (tabBoxes[j].classList.contains('first'))
                  tabBoxes[j].classList.remove('first')
                if (j != index) {
                  tabButtons[j].classList = ''
                  tabBoxes[j].classList.remove('is_active')
                }
              }
              tabButtons[index].classList = 'is_active'
              tabBoxes[index].classList.add('is_active')
            })
        }
      }
    })
  }
}
