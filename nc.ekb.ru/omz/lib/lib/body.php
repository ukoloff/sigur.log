<LI><B>Страница</B>:
<A hRef=/ Target=_top>Сетевой центр</A><?
list($p, $q)=explode('?', $_SERVER['REDIRECT_URL'], 2);
$Path='/';
while(1):
 $p=preg_replace('|^/+|', '', $p);
 if(!$p) break;
 if(1=="1".($i=strpos($p, '/'))) $i=strlen($p);
 $v=substr($p, 0, $i);
 $p=substr($p, $i);
 $Path.=rawurlencode($v);
 if($p) $Path.='/';
 echo "/<A\nhRef='$Path'>", htmlspecialchars($v), "</A>";
endwhile;
if($q)
 echo "?<A\nhRef='$Path?", rawurlencode($q),"'>", htmlspecialchars($q), "</A>";
?>
<LI><B>Ошибка</B>:
<?
$Errors=array(
 400=>"Ошибка браузера",
 401=>"Документ недоступен (неверные имя пользователя и/или пароль)",
 402=>"Документ предоставляется на платной основе",
 403=>"Документ закрыт для доступа",
 404=>'Документ не найден на сервере',
 500=>"Внутренняя ошибка сервера"
 );
$Errors[403]=$Errors[404];
$Msg=$Errors[$_SERVER['REDIRECT_STATUS']];
if(!$Msg) $Msg='Ошибка #'.$_SERVER['REDIRECT_STATUS'];
echo $Msg;
?>
<Center>
<Form Action='/omz/service/post/' Method='Post'>
<Input Type='Hidden' Name='body' Value="<?=htmlspecialchars($_SERVER[REDIRECT_URL])?>" />
<Input Type='Hidden' Name='Error' Value="<?=htmlspecialchars($_SERVER[REDIRECT_STATUS])?>" />
<Input Type='Hidden' Name='Referer' Value="<?=htmlspecialchars($_SERVER[HTTP_REFERER])?>" />
<Input Type='Hidden' Name='subject' Value="Ошибка на сервере" />
<Input Type='Hidden' Name='okpage' Value='/omz/' />
<Input Type='Submit' Value='Сообщить веб-мастеру' />
</Form>
<!--
<?
for($i=1; $i<=150; $i++):
  echo "PAD";
  if(0==$i%20) echo "\n";
endfor;
?>

-->
