<%@Language='JScript'%>
<%
var param = String(Request.QueryString)
switch (param) {
  case 'warn':
  case 'dev':
    Server.Execute(param + '.asp')
}
%>
