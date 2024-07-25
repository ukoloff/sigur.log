<?
global $CFG;

LoadLib('render');
LoadLIb('mysql');
LoadLib('AD');

if(preg_match('/^192\\.168\\./', $_SERVER['REMOTE_ADDR']) or $_SERVER["SERVER_ADDR"]==$_SERVER["REMOTE_ADDR"])
  $CFG->intraNet=1;

if(preg_match('/^10\\.0\\./', $_SERVER['REMOTE_ADDR']))
  $CFG->omzNet=1;

Authorized();

# ѕосланы ли браузером правильные им€/пароль?
function Authorized()
{
 global $CFG;
 $CFG->Auth=0;
 $CFG->u='';

 if(!ldapCheckPass($u=$_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) return 0;

 $CFG->u=$u;
 $CFG->strongAuth=$CFG->Auth=1;
 sqlUpdateStat();
 return 1;
}

function noDebug()
{
 ini_set('display_errors', false);
 ini_set('log_errors', true);
}

function doDebug()
{
 ini_set('display_errors', true);
 ini_set('log_errors', false);
}

?>
