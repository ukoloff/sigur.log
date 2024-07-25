<? // Библиотека разборки файлов .menu

$CFG->Menu=&new menuTree();

# Вспомогательная функция для парсера
function backSlashChar($C)
{
 return strtr($C, 'tnr0', "\t\n\r\0");
}

# Переделать строку, содержащую \<контрольные символы>
function backSlashStr($S)
{
 $R='';
 while(true):
  list($A, $B)=explode("\\", $S, 2);
  $R.=$A;
  if(''==$B) return $R;
  $R.=backSlashChar($B{0});
  $S=substr($B, 1);
 endwhile;
}

# Класс, разбирающий булевские выражения для меню if=
class expParser
{
 var $Src;
 var $Pos=0;

 function expParser($Src) { $this->Src=$Src; }

 function Error($Msg)
 {
  if($this->ErrPos) return;
  $this->ErrMsg=$Msg;
  $this->ErrPos=$this->Pos;
 }

 function skipBlanks()
 {
  while(''!=($C=$this->Src{$this->Pos}) and ctype_space($C)) $this->Pos++;
  if('#'==$this->Src{$this->Pos}) $this->Pos=strlen($this->Src);
 }

 function Ch() 
 { 
  return $this->Src{$this->Pos};
 }

 function getCh()
 { 
  return $this->Src{$this->Pos++};
 }

 function Id()
 {
  $this->skipBlanks();
  switch($S=$this->Ch())
  {
   case "<": $S=">";	// <id>
   case '"':		// "id"
   case "'":		// 'id'
    $Delim=$S;
    $S=''; 
    $this->Pos++;
    while(true):
     switch($C=$this->getCh())
     {
      case '': $this->Error('Unterminated string');
      case $Delim: return $S;
      case "\\": $C=backSlashChar($this->getCh());
     }
     $S.=$C;
    endwhile;
   case '': return '';
  }
  if(!preg_match('/^[a-z_]$/', strtolower($S))):
   $this->Error("Id expected");
   return '';
  endif;
  $this->Pos++;
  while(preg_match('/^[a-z_0-9]$/', strtolower($C=$this->Ch()))):
   $S.=$C;
   $this->Pos++;
  endwhile;
  return $S;
 }

 function Term()
 {
  global $CFG;
  $this->skipBlanks();
  switch($this->Ch())
  {
   case '(':
    $this->Pos++;
    $R=$this->X();
    $this->skipBlanks();
    if(')'!=$this->getCh()) $this->Error('Unclosed "("');
    return $R;
   case '@':
    $this->Pos++;
    $this->skipBlanks();
    $Exact=0;
    if('='==$this->Ch()):
     $this->Pos++;
     $Exact=1;
    endif;
    $G=$this->Id();
    return $Exact ? inGroup($G) : inGroupX($G);
   case '$':
    $this->Pos++;
    $v=$CFG;
    while(true):
     $i=$this->Id();
     if(is_object($v)): $v=$v->$i;
     elseif(is_array($v)): $v=$v[$i];
     else:
      $this->Error('Variable not found');
      unset($v);
     endif;
     $this->skipBlanks();
     if('.'!=$this->Ch()) break;
     $this->Pos++;
    endwhile;
    return $v;
  }
  return $CFG->u==$this->Id();
 }

 function U()
 {
  $N=0;
  $this->skipBlanks();
  while('!'==$this->Ch()):
   $N=1-$N;
   $this->Pos++;
   $this->skipBlanks();
  endwhile;
  $R=$this->Term();
  if($N) $R=!$R;
  return $R;
 }

 function A()
 {
  $R=1;
  while(true):
   $RR=$this->U();
   $R=($R and $RR);
   $this->skipBlanks();
   if('&'!=$this->Ch()) return $R;
   $this->Pos++;
  endwhile;
 }

 function X()
 {
  $R=0;
  while(true):
   $RR=$this->A();
   $R=($R or $RR);
   $this->skipBlanks();
   if('|'!=$this->Ch()) return $R;
   $this->Pos++;
  endwhile;
 }

