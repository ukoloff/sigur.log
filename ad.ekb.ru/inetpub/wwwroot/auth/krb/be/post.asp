<%@Language='JScript'%>
<%
var t = Request.Form('tIcKeT')
if (t.Count == 1) {
  var X = Application.Contents

  t = t(1)
  Response.Write(X(t))
  X.Remove(t)
  X.Remove(':' + t)
}
%>
