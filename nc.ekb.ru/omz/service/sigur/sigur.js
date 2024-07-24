setTimeout(function () {
  document.getElementById('/*')
    .addEventListener('click', clicker)
}, 100)

function clicker(ev) {
  var el = ev.srcElement
  if (el.tagName == 'A' && el.className == 'Q') {
    ev.preventDefault()
    clickExpander(el)
  }
}

function clickExpander(el) {
  el.blur()
  var div = document.getElementById(el.id.replace(':', '/'))
  if (!div) return
  if (el.innerText == '+') {
    el.innerText = '-'
    div.classList.remove('hide')
  } else {
    el.innerText = '+'
    div.classList.add('hide')
  }
}
