<%@Language='JScript'%>
<%
var Q = Server.CreateObject("MSXML2.ServerXMLHTTP")

Q.open('POST', 'https://ad.ekb.ru/auth/krb/be/', false)
Q.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
Q.send('tIcKeT=' + Server.URLEncode(Request.QueryString('TiCkEt')(1)))

%>
<%= Q.responseText %>
<ul>
<li><a href="./?dev">
  Start over
</a>
<li><a href="./?vars">
  See Server vars
</a>
</ul>
