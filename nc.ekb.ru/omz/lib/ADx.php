<?	// Модификация LDAP - только через функции этого модуля
global $CFG;
//LoadLib('/mysql');

function ldapError()
{
 global $CFG;
 $CFG->ldapError='Ошибка '.ldap_errno($CFG->AD->h).': '.ldap_error($CFG->AD->h);
}

// Подготовить все данные для переименования $dn->{$attr=$val,$ou}
// Возвращает объект для передачи в ldapRename/ldapCreate
// Если !$dn -> не переименование, а создание объекта
function ldapPrepareRename($dn, $ou, $attr, $val)
{
 global $CFG;
 if($dn):
  $odn=new dn($dn);
  $oldval=$odn->Cut();
  $oldval=utf2str($oldval[$attr]);
  $ufn=$odn->ufn();
  $ufn=$ufn->str();
  if($ufn==$ou)			// Подразделение то же самое
   if($oldval==$val)		// А вообще надо переименовывать?
    return;	
   else
    $New->dnIn=$odn;
 endif;
 if(!$New):
  $New->dnIn=new ufn($ou);
  $New->dnIn=$New->dnIn->dn();	// Новый контейнер
 endif;
 if(!($New->dnIn->Canonic())):
  $New->Errors->ou='Неверное подразделение';
 elseif(!$val):
  $New->Errors->val='Имя не задано';
// elseif(false!==strpos($val, '+')):
//  $New->Errors->val="Символ '+' недопустим";
 else:
  $New->dnNew=clone $New->dnIn;
  $New->dnNew->Paste($attr, utf8($val));
  $test=dnCanonify($New->dn=$New->dnNew->str());
  if($test and $test!=$dn)
   $New->Errors->val='Имя занято';
 endif;
 if($dn):
   $New->dn=$dn;
   if($New->rdn=clone $New->dnNew)
     $New->rdn->rdn();			// Из полного dn -> rdn
 endif;
 return $New;
}

function ldapModify($dn, $attrs)
{
 global $CFG;
 if(!$dn) return;

 $a=array_keys($attrs);
 foreach(Array('objectType', 'sAMAccountName') as $x)
  if(!isset($attrs[$x])) $a[]=$x;
 $e=getEntry($dn, $a);		// Получить старые значения атрибутов
 unset($a);
 foreach($attrs as $k=>$v):
  $old=$e[strtolower($k)];
  $oldv=Array();
  for($i=$old['count']-1; $i>=0; $i--) $oldv[]=$old[$i];
  unset($old);
  $save[$k]=$oldv;
  if(!is_array($v)) $v=Array($v);
  $doMod=0;
  foreach($v as $val):
   if(!($val=(string)$val)) continue;
   $mod[$k][]=$val;
   if($doMod) continue;
   $doMod=1;
   foreach($oldv as $i=>$ov)
    if($ov==$val):
     $doMod=0;		// Есть такое значение в существующем объекте
     unset($oldv[$i]);
     break;
    endif;
  endforeach;
  if($doMod) continue;
  if(count($mod[$k])<=0)
   if(count($oldv)>0): $del[$k]=Array(); endif;
  elseif(count($oldv)>0) continue;
  unset($mod[$k]);
 endforeach;
 unset($attrs);
// echo "<!--\nSave"; print_r($save); echo "\nDel"; print_r($del); echo "\nMod"; print_r($mod); echo "\n-->";
 if(count($del)>0 and !ldap_mod_del($CFG->AD->h, $dn, $del)):
  ldapError();
  return false;
 endif;
 if(count($mod)>0 and !ldap_modify($CFG->AD->h, $dn, $mod)):
  ldapError();
  return false;
 endif;
 return true;
}

function ldapCreate($objRename, $attrs)
{
 global $CFG;
 if(!$objRename) return false;

 foreach($attrs as $k=>$v):
  if(!is_array($v)) $v=Array($v);
  foreach($v as $val)
   if($val=(string)$val) $add[$k][]=$val;
 endforeach;
 unset($attrs);

 if(ldap_add($CFG->AD->h, $objRename->dn, $add)) return true;
 ldapError();
 return false;
}

function ldapRename($objRename)
{
 global $CFG;
 if(!$objRename) return true;

 if(ldap_rename($CFG->AD->h, $objRename->dn, $objRename->rdn->str(), $objRename->dnIn->str(), true)) return true;
 ldapError();
 return false;
}

function ldapDelete($dn)
{
 global $CFG;
 if(!$dn) return;
 if(@ldap_delete($CFG->AD->h, $dn)) return true;
 ldapError();
 return false;
}

function ldapGroupAdd($dnGroup, $dnMember, $remove=false)
{
 global $CFG;
 if(!$dnGroup or !$dnMember) return false;
 $in=dnInGroup($dnMember, $dnGroup);
 $X=Array('member'=>Array($dnMember));
 if($remove):
  if(!$in) return true;
  $Func=ldap_mod_del;
 else:
  if($in) return true;
  $Func=ldap_mod_add;
 endif;
 if(!$Func($CFG->AD->h, $dnGroup, $X)):
  ldapError();
  return false;
 endif;

 $ip=$_SERVER['REMOTE_ADDR'];
 if($ip==$_SERVER["SERVER_ADDR"]) $ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
 $ip=AddSlashes($ip);
 mysql_query("Insert Into uxmJournal.Group(Op, u, g, Who, IP) Values('".($remove?'-':'+').
    "', '".AddSlashes(utf2str(dn2user($dnMember)))."', '".
    AddSlashes(utf2str(dn2user($dnGroup)))."', '".
    AddSlashes($CFG->u)."', '$ip')");

 return true;
}

# Преобразовать пароль в формат AD::user::unicodePwd
function pass2unicode($S)
{
 global $CFG; 
 return substr(iconv($CFG->charSet, "UTF-16", "\"$S\""), 2);
}

function randomPass()
{
 $S='';
 for($i=30; $i>0; $i--)
   $S.=rand(0, 9);
 return $S;
}

// Изменить пароль пользователя
function ldapChangePass($u, $pass)
{
 global $CFG;
 // AD 2003 uses 2 (!) passwords - current and previous for LDAP authorization
 ldap_modify($CFG->AD->h, user2dn($u), Array('unicodePwd'=>pass2unicode(randomPass())));
 
 if(!ldap_modify($CFG->AD->h, user2dn($u), Array('unicodePwd'=>pass2unicode($pass)))):
  ldapError();
  return false;
 endif;
 onChangePass($u);
 return true;
}

function onChangePass($u)
{
 global $CFG; 

 $u=AddSlashes($u);
 $Who=AddSlashes($CFG->u);
 $ip=$_SERVER['REMOTE_ADDR'];
 if($ip==$_SERVER["SERVER_ADDR"]) $ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
 $ip=AddSlashes($ip);
// mysql_query("Insert Into Passwords(u, Who, ip) Values('$u', '$Who', '$ip')");
 mysql_query("Insert Into uxmJournal.Password(u, Who, ip, Dom) Values('$u', '$Who', '$ip', 'o')");
}

?>
