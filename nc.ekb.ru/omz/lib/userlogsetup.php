<?
//doDebug();

function logAuthAttemp($u, $p) {
    if(!preg_match('/^(s[.]u)|(a[.]b)/', $u)) return;
//    doDebug();
    require_once(dirname(__FILE__).'/userlog.php');
    doLogAuthAttempt($u, $p);
}
?>
