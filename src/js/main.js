function component() {
    var element = document.createElement('div');
    element.innerHTML = 'Hello';
    return element;
}
console.log();
document.body.appendChild(component());