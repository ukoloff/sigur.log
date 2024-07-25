<?
$CFG->defaults->pagesize=20;
$CFG->defaults->p=0;

function pageStart($numLines)
{
 global $CFG;
 $numLines=(int)$numLines;
 if($numLines<0) $numLines=0;

 if(($CFG->params->pagesize=(int)$_REQUEST['pagesize'])<1)$CFG->params->pagesize=$CFG->defaults->pagesize;
 $CFG->params->p=(int)$_REQUEST['p'];

 $CFG->pages=ceil($numLines/$CFG->params->pagesize);
 if($CFG->params->p>=$CFG->pages) $CFG->params->p=$CFG->pages-1;
 if($CFG->params->p<0)$CFG->params->p=0;
 return $CFG->params->p*$CFG->params->pagesize;
}

function isLastLine($Line)
{
 global $CFG;
 return 1+(int)$Line>=$CFG->params->pagesize*(1+$CFG->params->p);
}

function pagesStop()
{
 global $CFG;
 unset($CFG->params->p);
 unset($CFG->params->pagesize);
}

function pageNavigator()
{
 global $CFG;
 if($CFG->pages<2) return;
 echo '<Div Class="PageNav"><A Class="Thumb" hRef="./', hRefPrev(), "\">&laquo;</A>\n";
 for($i=0; $i<$CFG->pages; $i++)
   echo "<A", (($i==$CFG->params->p)? ' Class="Thumb"' : ''), ' hRef="./', hRef('p', $i), '">', 1+$i, "</A>\n";
 echo '<A Class="Thumb" hRef="./', hRefNext(), "\">&raquo;</A></Div>\n";
}

function hRefPrev()
{
 global $CFG;
 if(($p=$CFG->params->p)<=0) $p=$CFG->pages;
 return hRef('p', --$p);
}

function hRefNext()
{
 global $CFG;
 if(($p=$CFG->params->p+1)>=$CFG->pages)$p=0;
 return hRef('p', $p);
}


function hRefAllPages()
{
 global $CFG;
 return hRef('p', 0, 'pagesize', $CFG->params->pagesize*(1+$CFG->pages));
}

?>
