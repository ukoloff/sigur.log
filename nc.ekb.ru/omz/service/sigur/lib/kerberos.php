<?
$t = $_SESSION['t'];
unset($_SESSION['t']);
$c = curl_init('https://ad.ekb.ru/auth/krb/be/');
curl_setopt($c, CURLOPT_POST, 1);
curl_setopt($c, CURLOPT_POSTFIELDS, "tIcKeT=" . urlencode($t));
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
// curl_setopt($c, CURLOPT_CAINFO, "/var/www/net.ekb.ru/ssl/ca.crt");
$Res = curl_exec($c);
$Res = json_decode($Res);
// print_r($Res);
session_regenerate_id(1);
$_SESSION['user'] = $Res->user;
$_SESSION['u'] = preg_replace('/^\w+\\\/', '', $_SESSION['user']);
$_SESSION['meth'] = 'krb';
// $_SESSION['blob'] = $Res->blob;
