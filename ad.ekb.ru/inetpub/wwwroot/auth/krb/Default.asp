<%@Language='JScript'%>
<%
Server.Execute('be/expire.asp')

var jsonEsc = { "\n": 'n', "\r": 'r' }

var q = Request.QueryString
if (q('dump').Count == 1)
  Server.Execute('be/dump.asp')
else if (q('auth').Count == 1)
  auth(q('auth')(1))

function auth(ref) {
  var z = Request.ServerVariables
  if (!ref) {
    ref = z('HTTP_REFERER')
    if (ref.count != 1) return
    ref = ref(1)
  }
  if (!/^https:\/\/([-\w_]+[.])+ekb[.]ru\//.test(ref)) return
  ref = ref.replace(/[^\/\\]*$/, '')

  var si = new ActiveXObject("ADSystemInfo")
  var key = rnd()
  var data = {
    auth: z('AUTH_TYPE'),
    user: z('AUTH_USER'),
    dn: si.UserName,
    ip: z('REMOTE_ADDR'),
    ua: z('HTTP_USER_AGENT')
    // blob: z('HTTP_AUTHORIZATION')(1).split(/\s+/, 2)[1]
  }
  var ticket = z('HTTP_AUTHORIZATION')
  if (ticket.count == 1)
    data.blob = ticket(1).split(/\s+/, 2)[1]
  Application(key) = r2j(data)
  Application(':' + key) = (new Date()).getTime() + 3000
  Response.Redirect(ref + '?TiCkEt=' + key);
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
    if (res) res += ',\n'
    res += s2j(k) + ': ' + s2j(rec[k])
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
