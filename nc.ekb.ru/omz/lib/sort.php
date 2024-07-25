<?
$CFG->params->sort=trim($_REQUEST['sort']);

function AdjustSort()
{
 global $CFG;
 unset($CFG->currentSort);
 $S=$CFG->params->sort;
 if(!strlen($S))$S=$CFG->params->sort=$CFG->defaults->sort;
 $X='';
 for(; $c=$S{0}; $S=substr($S, 1)):
  if($CFG->currentSort[strtolower($c)]) continue;	// Уже сортируем по этой букве
  if(!($x=$CFG->sort[strtolower($c)])) continue;	// Не умеем сортировать по этой букве
  $X.=$c;
  unset($z);
  $z->c=strtolower($c);
  $z->rev=(!$x['rev'])==($z->c!=$c);
  $z->field=$x['field'];
  $CFG->currentSort[$z->c]=$z;
 endfor;
 $CFG->params->sort=$X;
}

function myCmp(&$a, &$b)
{
 global $CFG;
 foreach($CFG->currentSort as $k=>$v):
  $f=$v->field;
  $af=$a->$f; $bf=$b->$f;
  if($af==$bf) continue;
  $R=$af<$bf ? -1: 1;
  if($v->rev) $R=-$R;
  return $R;
 endforeach;
 return 0;
}

function sortArray(&$arr)
{
 AdjustSort();
 if(is_array($arr)) usort($arr, myCmp);
}

function sqlOrderBy()
{
 global $CFG;
 AdjustSort();
 $S='';
 foreach($CFG->currentSort as $k=>$v):
  $S.=$S? ', ' : ' Order By ';
  $S.=$v->field;
  $S.=$v->rev ? ' Desc' : ' Asc';
 endforeach;
 return $S;
}

function sortLink($c)
{
 global $CFG;
 $A="<Sup><A Class='Sort'\nhRef='./";
 $s=$CFG->params->sort;
 $p=strpos(strtolower($s), strtolower($c));
 if(false===$p):
  $x='&rsaquo;&lsaquo;';
  $title="Не отсортировано \nОтсортировать";
 else:
  $title='Отсортировано по ';
  if($CFG->currentSort[strtolower($c)]->rev):
   $title.='убыванию ';
   $x='&laquo;';	// '&or;'
  else:
   $title.='возрастанию ';
   $x='&raquo;';	// '&and;'
  endif;
  if($p):
   $p++;
   $title.="\nПозиция: $p \nОтсортировать";
  else:
   $title.="\nПересортировать";
   $c=$s{0};
   $c=(strtoupper($c)==$c)? strtolower($c) : strtoupper($c);
  endif;
 endif;
 $s=$c.strtr($s, array(strtolower($c)=>'', strtoupper($c)=>''));
 $A.=hRef('sort', $s);
 $A.="' Title='$title'>$x</A></Sup>";
 return $A;
}

function sortedHeader($template)
{
 global $CFG;
 echo "<Table Border CellSpacing='0' Width='100%'><THead><TR Class='tHeader'>\n";
 for($i=0; $c=$template{$i}; $i++):
  $x=&$CFG->sort[$c];
  echo "<TH ";
  if($x['title']) echo ' Title="', $x['title'], '" ';
  echo ">", $x['name'];
  if($x['field']) echo sortLink($c);
  echo "</TH>\n";
 endfor;
 echo "</TR></THead>\n";
}

function sortedFooter()
{
 echo "\n</Table>";
}

?>
