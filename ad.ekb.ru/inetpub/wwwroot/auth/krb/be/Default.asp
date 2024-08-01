<%@Language='JScript'%>
<%
Server.Execute('expire.asp')

var param = String(Request.QueryString)
switch (param) {
  case 'dump':
  case 'warn':
  case 'dev':
  case 'vars':
    Server.Execute(param + '.asp')
    break;
  default:
    if (Request.ServerVariables("REQUEST_METHOD") == "POST")
      Server.Execute('post.asp')
    else if (Request.QueryString('TiCkEt').Count == 1)
      Server.Execute('2nd.asp')
}

%>
