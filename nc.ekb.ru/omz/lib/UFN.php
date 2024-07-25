<?
# Вспомогательная функция, аналог split, но учитывает \...
function &escSplit($char, $str, $count=0)
{
 $Res=Array();
 while(true):
  if($count>0 and count($Res)+1>=$count):
   array_push($Res, $str);
   return $Res;
  endif;
  $start='';
  while(true):
   $p=strpos($str, $char);
   if(false===$p):
    array_push($Res, $start.$str);
    return $Res;
   endif;
   $start.=substr($str, 0, $p);
   $str=substr($str, $p+strlen($char));
   if(!preg_match("/\\\\+$/", $start, $matches)) break;	// Нет \ перед разделителем
   if(0==(1&strlen($matches[0]))) break;		// Чётное число \ перед разделителем
   $start.=$char;
  endwhile;
  array_push($Res, $start);
 endwhile;
}

# Добавляет \ перед указанными символами, если уже не добавлено
function &smartAddSlashes($S, $Chars='(*)')
{
 for($i=strlen($Chars)-1; $i>0; $i--)
  if("\\"!=($c=$Chars{$i}))
   $S=join("\\$c", escSplit($c, $S));
 return $S;
}

function dnCanonify($dn)
{
 global $CFG;
 if(!($e=@ldap_read($CFG->AD->h, $dn, 'objectClass=*', array('distinguishedname')))) return;
 if(!($e=@ldap_get_entries($CFG->AD->h, $e))) return;
 return $e[0]['distinguishedname'][0]; 
}

class dn
{	// Содержит dn в разобранном виде (UTF-8)
 var $X=Array();
 function dn($dn)
 {
  foreach(escSplit(',', dnCanonify($dn)) as $rdn):
   $y=Array();
   foreach(escSplit('+', $rdn) as $pair):
    list($k, $v)=escSplit('=', $pair, 2);
    $y[stripSlashes(strtolower(trim($k)))]=stripSlashes($v);
   endforeach;
   $this->X[]=$y;
  endforeach;
 }

 function &Cut()	// Отрезать объект, оставшись контейнером
 {
  return array_shift($this->X);
 }

 function Paste($key, $value)	// Добавить потомка
 {
  array_unshift($this->X, array($key=>$value));
 }

 function rdn($n=1)	// Отрезать контейнер, оставив только rdn
 {
  while(count($this->X)>$n) array_pop($this->X);
 }

 function ufn()
 {
  global $CFG;
  $bdn=new dn($CFG->AD->baseDN);
  for($i=count($this->X)-1, $j=count($bdn->X)-1; $j>=0; $i--, $j--)
    if($this->X[$i]!==$bdn->X[$j]) return;
  $R=new ufn('/');
  for(; $i>=0; $i--)
   $R->X[]=array_values($this->X[$i]);
  return $R;
 }

 function str()
 {
  define(dnMasqChars, '+,="\\');
  $S='';
  foreach($this->X as $y):
   $rdn='';
   foreach($y as $k=>$v):
    if($rdn)$rdn.='+';
    $rdn.=addCSlashes($k, dnMasqChars).'='.addCSlashes($v, dnMasqChars);
   endforeach;
   if($S) $S.=",";
   $S.=$rdn;
  endforeach;
  return $S;
 }

 function Canonic()
 {
  return dnCanonify($this->str());
 }

 function isParentOf($dn)
 {
  if(!is_object($dn)) $dn=new dn($dn);
  for($i=count($this->X)-1, $j=count($dn->X)-1; $i>=0; $i--, $j--)
   if($this->X[$i]!=$dn->X[$j]) return false;
  return true;
 }
}

class ufn
{	// Содержит UserFriendlyName в разобранном виде (UTF-8)
 var $X=Array();

 function ufn($ufn='')
 {
  global $CFG;
  if('/'==$ufn{0}):
   $ufn=substr($ufn, 1);
  else:
   if(''!=$ufn) $ufn='/'.$ufn;
   $ufn=$CFG->AD->rootOU.$ufn;
   $ufn=preg_replace('|[^/]+/\.\./|', '', $ufn);
  endif;
  if(!$ufn) return;
  foreach(escSplit('/', $ufn) as $y):
   $z=Array();
   foreach(escSplit('+', $y) as $r) $z[]=utf8(StripSlashes($r));
   $this->X[]=$z;
  endforeach;
 }

 function &Cut()	// Отрезать объект, оставшись контейнером
 {
  return array_pop($this->X);
 }

 function Paste($value)	// Добавить потомка
 {
  $this->X[]=Array($value);
 }

 function &dn()
 {
  global $CFG;
  $d=new dn($CFG->AD->baseDN);
  foreach($this->X as $y)
   array_unshift($d->X, Array('OU'=>$y[0]));
  return $d;
 }

 function str()
 {
  global $CFG;
  define(ufnMasqChars, '/+\\');
  if(0>=count($this->X)) return '/';
  $S='';
  foreach($this->X as $y):
   $z='';
   foreach($y as $r):
    if($z) $z.='+';
    $z.=addCSlashes(utf2str($r), ufnMasqChars);
   endforeach;
   $S.='/';
   $S.=$z;
  endforeach;
  $R="/".$CFG->AD->rootOU;
  if($R==$S) return '';
  $R.='/';
  if(substr($S, 0, strlen($R))==$R)
    $S=substr($S, strlen($R));
  return $S;
 }
}

?>
