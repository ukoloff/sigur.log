<%@Language='JScript'%>
<%
Server.Execute('be/expire.asp')

var jsonEsc = { "\n": 'n', "\r": 'r' }

switch ('' + Request.QueryString) {
  case 'dump':
    Server.Execute('be/dump.asp')
    break
  case 'auth':
    auth()
    break
}

function auth() {
  var z = Request.ServerVariables
  var ref = z('HTTP_REFERER')
  if (ref.count != 1 && !/^https:\/\/([-\w_]+[.])+ekb[.]ru\//.test(z(1))) return
  ref = ref(1)
  var si = new ActiveXObject("ADSystemInfo")
  var key = rnd()
  Application(key) = r2j({
    auth: z('AUTH_TYPE'),
    user: z('AUTH_USER'),
    dn: si.UserName,
    ip: z('REMOTE_ADDR'),
    ua: z('HTTP_USER_AGENT'),
    blob: z('HTTP_AUTHORIZATION')(1).split(/\s+/, 2)[1]
  })
  Application(':' + key) = (new Date()).getTime() + 3000
  Response.Redirect(ref.replace(/[^\/\\]*$/, '') + '?TiCkEt=' + key);
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
