<?

function mayRender()
{
  global $CFG;
  if ($CFG->AAA > 1)
    return;	// Не авторизован
  if ($CFG->AAA)
    return $CFG->Auth;
  return 1;
}

function doPage()
{
  global $CFG;

  LoadLib('init', 1);

  if (mayRender()):
    checkCSRF();
    LoadLib(strtolower($_SERVER['REQUEST_METHOD']), 1);
  else:
    forceAuth();
  endif;

  if (!$CFG->title)
    $CFG->title = 'ОАО &laquo;Уралхиммаш&raquo;';
  ?>
  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
  <html>

  <head>
    <title><?= $CFG->title ?></title>
    <?
    if (mayRender())
      LoadLib('head', 1);
    foreach ($CFG->styleSheets as $x):
      $tm = getdate(filemtime($_SERVER['DOCUMENT_ROOT'] . $x));
      echo "<link rel='stylesheet' type='text/css' href='$x?", $tm['seconds'], "' />\n";
    endforeach;
    unset($CFG->styleSheets);
    $js = '/menu.js';
    $tm = getdate(filemtime($_SERVER['DOCUMENT_ROOT'] . $js));
    ?>
    <Script Src='<?= $js ?>?<?= $tm['seconds'] ?>'></Script>
  </head>

  <body>
    <? if ($CFG->Auth)
      LoadLib('/me/remind'); ?>
    <NoScript>
      <Div Class='Error'>Для просмотра этого сайта Вам определённо нужен JavaScript</Div>
    </NoScript>
    <Script><!--
<?
    for ($i = new menuIterator($CFG->Menu); $x =& $i->item(); $i->advance()):
      echo "AddMenu(", $i->Level(), ", ", jsEscape($x->text), ", ", jsEscape($x->href), ");\n";
      foreach (array("title", "status", "target") as $prop)
        if ($x->$prop)
          echo "\tmItem.$prop=", jsEscape($x->$prop), ";\n";
    endfor;
    ?>
    StartUp();
    //--></Script>
    <?
    unset($CFG->Menu);
    if ('192.168.16.12' == $_SERVER['REMOTE_ADDR'] and !$_COOKIE['seenNoProxy'])
      LoadLib('/noproxy');
    flush();
    ?>
    <H1><?= $CFG->H1 ? $CFG->H1 : $CFG->title ?></H1>
    <?
    LoadLib(mayRender() ? 'body' : '/accessDeny', 1);
    ?>
  </body>

  </html>
  <?
}

# Подготовить строку для записи в JavaScript
function jsEscape($S)
{
  return strtr("'" . AddSlashes($S) . "'", array("\n" => "\\n", "\r" => "\\r"));
}

# Велеть браузеру послать имя/пароль
function forceAuth()
{
  Header("WWW-Authenticate: Basic realm=\"Control center\"");
  Header("HTTP/1.0 401 Unauthorized");
}

# Вернуть URL для <A hRef=> из содержимого $CFG->params и переданных данных
function hRef()
{
  global $CFG;
  $params = @get_object_vars($CFG->params);
  $argv =& func_get_args();
  while (count($argv) > 0):
    $x =& array_shift($argv);
    if (is_object($x))
      $x = get_object_vars($x);
    if (is_array($x)):
      foreach ($x as $k => $v)
        $params[(string) $k] = (string) $v;
    else:
      $v = array_shift($argv);
      $params[(string) $x] = isset($v) ? (string) $v : $CFG->defaults->$x;
    endif;
  endwhile;
  $R = '';
  if ($params)
    foreach ($params as $k => $v)
      if ($v != $CFG->defaults->$k)
        $R .= ('' == $R ? '?' : '&') . urlencode($k) . '=' . urlencode($v);
  return $R;
}

function transLit($S)
{
  $r = "абвгдезийклмнопрстуфхъыьэ";
  $l = "abvgdeziyklmnoprstufh'y'e";
  $t = array('ё' => 'yo', 'ж' => 'zh', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ю' => 'yu', 'я' => 'ya', );
  $r = $r . strtoupper($r);
  $l = $l . strtoupper($l);
  foreach ($t as $ru => $la)
    $t[ucfirst($ru)] = ucfirst($la);
  return strtr(strtr($S, $r, $l), $t);
}

# Записать набор <Input Type=Hidden> для содержимого $CFG->params
function hiddenInputs()
{
  global $CFG;
  if (!is_object($CFG->params))
    return;
  foreach (get_object_vars($CFG->params) as $k => $v)
    if ($v != $CFG->defaults->$k)
      echo "<Input Type=Hidden Name=\"", htmlspecialchars($k), "\" Value=\"", htmlspecialchars($v), "\" />\n";
}

function headerEncode($S)
{
  global $CFG;
  return "=?windows-1251?B?" . base64_encode($S) . "?=";
}

function checkCSRF()
{
  global $CFG;
  if (!$CFG->Auth)
    return;
  if ('POST' != strtoupper($_SERVER['REQUEST_METHOD']))
    return;
  if (preg_match('/^(\w+\.){2,}$/', $_SERVER['HTTP_HOST'] . '.')):
    $pfx = 'https://' . $_SERVER[HTTP_HOST];
    switch ($CFG->checkCSRF) {
      default:
        $pfx .= $_SERVER['REQUEST_URI'];
        if ($pfx == substr($_SERVER['HTTP_REFERER'], 0, strlen($pfx))):
          $pfx = substr($_SERVER['HTTP_REFERER'], strlen($pfx), 1);
          if ('?' == $pfx or !strlen($pfx))
            return;
        endif;
        break;
      case '.':
        $pfx .= $CFG->Top;
        if ($pfx == substr($_SERVER['HTTP_REFERER'], 0, strlen($pfx)))
          return;
        break;
      case '/':
        $pfx .= '/';
        if ($pfx == substr($_SERVER['HTTP_REFERER'], 0, strlen($pfx)))
          return;
    }
  endif;

  Header('Location: ' . $_SERVER['HTTP_REFERER']);
  exit;
}
