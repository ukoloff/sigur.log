<%@Language='JScript'%>
<%
var jsonEsc = { "\n": 'n', "\r": 'r' }

switch ('' + Request.QueryString) {
  case 'dump':
    dump()
    break
  case 'auth':
    auth()
    break
}

function dump() {
  Response.Write('<table border cellspacing=0>')
  for (var E = new Enumerator(Request.ServerVariables); !E.atEnd(); E.moveNext())
    Response.Write('<tr><th>' + E.item() + '</th><td>' + Request.ServerVariables(E.item()) + '</td></tr>');
  Response.Write('</table>')
}

function auth() {
  var z = Request.ServerVariables
  Response.Write(r2j({
    auth: z('AUTH_TYPE'),
    user: z('AUTH_USER'),
    ip: z('REMOTE_ADDR'),
    ua: z('HTTP_USER_AGENT'),
    blob: z('HTTP_AUTHORIZATION')(1).split(/\s+/, 2)[1]
  }))
}

function c2j(char) {
  return "\\" + (jsonEsc[char] || char)
}

function s2j(str) {
  return str == null ?
    'null' :
    '"' + String(str).replace(/[\r\n"\\]/g, c2j) + '"'
}

function r2j(rec) {
  var res = ''
  for (var k in rec) {
    if (res) res += ','
    res += s2j(k) + '=' + s2j(rec[k])
  }
  return '{' + res + '}'
}

function rnd(N) {
  for (var S = ''; S.length < (N || 21);) {
    var n = Math.floor(62 * Math.random());
    S += String.fromCharCode('Aa0'.charCodeAt(n / 26) + n % 26);
  }
  return S;
}

%>
