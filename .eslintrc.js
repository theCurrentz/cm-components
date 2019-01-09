module.exports = {
  "parser": "babel-eslint",
  "rules": {
    "strict": 0,
    'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'no-eval': 'off',
    'no-new': 'off'
  }
}
