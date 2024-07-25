<? // Библиотека доступа к MySQL
if(!function_exists('mysql_pconnect'))	dl('mysql.so');

require_once(dirname(__FILE__).'/mysql.ini.php');
//mysql_pconnect($host, $user, $pass);
//mysql_query('Set Names cp1251');
//mysql_select_db($db);

function sqlGet($SQL)
{
 $q=mysql_query($SQL);
 $r=mysql_fetch_object($q);
 if(!$r) return;
 switch(count($a=get_object_vars($r)))
 {
  case 0: return;
  case 1: reset($a); return current($a);
 }
 return $r;
}

function sqlUpdateStat()
{
 return;
 global $CFG;
 $ip=$_SERVER["REMOTE_ADDR"];
 if($ip==$_SERVER["SERVER_ADDR"])$ip='127.0.0.1';
 $ip=AddSlashes($ip);
 $u=AddSlashes($CFG->u);
 mysql_query("Update ipUse Set www=1 Where Month=Date_Format(Now(), '%Y%m') And u='$u' And ip='$ip'");
 if(mysql_affected_rows()<=0)
   mysql_query("Insert Into ipUse(Month, u, ip, www) Values(Date_Format(Now(), '%Y%m'), '$u', '$ip', 1)");
}

?>
