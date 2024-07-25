<?
//global $CFG;

ini_set('display_errors', false);
ini_set('log_errors', false);
//error_reporting(E_ERROR);
//print_r($_SERVER);

if(false!==strpos($_SERVER['SCRIPT_NAME'], 'error')) LoadLib('error');

LoadLib('/menu');
walkURI();
doPage();

//echo "<PRE><HR />"; print_r($CFG);



# Обойти родительские папки текущей папки
function walkURI()
{
 global $CFG;
 $CFG->styleSheets=Array();
 $Folder='.';
 $CFG->Top=$me=preg_replace('|[^/]+$|', '', $_SERVER['SCRIPT_NAME']);
 $Path=preg_replace('|[^/]+$|', '', preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']));
 if(substr($Path, 0, strlen($me))!=$me):
  Header("Location: $me");
  exit;
 endif;
 $me=substr($me, 0, -1);
 $Path=explode('/', substr($Path, strlen($me)));
 array_pop($Path);
// $CFG->Folder=join('/', $Path).'/';
 foreach($Path as $x):
  $CFG->pwd=$Folder.="$x/";
  $me.="$x/";
  foreach(glob("$Folder/*.css") as $z) $CFG->styleSheets[]=$me.basename($z);
  LoadLib('autoload', 1);
  $CFG->Menu->parseIni("$Folder/.menu", $me);
 endforeach;
}

# Загружает .php файл из (под)папки /lib/
function LoadLib($Name, $optional=0)
{
 global $CFG;
 $Dir=dirname($Name);
 $pfx='.';
 $N=$Name;
 if('/'!=$Dir{0}) $pfx=$CFG->pwd;
 $Name="$pfx$Dir/lib/".basename($Name).".php";
 if(!$optional or file_exists($Name))
  require_once($Name);
 if(is_array($CFG->onLoadLib) and $CFG->onLoadLib[$N]) call_user_func($CFG->onLoadLib[$N], $N);
}

?>
