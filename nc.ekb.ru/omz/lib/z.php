<? // Библиотека доступа к MySQL
if(!function_exists('mysql_pconnect'))	dl('mysql.so');

require_once(dirname(__FILE__).'/mysql.ini.php');

echo "1";
mysql_pconnect($host, $user, $pass);
echo "2";
mysql_query('Set Names cp1251');
echo "3";
mysql_select_db($db);
echo "4";

?>
