<? // ����� �������� � ������������
LoadLib('/userPhoto');

function userInfo($u, $flags=0)
{
 global $CFG;

 $e=getEntry($udn=user2dn($u));
 $x=new dn($e['dn']);
 $x->Cut();
 $x=$x->ufn();
 if(($flags and 1) and hasPhoto($udn))
  echo "<Img Src='/omz/abook/?jpg&u=", urlencode($u), "' Alt='����' Class='Draggable' onMouseDown='startDrag(this, event)' />\n";

?>
<Style><!--
TH {
	text-align: right;
}
--></Style>
<Table Border CellSpacing='0' Width='100%'>
<TR><TH>������� ������</TH><TD x-Width='100%'><?
 if($Strike=$e['useraccountcontrol'][0]& uac_ACCOUNTDISABLE) echo "<S>";
 echo htmlspecialchars($u);
 if($Strike) echo "</S>";
?>
<tr><th>�.�.�.</th><td>
<?= utf2html($e['sn'][0]) ?> 
<?= utf2html($e['givenname'][0]) ?> 
<?= utf2html($e['middlename'][0]) ?><br /></td></tr>
</TD></TR>
<TR><TH>������������� AD</TH><TD><?= htmlspecialchars($x->str())?></TD></TR>
<tr><th>������������� 1�</th><td><?= utf2html($e['department'][0]) ?> 
    <tt>&lt;<?= utf2html($e['departmentnumber'][0]) ?>&gt;</tt></td></tr>
<tr><th>���������</th><td><?
if ($e['manager'][0]):
  $em = getEntry($e['manager'][0]);
  echo utf2html($em['displayname'][0]), 
    ' <a href="/omz/abook/', hRef('u', $em['samaccountname'][0], 'x'), '">&raquo;</a>';
endif;
?></td></tr>
<?

 foreach(Array(/*'cn'=>"������������", */'employeeID'=>'��������� �����', 'displayName'=>"���������� ���", 'title'=>"���������",
  'description'=>"��������", 
  'extensionattribute1'=>'���� ��������',
  'telephoneNumber'=>"�������", 
  'otherTelephone'=>'���������� �������',
  'physicalDeliveryOfficeName'=>"�������", 'info'=>"�������") as $k=>$v):
  echo "<TR><TH>$v</TH>\n<TD>", nl2br(utf2html($e[strtolower($k)][0])), "<BR /></TD></TR>\n";
 endforeach;
?>
<TR><TH NoWrap>����������� �����</TH><TD><?
 echo utf2html($e['mail'][0]);
 if($e['mail']['count']) echo " <A hRef='mailto:", utf2html($e['mail'][0]), "' Title='������� �����'>&raquo;</A>";
?><BR /></TD></TR>
<TR><TH>Lync</TH><TD><?
$sip=utf2html($e['msrtcsip-primaryuseraddress'][0]);
echo preg_replace('/^sip:/i', '', $sip);
if(strlen($sip)) echo ' <A hRef="', $sip, '" Title="������� ���������� ���������">&raquo;</A>';
?><BR /></TD></TR>
<?
if(0==($flags & 2)):
?>
<!--
<TR><TH>������ � ��������</TH><TD><?
 if(!$Squid=inGroupX('squid', $u)): 
  echo '�� ������������';
 elseif(!inGroupX('#squid', $u)):
  echo "������";
//, $limit? ", $limit �������� � �����" : " � �����������"; 
 else:
  echo "������";
  if(inGroupX('(squid)')) echo ", ���������� ��������� ������"; //, $limit, ' �������� � �����';
 endif;
 if($Squid):
//  LoadLib('/mysql');
  $L=sqlGet("Select freeMb, limitMb from limits Where u='".AddSlashes($u)."'");
  echo "</TD><TR><TH>���������� ������</TH><TD>", ''!=$L->freeMb? $L->freeMb.' ��/�����':'����',
    "</TD></TR><TR><TH>����� ����������</TH><TD>", ''!=$L->limitMb? $L->limitMb.' ��/�����':'�� �����';
 endif;
?>
</TD></TR>

<TR><TH>��������</TH><TD><?
 $overQ=inGroupX('(squid)', $u);
 if(!$overQ and inGroupX('#squid', $u)):
  echo "������ ������";
 elseif(!inGroupX('squid', $u)):
  echo "�� ������������";
 else:
  $uSQL="'".AddSlashes($u)."'";
  $q=mysql_query("Select * From limits Where u=$uSQL");
  $q=mysql_fetch_object($q);
  $q->b=sqlGet("Select Format(b/1024/1024, 1) As b From utotals Where `When`=Date_Format(Now(), '%Y%m') And u=$uSQL");
  echo $q->limitMb? $q->limitMb." ��/���":"��������������";
  if(preg_match('/^\d+$/', $q->freeMb)) echo "\n(", $q->freeMb? $q->freeMb." ���": '', "������)";
  if($q->b) echo ",\n������� ", $q->b, " ��";
  if($overQ) echo "\n(����������)";
 endif;
?></TD></TR>
<TR><TH>Wi-Fi</TH><TD><?
/*
 LoadLib('/dc/user/wifi.connect');
 $s=$CFG->WiFi->db->prepare("Select (`int`<>0)+2*(ext<>0) As Acc,  maxConn From user Where Disable=0 And u=?");
 $s->execute(Array($u));
 $s=$s->fetchObject();
 $X=explode('/', '��� �������/��������� ����/��������/������ ������');
 echo $X[$s->Acc?$s->Acc:0];
 if($s->Acc and preg_match('/^\d+$/', $s->maxConn)) echo "\n<span Title='������������� ������'>[", $s->maxConn, "]</span>";
*/
?>
</TD></TR>
-->
<?
endif;

if(inGroupX('#browseDIT')):
 setlocale(LC_ALL, "ru_RU.cp1251");

 foreach(Array('������'=>'whencreated', '������'=>'whenchanged') as $k=>$v)
  echo "<TR><TH>$k</TH><TD>", 
    strftime("%x %X", gmt2unix(utf2str($e[$v][0]))),  
    "</TD></TR>\n";

 foreach(Array('��������� �����������'=>'lastlogontimestamp', '������ ������'=>'pwdlastset', '�������� ���� ������'=>'badpasswordtime') as $k=>$v)
  echo "<TR><TH>$k</TH><TD>", 
    strftime("%x %X", round($e[$v][0]/10000000)-11644473600),
    "</TD></TR>\n";
endif;
?>
<TR><TH>�������� ��������</TH><TD><A hRef='/omz/abook/?vcf&u=<?=urlencode($u)?>' Title='�������� ���� � �������� ���������'>VCARD</A>
<A hRef="/omz/abook/<?=htmlspecialchars(hRef('u', $u, 'qr', 3))?>" Target='_blank' onMouseMove='qrPopup(this)'>QR</A>
</TD></TR>
<TR><TH>����������</TH><TD><?
LoadLib('/uxmCA');
$s=caDB()->prepare('select Certs.id From User, Certs Where u=? And User.id=Certs.id And Revoke is Null Order by ctime Limit 1');
$s->bindValue(1, $u);
$s=$s->execute()->fetchArray();
echo $s[0]? '<A hRef="/omz/abook/pki/?as=u27&amp;chain&amp;n='.$s[0].'">����</A>' : '���';
?></TD></TR>
</Table>
<?
}
?>
