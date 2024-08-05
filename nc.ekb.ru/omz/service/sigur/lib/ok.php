<pre>
<?
doDebug();
print_r($CFG);
print_r($_SESSION);
print_r($_SERVER);

if (!$CFG->u):
  require '/etc/nc.ekb.ru/passwd/nc.ekb.ru.php';
  ldapCheckPass($adLogin, $adPassword);
endif;
$dn = user2dn($_SESSION['u']);
echo utf2html($dn);
?>
</pre>
