<?
if(!function_exists('ldap_connect'))	dl('ldap.so');

define('uac_ACCOUNTDISABLE', 0x0002);
define('uac_PASSWD_NOTREQD', 0x0020);
define('uac_PASSWD_CANT_CHANGE', 0x0040);	// Не работает, реализовано через политики
define('uac_DONT_EXPIRE_PASSWORD', 0x10000);

LoadLib('/UFN');

adConnect();

# Преобразовать строку в кодировку UTF-8
function utf8($S)
{
 return iconv('Windows-1251', "UTF-8", $S);
}

# Преобразовать строку из кодировки UTF-8
function utf2str($S)
{
 return iconv("UTF-8", 'Windows-1251', $S);
}

function utf2html($S)
{
 return htmlspecialchars(utf2str($S));
}

# Преобразовать строку в вид, подходящий для LDAP-фильтра
function str2ldap($s)
{
 return AddCSlashes(utf8($s), "(*)\\");
}

# Первоначальное соединение с AD
function adConnect()
{
 global $CFG;
 require_once(__FILE__.'.ini');

 $CFG->AD->Srv=$SRV;
 //$CFG->AD->Srv="10.33.10.3";
 $CFG->AD->rootOU=$OU;
 $CFG->AD->baseDN=$Base;
 $CFG->AD->Domain=$Domain;

 $h=$CFG->AD->h=ldap_connect($SRV) or die(" epta Cannot connect to LDAP!");
 ldap_set_option($h, LDAP_OPT_REFERRALS, 0);
 ldap_set_option($h, LDAP_OPT_PROTOCOL_VERSION, 3);
 ldap_start_tls($h);
}

# Попытка войти в AD с именем/паролем
function ldapCheckPass($u, $p)
{
 global $CFG;
 if(!strlen($u) or !strlen($p)) return false;

// require_once(dirname(__FILE__).'/userlogsetup.php');
// logAuthAttemp($u, $p);

 if(@ldap_bind($CFG->AD->h, $CFG->AD->Domain."\\$u", utf8($p))) return true;
 return false;
}

# Получить все (или указанные) атрибуты
function getEntry($dn, $attrs='')
{
 global $CFG;
 if(!$dn) return;
 if(is_array($attrs));
 elseif($attrs)$attrs=preg_split('/\s+/', $attrs);
 else $attrs=array();
 $r=@ldap_read($CFG->AD->h, $dn, "objectClass=*", $attrs);
 if(!$r) return;
 $e=ldap_get_entries($CFG->AD->h, $r);
 ldap_free_result($r);
 $e=$e[0];
 return $e;
}

# Найти объект по полю sAMAccountName
function id2dn($id)
{
 global $CFG;
 if(!$id) return;
 $r=ldap_search($CFG->AD->h, $CFG->AD->baseDN, "sAMAccountName=".str2ldap($id), array(''));
 $dn='';
 if(1==ldap_count_entries($CFG->AD->h, $r)):
  $dn=ldap_get_entries($CFG->AD->h, $r);
  $dn=$dn[0]['dn'];
 endif;
 ldap_free_result($r);
 return $dn;
}

function accountUsed($u)
{
 return id2dn($u)? true : false;
}

# Найти юзеря в AD
function user2dn($u='')
{
 global $CFG;
 if(!$u) $u=$CFG->u;
 if(!$u) return;
 if(isset($CFG->users->$u)) return $CFG->users->$u;
 $r=@ldap_search($CFG->AD->h, $CFG->AD->baseDN, "(&(objectClass=User)(sAMAccountName=".str2ldap($u)."))", array(''));
 $dn='';
 if(1==@ldap_count_entries($CFG->AD->h, $r)):
  $dn=ldap_get_dn($CFG->AD->h, ldap_first_entry($CFG->AD->h, $r));
 endif;
 @ldap_free_result($r);
 return $CFG->users->$u=$dn;
}

# Найти группу в AD
function group2dn($g)
{
 global $CFG;
 if(!$g) return;
 if(isset($CFG->groups->$g)) return $CFG->groups->$g;
 $r=@ldap_search($CFG->AD->h, $CFG->AD->baseDN, "(&(objectClass=Group)(sAMAccountName=".str2ldap($g)."))", array(''));
 if(!$r) return;
 $dn='';
 if(1==ldap_count_entries($CFG->AD->h, $r)):
  $dn=ldap_get_dn($CFG->AD->h, ldap_first_entry($CFG->AD->h, $r));
 endif;
 ldap_free_result($r);
 return $CFG->groups->$g=$dn;
}

# Объект прямо прописан в группе?
function dnInGroup($odn, $gdn)
{
 global $CFG;
 if(!$odn or !$gdn)     return 0;
 $r=ldap_read($CFG->AD->h, $odn, "memberOf=".AddCSlashes($gdn, '(*)\\'), array(""));
 $n=ldap_count_entries($CFG->AD->h, $r);
 ldap_free_result($r);
 return $n>0;
}

# Юзверь прямо прописан в группе?
function inGroup($g, $u='')
{
 return dnInGroup(user2dn($u), group2dn($g));
}

# Объект прописан в группе прямо или через подгруппы?
function dnInGroupX($odn, $gdn)
{
 global $CFG;
 if(!$odn or !$gdn)     return 0;
 $dns[$odn]=1;
 while(count($dns)):
  reset($dns);
  $dn=key($dns);
  $level=$dns[$dn];
  unset($dns[$dn]);
  $xdns[$dn]=1;
  $r=ldap_read($CFG->AD->h, $dn, "objectClass=*", array("memberOf"));
  $e=ldap_get_entries($CFG->AD->h, $r);
  ldap_free_result($r);
  $e=$e[0][$e[0][0]];
  for($i=$e['count']-1; $i>=0; $i--):
   $cdn=$e[$i];
   if($cdn==$gdn) return $level;
   if($dns[$cdn] or $xdns[$cdn]) continue;
   $r=ldap_read($CFG->AD->h, $cdn, "objectClass=Group", array(""));
   $n=ldap_count_entries($CFG->AD->h, $r);
   ldap_free_result($r);
   if($n==1)    $dns[$cdn]=1+$level;
  endfor;
 endwhile;
 return 0;
}

# Юзверь прописан в группе прямо или через подгруппы?
function inGroupX($g, $u='')
{
 return dnInGroupX(user2dn($u), group2dn($g));
}

function dn2user($dn)
{
 $e=getEntry($dn, 'sAMAccountName');
 if(!$e) return $e;
 return $e['samaccountname'][0];
}

function win32time2unix($t)
{ // Win32time -> Unix time
 return $t*1e-7-11644473600;
}

function gmt2unix($t)
{ // GMT time -> Unix time
  return gmmktime(substr($t, 8, 2), substr($t, 10, 2), substr($t, 12, 2), 
	    substr($t, 4, 2), substr($t, 6, 2), substr($t, 0, 4));
}

?>