 function Result()
 {
  $R=$this->X();
  $this->skipBlanks();
  if(''!=$this->Ch()) $this->Error('Extra characters after expression');
  return $this->ErrMsg ? false : $R;
 }
}

# Вспомогательный класс для ползания по меню
class menuIterator
{
 var $stack=Array();

 function menuIterator(&$menu)
 {
  $x->list=&$menu->Children;
  $x->pos=0;
  $this->stack[]=&$x;
 }

 function &item()
 {
  $x=&$this->stack;
  $x=&$x[count($x)-1];
  if(!$x) return;
  return $x->list[$x->pos];
 }

 function Level()
 {
  return count($this->stack)-1;
 }

 function advance()
 {
  $x=&$this->stack;
  $x=&$x[count($x)-1];
  $i=&$x->list[$x->pos];
  if(count($i->Children)>0):
   $y->list=&$i->Children;
   $y->pos=0;
   $this->stack[]=&$y;
   return;
  endif;
  while(true):
   $x->pos++;
   if($x->pos<count($x->list)) return;
   array_pop($this->stack);
   if(count($this->stack)<=0) return;
   $x=&$this->stack;
   $x=&$x[count($x)-1];
  endwhile;
 }
}

# Класс, содержащий иерархическое меню
class menuTree
{
 var $Children=Array();

 function &findItem($href)
 {
  if(''==$href)return $this;
  for($i=&new menuIterator($this); $x=&$i->item(); $i->advance())
   if($x->href==$href) return $x; 
  return $this;
 }

 function ins(&$item, &$parent, $pos=-1)
 {
  $c=&$parent->Children;
  if(!is_array($item->Children)) $item->Children=Array();
  if($pos<0) $pos=count($c);
  for($i=count($c)-1; $i>=$pos; $i--) $c[$i+1]=&$c[$i];
  $c[$pos]=$item;
 }

 function insertItem(&$item, $href='')
 {
  if($item)$this->ins($item, $this->findItem($href));
 }

 function insertAfter($item, $after)
 {
 }
  
 function insertBefore($item, $before)
 {
 }

 function parseIni($fileName, $defaultParent='')
 {
  if(!file_exists($fileName)) return;
  $parent=$defaultParent;
  $skip=0;
  $f=fopen($fileName, 'r');
  if(!$f) return;
  while(!feof($f)):
   $s=trim(fgets($f));
   if(preg_match('/^(#|$)/', $s)) continue;
   if('['==$s{0}):
    if(!$skip)$this->insertItem($item, $parent);
    $parent=$defaultParent;
    $skip=0;
    $s=trim(preg_replace('/\].*/', '', substr($s, 1)));
    unset($item);
    $item->href=$s;
    continue;
   endif;
   list($k, $v)=explode('=', $s, 2);
   $v=trim($v);
   switch($k=strtolower(trim($k)))
   {
    case 'target':
    case 'status':
    case 'title':
    case 'text': $item->$k=$v; break;
    case 'parent':$parent=$v; break;
    case 'defaultparent': $defaultParent=$item->href; break;
    case 'if': $p=new expParser($v); $skip=!$p->Result(); break;
   }
  endwhile;
  if(!$skip)$this->insertItem($item, $parent);
  fclose($f);
 }

 function Fill()
 {
  global $CFG;
  $CFG->styleSheets=Array();
  $path=explode('/', $_SERVER['SCRIPT_NAME']);
  $absPath=$_SERVER['DOCUMENT_ROOT'];
  while(count($path)>0):
   if(!($folder=array_shift($path))) continue;
   foreach(glob("$absPath/*.css") as $x) array_push($CFG->styleSheets, "$relPath/".basename($x));
   $this->parseIni("$absPath/.menu", $relPath ? "$relPath/":"");
   $relPath.="/$folder";
   $absPath.="/$folder";
  endwhile;
 }
}

?>
