<?
session_name('SIGURS');
session_start();

$CFG->title = 'Ёкспорт из Sigur';

if ($_GET['auth'] == 'AD'):
  $CFG->AAA = 1;
  if ($CFG->Auth):
    // Authorized via AD
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
