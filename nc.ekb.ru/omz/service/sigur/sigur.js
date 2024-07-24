setTimeout(function () {
  document.getElementById('/*')
    .addEventListener('click', clicker)
}, 100)

function clicker(ev) {
  var el = ev.srcElement
  switch (el.tagName) {
    case 'A':
      switch (el.className) {
        case 'Q':
          ev.preventDefault()
          return clickExpander(el)
      }
    case 'INPUT':
      switch (el.type) {
        case 'checkbox':
          return clickCB(el)
      }
  }
}

function clickExpander(a) {
  a.blur()
  var div = document.getElementById(a.id.replace(':', '/'))
  if (!div) return
  if (a.innerText == '+') {
    a.innerText = '-'
    div.classList.remove('hide')
  } else {
    a.innerText = '+'
    div.classList.add('hide')
  }
}

function clickCB(cb) {
  cb.blur()
  var div = document.getElementById(cb.id.replace('%', '/'))
  if (!div) return
  var cbs = div.getElementsByTagName('input')
  for (var i = cbs.length - 1; i >= 0; i--) {
    var z = cbs[i]
    if (z.disabled) continue
    z.checked = cb.checked
  }

}
