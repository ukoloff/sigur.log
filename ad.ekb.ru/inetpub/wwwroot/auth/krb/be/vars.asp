<%@Language='JScript'%>
<table border cellspacing=0>
<%
for (var E = new Enumerator(Application.Contents); !E.atEnd(); E.moveNext())
  Response.Write('<tr><th>' + E.item() + '</th><td>' + Application.Contents(E.item()) + '</td></tr>');
%>
</table>
