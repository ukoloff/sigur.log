<LI><B>��������</B>:
<A hRef=/ Target=_top>������� �����</A><?
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
<LI><B>������</B>:
<?
$Errors=array(
 400=>"������ ��������",
 401=>"�������� ���������� (�������� ��� ������������ �/��� ������)",
 402=>"�������� ��������������� �� ������� ������",
 403=>"�������� ������ ��� �������",
 404=>'�������� �� ������ �� �������',
 500=>"���������� ������ �������"
 );
$Errors[403]=$Errors[404];
$Msg=$Errors[$_SERVER['REDIRECT_STATUS']];
if(!$Msg) $Msg='������ #'.$_SERVER['REDIRECT_STATUS'];
echo $Msg;
?>
<Center>
<Form Action='/omz/service/post/' Method='Post'>
<Input Type='Hidden' Name='body' Value="<?=htmlspecialchars($_SERVER[REDIRECT_URL])?>" />
<Input Type='Hidden' Name='Error' Value="<?=htmlspecialchars($_SERVER[REDIRECT_STATUS])?>" />
<Input Type='Hidden' Name='Referer' Value="<?=htmlspecialchars($_SERVER[HTTP_REFERER])?>" />
<Input Type='Hidden' Name='subject' Value="������ �� �������" />
<Input Type='Hidden' Name='okpage' Value='/omz/' />
<Input Type='Submit' Value='�������� ���-�������' />
</Form>
<!--
<?
for($i=1; $i<=150; $i++):
  echo "PAD";
  if(0==$i%20) echo "\n";
endfor;
?>

-->
