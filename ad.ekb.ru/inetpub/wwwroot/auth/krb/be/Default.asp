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
%>
