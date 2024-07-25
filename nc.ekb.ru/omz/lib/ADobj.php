<?	// DIT object=User|Group|OU
$CFG->oClasses=Array(
    'u'=>Array('name'=>'User', 'attrs'=>Array('sAMAccountName', 'userAccountControl', 'homeMDB')),
    'g'=>Array('name'=>'Group', 'attrs'=>Array('sAMAccountName', 'groupType')),
    'o'=>Array('name'=>'organizationalUnit', 'attrs'=>Array('l')),
);
$CFG->defaults->oClasses='ugo';
$CFG->sort=Array(
    't'=>Array('field'=>'t', 'name'=>'', 'title'=>'��� �������'),
    'i'=>Array('field'=>'id', 'name'=>'���'),
    'n'=>Array('field'=>'name', 'name'=>'��������'),
    'o'=>Array('field'=>'ou', 'name'=>'�����', 'title'=>'�������������, � ������� ��������� ������'),
    'f'=>Array('field'=>'flags', 'name'=>'+', 'title'=>'�������������� �������� �������'),
    'd'=>Array('field'=>'desc', 'name'=>'��������'),
);
$CFG->defaults->sort='tn';

$CFG->oIcons=Array(
 'D'=>Array('file'=>'ou', 'title'=>'�������������'),
 'G'=>Array('file'=>'group', 'title'=>'������'),
 'U'=>Array('file'=>'user', 'title'=>'������������'),
 'u'=>Array('file'=>'xuser', 'title'=>'��������������� ������������'),
);

function objectClassFilter()
{
 global $CFG;
 $CFG->defaults->oClasses=strtolower($CFG->defaults->oClasses);
 $S='(|';
 foreach($CFG->oClasses as $k=>$v)
  if(false!==strpos($CFG->defaults->oClasses, $k))
    $S.='(objectClass='.$v['name'].')';
 return $S.')';
}

function getObject($dn)
{
 global $CFG;
 if(!$dn) return;
 $attrs=Array('objectClass', 'description');
 $f=objectClassFilter();
 foreach($CFG->oClasses as $k=>$v)
  if(false!==strpos($CFG->defaults->oClasses, $k))
    foreach($v['attrs'] as $a)
     $attrs[]=$a;
 $q=ldap_read($CFG->AD->h, $dn, $f, $attrs);
 $e=ldap_get_entries($CFG->AD->h, $q);
 ldap_free_result($q);
 if(1!=$e['count']) return;
 $e=$e[0];
 $x->dn=$dn;
 $x->desc=utf2str($e['description'][0]);
 $ufn=new dn($dn);
 $ufn=$ufn->ufn();
 $x->ufn=$ufn->str();
 $rdn=$ufn->Cut();
 $x->name=utf2str($rdn[0]);
 $x->ou=$ufn->str();
 $x->id=utf2str($e['samaccountname'][0]);
 $z=$e['objectclass'];
 for($i=$z['count']-1; $i>=0; $i--)
  foreach($CFG->oClasses as $k=>$v):
   if(strcasecmp($z[$i], $v['name'])) continue;
   $x->isA=$k;
   switch($k)
   {
    case 'u':
     $x->t=($e['useraccountcontrol'][0]& uac_ACCOUNTDISABLE)? 'u' : 'U';
     $x->flags=$e['homemdb'][0]? '@':'';
     break;
    case 'g':
     $x->t='G';
     $e=$e['grouptype'][0];
     $x->flags=($e & 0x80000000) ? 'S':'D';
     if($e & 1) $x->flags.='�';
     if($e & 2) $x->flags.='�';
     if($e & 4) $x->flags.='�';
     if($e & 8) $x->flags.='�';
     break;
    case 'o':
     unset($x->id);
     $x->flags=utf2str($e['l'][0]);	// ???
     $x->t='D';
   }
   return $x;
  endforeach;
}

function echoObject(&$x, $template='notfid')
{
 global $CFG;
 $n=0;
 for($i=0; $c=$template{$i}; $i++):
  if($n++) echo "</TD><TD>";
  switch($c)
  {
   case 'n':
    if('o'==$x->isA) echo "<A\nhRef='../ou/", hRef('ou', $x->ufn), "'>";
//    if('u'==$x->isA and 'stas'==$CFG->u) echo "<Span onMouseMove=\"userThumb(this, ".jsEscape($x->id).")\">";
    if('u'==$x->isA) echo "<Span onMouseMove=\"userThumb(this, ".jsEscape($x->id).")\">";
    echo htmlspecialchars($x->name);
    if('o'==$x->isA) echo "</A>";
//    if('u'==$x->isA and 'stas'==$CFG->u) echo "</Span>";
    if('u'==$x->isA) echo "</Span>";
    break;
   case 'i':
    $obj='';
    switch($x->isA)
    {
     case 'u': $obj='user'; break;
     case 'g': $obj='group'; break;
    }
    if($obj) echo "<A\nTitle='������� � ��������� ����� �������' hRef='../$obj/", hRef($x->isA, $x->id), "'>";
    echo htmlspecialchars($x->id);
    echo $obj? "</A>" : "<BR />";
    break;
   case 'd':
    echo "<Small>", htmlspecialchars($x->desc), "</Small><BR />\n";
    break;
   case 'o':
    echo "<A\nTitle='������� � ��������� ����� �������������' hRef='../ou/", 
	hRef('ou', $x->ou), "'>", htmlspecialchars($x->ou), "</A><BR />\n";
    break;
   case 't':
//    echo "<Center>", $x->t, "</Center>\n";
      echo '<Center><Img Class="Icon" Src="/img/', 
	$CFG->oIcons[$x->t]['file'], '.gif" Alt="', $x->t, '" title="', $CFG->oIcons[$x->t]['title'], '"></Center>';
    break;
   case 'f':
    echo htmlspecialchars($x->flags), "<BR />\n";
  }
 endfor;
}
?>
