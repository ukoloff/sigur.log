<?

function mayRender()
{
 global $CFG;
 if($CFG->AAA>1) return;	// �� �����������
 if($CFG->AAA)	return $CFG->Auth;
 return 1;
}

function doPage()
{
 global $CFG;

 LoadLib('init', 1);

 if(mayRender()):
  checkCSRF();
  LoadLib(strtolower($_SERVER['REQUEST_METHOD']), 1);
 else:
  forceAuth();
 endif;

 if(!$CFG->title)$CFG->title='��� &laquo;����������&raquo;';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><head>
<title><?=$CFG->title?></title>
<?
 if(mayRender())LoadLib('head', 1);
 foreach($CFG->styleSheets as $x)
  echo "<link REL=STYLESHEET TYPE='text/css' HREF='$x?", strftime('%d'), "' />\n";
 unset($CFG->styleSheets);
?>
<Script Src='/menu.js?<?=strftime('%d')?>'></Script>
</head><body>
<? if($CFG->Auth)LoadLib('/me/remind'); ?>
<NoScript><Div Class='Error'>��� ��������� ����� ����� ��� ���������� ����� JavaScript</Div></NoScript>
<Script><!--
<?
 for($i=&new menuIterator($CFG->Menu); $x=&$i->item(); $i->advance()):
  echo "AddMenu(", $i->Level(), ", ", jsEscape($x->text), ", ", jsEscape($x->href), ");\n";
  foreach(Array("title", "status", "target") as $prop)
    if($x->$prop) echo "\tmItem.$prop=", jsEscape($x->$prop), ";\n";
 endfor;
?>
StartUp();
//--></Script>
<?
 unset($CFG->Menu);
 if('192.168.16.12'==$_SERVER['REMOTE_ADDR'] and !$_COOKIE['seenNoProxy']) LoadLib('/noproxy');
 flush();
?>
<H1><?=$CFG->H1?$CFG->H1:$CFG->title?></H1>
<?
 LoadLib(mayRender()? 'body':'/accessDeny', 1);
?>
</body></html>
<?
}

# ����������� ������ ��� ������ � JavaScript
function jsEscape($S)
{
 return strtr("'".AddSlashes($S)."'", Array("\n"=>"\\n", "\r"=>"\\r"));
}

# ������ �������� ������� ���/������
function forceAuth()
{
  Header("WWW-Authenticate: Basic realm=\"Control center\"");
  Header("HTTP/1.0 401 Unauthorized");
}

# ������� URL ��� <A hRef=> �� ����������� $CFG->params � ���������� ������
function hRef()
{
 global $CFG;
 $params=@get_object_vars($CFG->params);
 $argv=&func_get_args();
 while(count($argv)>0):
  $x=&array_shift($argv);
  if(is_object($x)) $x=get_object_vars($x);
  if(is_array($x)):
   foreach($x as $k=>$v)
    $params[(string)$k]=(string)$v;
  else:
   $v=array_shift($argv);
   $params[(string)$x]=isset($v)? (string)$v : $CFG->defaults->$x ;
  endif;
 endwhile;
 $R='';
 if($params)
  foreach($params as $k=>$v)
   if($v!=$CFG->defaults->$k) $R.=(''==$R?'?':'&').urlencode($k).'='.urlencode($v);
 return $R;
}

function transLit($S)
{
 $r="�������������������������";
 $l="abvgdeziyklmnoprstufh'y'e";
 $t=Array('�'=>'yo', '�'=>'zh', '�'=>'ts', '�'=>'ch', '�'=>'sh', '�'=>'sch', '�'=>'yu', '�'=>'ya',);
 $r=$r.strtoupper($r);
 $l=$l.strtoupper($l);
 foreach($t as $ru=>$la)
  $t[ucfirst($ru)]=ucfirst($la);
 return strtr(strtr($S, $r, $l), $t);
}

# �������� ����� <Input Type=Hidden> ��� ����������� $CFG->params
function hiddenInputs()
{
 global $CFG;
 if(!is_object($CFG->params)) return;
 foreach(get_object_vars($CFG->params) as $k=>$v)
  if($v!=$CFG->defaults->$k)
   echo "<Input Type=Hidden Name=\"", htmlspecialchars($k), "\" Value=\"", htmlspecialchars($v), "\" />\n";
}

function headerEncode($S)
{
 global $CFG; 
 return "=?windows-1251?B?".base64_encode($S)."?=";
}

function checkCSRF()
{
 global $CFG;
 if(!$CFG->Auth) return;
 if('POST'!=strtoupper ($_SERVER[REQUEST_METHOD])) return;
 if(preg_match('/^(\w+\.){2,}$/', $_SERVER[HTTP_HOST].'.')):
  $pfx='https://'.$_SERVER[HTTP_HOST];
  switch($CFG->checkCSRF)
  {
   default:
    $pfx.=$_SERVER[REQUEST_URI];
    if($pfx==substr($_SERVER[HTTP_REFERER], 0, strlen($pfx))):
     $pfx=substr($_SERVER[HTTP_REFERER], strlen($pfx), 1);
     if('?'==$pfx or !strlen($pfx)) return;
    endif;
    break;
   case '.': $pfx.=$CFG->Top;	if($pfx==substr($_SERVER[HTTP_REFERER], 0, strlen($pfx))) return; break;
   case '/': $pfx.='/';		if($pfx==substr($_SERVER[HTTP_REFERER], 0, strlen($pfx))) return;
  }
 endif;

 Header('Location: '.$_SERVER[HTTP_REFERER]);
 exit;
}

?>
