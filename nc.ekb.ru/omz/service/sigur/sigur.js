setTimeout(function () {
  document.getElementById('/*')
    .addEventListener('click', clicker)
  updateCounts()
  document.querySelector('fieldset:has(input[type=date]) a')
    .onclick = dateDrop
  installDates()
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
      break
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
  var div = document.getElementById(cb.name.replace('?', '/'))
  setTimeout(updateCounts)
  if (!div) return
  var cbs = div.getElementsByTagName('input')
  for (var i = cbs.length - 1; i >= 0; i--) {
    var z = cbs[i]
    if (z.disabled) continue
    z.checked = cb.checked
  }
}

function countDepts() {
  var cbs = document.getElementById('/*').querySelectorAll('input[type=checkbox]')
  var found = 0
  var checked = 0
  for (var i = cbs.length - 1; i >= 0; i--) {
    var z = cbs[i]
    if (z.disabled) continue
    found++
    if (z.checked) checked++
  }
  return [checked, found]
}

function updateCounts() {
  var counts = countDepts()
  document.querySelector('form button[type=submit]').disabled = counts[0] == 0
  var s = document.querySelector('fieldset > legend span')
  if (s) s.innerText = counts.join('/')
}

function dateDrop(ev) {
  ev.preventDefault()
}

function installDates() {
  var handlers = [
    skip,
    thisMonth, prevMonth, theMonth,
    thisWeek, prevWeek, theWeek,
    dayA, dayZ, theDay]
  for (var a of Array.from(document.getElementById('datez').getElementsByTagName('a'))) {
    a.onclick = (function (handler) {
      return function (ev) {
        ev.preventDefault()
        handler.call(this, ev)
      }
    })(handlers.shift() || skip)
  }
}

function datePicker(fn) {
  var f = document.forms[0]
  var z = document.createElement('input')
  z.type = 'date'
  z.value = f.dZ.value
  z.min = f.dA.min
  if (z.min > f.dZ.min) z.min = f.dZ.min
  z.max = f.dA.max
  if (z.max < f.dZ.max) z.max = f.dZ.max
  z.onchange = fn
  z.showPicker()
  return z
}

function skip() {
  return false
}

function d2s(date) {
  return date.toISOString().replace(/T.*/, '')
}

function setMonth(date) {
  var f = document.forms[0]
  var d = new Date(date)
  d.setDate(1)
  f.dA.value = d2s(d)
  d.setMonth(d.getMonth() + 1)
  d.setDate(0)
  f.dZ.value = d2s(d)
}

function thisMonth() {
  setMonth(new Date)
}

function prevMonth() {
  var d = new Date
  d.setMonth(d.getMonth() - 1)
  setMonth(d)
}

function theMonth() {
  datePicker(function () {
    setMonth(this.value)
  })
}

function setWeek(date) {
  var f = document.forms[0]
  var d = new Date(date)
  d.setDate(d.getDate() - (d.getDay() + 6) % 7)
  f.dA.value = d2s(d)
  d.setDate(d.getDate() + 6)
  f.dZ.value = d2s(d)
}

function thisWeek() {
  setWeek(new Date)
}

function prevWeek() {
  var d = new Date
  d.setDate(d.getDate() - 7)
  setWeek(d)
}

function theWeek() {
  datePicker(function () {
    setWeek(this.value)
  })
}

function dayA() {
  var f = document.forms[0]
  f.dZ.value = f.dA.value
}

function dayZ() {
  var f = document.forms[0]
  f.dA.value = f.dZ.value
}

function theDay() {
  datePicker(function () {
    var f = document.forms[0]
    f.dZ.value = f.dA.value = d2s(new Date(this.value))
  })
}
