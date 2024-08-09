<?
if (isset($_GET['login'])):
  if ($CFG->Auth):
    Header('Location: ./');
    exit();
  endif;
  $CFG->AAA = 1;
endif;

if (isset($_GET['logout'])):
  ini_set("session.use_cookies", 0);
  ini_set('session.use_only_cookies', 0);
  session_name('logout');
  session_start();

  if (!$_GET['logout']):
    header('Location: ./?' . SID);
    $_SESSION['auth'] = 1;
    exit();
  endif;

  if ($_SESSION['auth']):
    ForceAuth();
    header('Refresh: 0; URL=./');
    session_unset();
    exit();
  endif;

  header('Location: ./');
  session_destroy();
  exit();
endif;
