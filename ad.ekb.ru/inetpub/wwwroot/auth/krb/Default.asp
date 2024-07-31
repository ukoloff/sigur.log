<%@Language='JScript'%>
<pre>
<%
for (var E = new Enumerator(Request.ServerVariables); !E.atEnd(); E.moveNext())
    Response.Write('<li>' + E.item() + '=' + Request.ServerVariables(E.item()) + '\n');
%>
