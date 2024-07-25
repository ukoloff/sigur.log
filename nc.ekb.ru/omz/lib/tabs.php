<?
$CFG->tabs=Array();
$CFG->params->x=$_REQUEST['x'];

function tabsAdjust()
{
 global $CFG;
 if(!isset($CFG->tabs[$CFG->defaults->x])):
  reset($CFG->tabs);
  $CFG->defaults->x=key($CFG->tabs);
 endif;
 if(!isset($CFG->tabs[$CFG->params->x])):
  $CFG->params->x=$CFG->defaults->x;
 endif;
}

function tabsHeader()
{
 global $CFG;
 tabsAdjust();
 echo "<Table Class='Tabs' CellSpacing='0' CellPadding='0'><TR Class='Tabs'><TD><Table CellSpacing='0'><TR>\n";
 foreach($CFG->tabs as $k=>$v):
  if(!$k) continue;
  echo "<TD Class='Empty'>&nbsp;</TD>\n<TD NoWrap Class='", 
   ($k==$CFG->params->x ? 'Active' : 'Passive'), "'><A hRef='./",
   htmlspecialchars(hRef('x', $k)), "'>",
   htmlspecialchars($v), "</A></TD>\n";
 endforeach;
 echo "<TD Class='EOL'><BR /></TD></TR></Table></TD></TR><TR><TD Class='Page' vAlign='top'>"; 
// if(inGroupX('#modifyDIT')) 
 echo "<Script Src='/omz/xtab.js'></Script>";
}

# Действия перед выводом текста страницы
function tabsInit()
{
 global $CFG;
 tabsAdjust();

 if(!mayRender()) return;
 checkCSRF();
 LoadLib($CFG->params->x.".init", 1);
 LoadLib($CFG->params->x.".".strtolower($_SERVER['REQUEST_METHOD']), 1);
}

# Вывод текста страницы
function tabsBody()
{
 global $CFG;
 tabsHeader();
 LoadLib("tabs", 1);
 LoadLib($CFG->params->x.".body", 1);
 echo "<BR /></TD></TR></Table>\n";
}

function tabName()
{
 global $CFG;
 tabsAdjust();
 return $CFG->tabs[$CFG->params->x];
}

?>
