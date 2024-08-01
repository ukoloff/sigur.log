<%@Language='JScript'%>
<%
var old = []
var now = (new Date()).getTime()
var X = Application.Contents

for (var E = new Enumerator(X); !E.atEnd(); E.moveNext()) {
  var k = E.item()
  if (/^:/.test(k)) {
    if (X(k.substr(1)) && /^\d+$/.test(X(k)) && now < X(k)) continue
    old.push(k.substr(1))
  } else if (X(':' + k))
    continue
  old.push(k)
}

for (var i = old.length - 1; i >= 0; i--)
  X.Remove(old[i])

%>
