export function formatOptionsHandler() {
  if(document.getElementById('#chromma-format_options_1') && document.getElementById('#chromma-format_options_2')) {
    const checkChecked = () => {
      (this.hasAttribute('checked')) ? this.removeAttribute('checked') : this.setAttribute('checked', 'true')
    }
    // toggle checked attribute for format options
    console.log(document.getElementById('#chromma-format_options_1'))
    document.getElementById('#chromma-format_options_1').addEventListener('click', () => {
      checkChecked()
    })
    document.getElementById('#chromma-format_options_2').addEventListener('click', () => {
      checkChecked()
    })
  }
}
