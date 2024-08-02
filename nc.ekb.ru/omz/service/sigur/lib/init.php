<?
session_name('SigurS');
// session_set_cookie_params(0, preg_replace('/[^\/]+$/', '', $_SERVER['REQUEST_URI']));
ini_set('session.cookie_path', preg_replace('/[^\/]+$/', '', $_SERVER['REQUEST_URI']));
ini_set('session.cookie_httponly', 1);
session_start();

$CFG->title = 'Ёкспорт из Sigur';

if ($_GET['auth'] == 'AD'):
  $CFG->AAA = 1;
  if ($CFG->Auth):
    // Authorized via AD
    session_regenerate_id(1);
    $_SESSION['u'] = $CFG->u;
    $_SESSION['meth'] = 'AD';

    header('Location: ./');
    exit();
  endif;
endif;

if ($_GET['TiCkEt']):
  $_SESSION['t'] = $_GET['TiCkEt'];
  header('Location: ./');
  exit();
endif;

if ($_SESSION['t']):
  LoadLib('kerberos');
endif;
