<%@Language='JScript'%>
<%
Response.ContentType = 'text/json'
Response.CodePage = 65001
Response.CharSet = "UTF-8"

var t = Request.Form('tIcKeT')
if (t.Count == 1) {
  var X = Application.Contents

  t = t(1)
  Response.Write(X(t))
  X.Remove(t)
  X.Remove(':' + t)
}
%>
