<?

//echo "hui-pizda";

function caExec($args)
{
 $args=func_get_args();
 while(count($args)):
  $x=array_shift($args);
  if(is_object($x) or is_array($x))
   foreach($x as $k=>$v) $Z[$k]=$v;
  else
   $Z[$x]=array_shift($args);
 endwhile;

 if(array_key_exists('auth',  $Z)):
  unset($Z[auth]);
  $Z[authUser]=base64_encode($_SERVER['PHP_AUTH_USER']);
  $Z[authPass]=base64_encode($_SERVER['PHP_AUTH_PW']);
 endif;
 
//echo "opa nihiya";

 $x=proc_open("/usr/bin/sudo -u uxm-ca /home/uxmCA/CA.pl -web", Array(Array('pipe', 'r'), Array('pipe', 'w')),  $pipes);
 echo "------------------------>>>>";
 echo $x;
 foreach($Z as $k=>$v)
  fwrite($pipes[0], "$k=$v\n");
 fclose($pipes[0]);
 $Z=stream_get_contents($pipes[1]);
 fclose($pipes[1]);
 proc_close($x);
 return $Z;
}

function caDB()
{
	//echo "opa nihiya";
 return new SQLite3('/home/uxmCA/db/pub/pub.db');
}

function crlUpdate()
{
 global $CFG;
 if(!$CFG->Auth) return;
 $db=caDB();
 if($db->querySingle("Select datetime('now', '-27 hours')<(Select Value From Ini Where Name='userCRL')")) return;
 caExec(Array(command=>'crl', auth=>1));
}

?>
