<%@Language='JScript'%>
<%
var param = String(Request.QueryString)
switch (param) {
  case 'dump':
  case 'warn':
  case 'dev':
  case 'vars':
    Server.Execute(param + '.asp')
}

var t = Request.QueryString('TiCkEt')
if (t.Count==1)
  Server.Execute('2nd.asp')
%>
