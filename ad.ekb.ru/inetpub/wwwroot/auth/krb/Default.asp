<%@Language='JScript'%>
<%
for (var E = new Enumerator(Request.ServerVariables); !E.atEnd(); E.moveNext())
    Response.Write(E.item() + '=' + Request.ServerVariables(E.item()) + '\n');
%>
