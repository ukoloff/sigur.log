<?
ini_set("session.use_cookies", false);
session_name('out');

if(isset($_GET['login'])):
  if($CFG->Auth):
   Header('Location: ./');
  else:
   $CFG->AAA=1;
  endif;
elseif(isset($_GET['logout'])):
  session_start();
  Header('Location: ./?'.session_name().'='.session_id());
elseif($_GET[session_name()]):
 /*
  $f=fopen('/var/tmp/omz.log', 'a');
  fputs($f, "[]\n");
  foreach(getallheaders() as $k=>$v):
    fputs($f, "$k=$v\n");
  endforeach;
  fclose($f);
*/  
  session_start();
  if($_SESSION['n']):
//    if(''==$_SERVER['PHP_AUTH_USER'] or $CFG->Auth):
    if($CFG->u!=$_SESSION['u']):
     Header('Location: ./');
     session_destroy();
    else:
     ForceAuth();
    endif;
  else:
   $_SESSION['n']=1;
   $_SESSION['u']=$CFG->u;
   forceAuth();
  endif;
endif;
?>
