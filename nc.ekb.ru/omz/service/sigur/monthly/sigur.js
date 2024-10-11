!function () {
  setTimeout(init, 10)

  function init() {
    var f = document.forms[0]
    var d = f.querySelector('input[type=date]')
    d.onchange = date
    var links = f.querySelectorAll('form>small>a')
    var handlers = [skip, skip, skip]
    for (var a of links) {
      a.onclick = (function (handler) {
        a.href='#'
        return function (ev) {
          ev.preventDefault()
          handler.call(this, ev)
        }
      })(handlers.shift() || skip)
    }
  }

  function date() {
    console.log(this, this.value)
  }

  function skip(ev) {
    console.log('Click!')
  }
}()
