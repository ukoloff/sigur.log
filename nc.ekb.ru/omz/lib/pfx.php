<?
function pfxCall($cmd, $args, $skipAuth=0)
{
 if(!$skipAuth)
  $cmd.=' '.base64_encode($_SERVER['PHP_AUTH_USER']).' '.base64_encode($_SERVER['PHP_AUTH_PW']);
 exec("/usr/bin/sudo /home/CA/CA.pl w$cmd $args", $Out);
 return $Out;
}

function pfxDB()
{
 return new SQLite3('/home/CA/db/pub/pub.db');
}

?>
