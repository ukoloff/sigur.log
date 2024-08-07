<?
ini_set("session.use_cookies", false);
session_name('out');

if (isset($_GET['login'])):
  if ($CFG->Auth):
    Header('Location: ./');
  else:
    $CFG->AAA = 1;
  endif;
elseif (isset($_GET['logout'])):
  session_start();
  Header('Location: ./?' . session_name() . '=' . session_id());
elseif ($_GET[session_name()]):
  /*
   $f=fopen('/var/tmp/omz.log', 'a');
   fputs($f, "[]\n");
   foreach(getallheaders() as $k=>$v):
     fputs($f, "$k=$v\n");
   endforeach;
   fclose($f);
 */
  session_start();
  if ($_SESSION['n']):
    //    if(''==$_SERVER['PHP_AUTH_USER'] or $CFG->Auth):
    if ($CFG->u != $_SESSION['u']):
      Header('Location: ./');
      session_destroy();
    else:
      ForceAuth();
    endif;
  else:
    $_SESSION['n'] = 1;
    $_SESSION['u'] = $CFG->u;
    forceAuth();
  endif;
endif;


if (isset($_GET['logoff'])):
  ini_set("session.use_cookies", 0);
  ini_set('session.use_only_cookies', 0);
  session_name('logoff');
  session_start();

  if (!$_GET['logoff']):
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
