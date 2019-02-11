/**
 * Utility Functions
 */
function doesExist(el) {
  if (el !== null && typeof el !== 'undefined')
    return true
}
function getRandomInt(max) {
  return Math.floor(Math.random() * Math.floor(max));
}

export {doesExist,  getRandomInt}
