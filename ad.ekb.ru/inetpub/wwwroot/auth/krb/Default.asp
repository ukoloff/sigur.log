<%@Language='JScript'%>
<table border cellspacing=0>
<%
for (var E = new Enumerator(Request.ServerVariables); !E.atEnd(); E.moveNext())
    Response.Write('<tr><th>' + E.item() + '</th><td>' + Request.ServerVariables(E.item()) + '</td></tr>');
%>
</table>
