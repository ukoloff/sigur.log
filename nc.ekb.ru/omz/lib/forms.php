<?	// Вывод и обработка форм

global $CFG;
$CFG->defaults->Input->W=25;
$CFG->defaults->Input->H=6;
$CFG->defaults->Input->BR="<BR />\n";	// Перевод строки в метке для поля ввода

function BR()
{
 echo "<br />\n";
}

function HR()
{
 echo "<hr />\n";
}

function itemError($var)
{
 global $CFG;
 if(!$var) return;
 if($CFG->Errors->$var) echo "<br /><Span Class='itemError'>^", nl2br(htmlspecialchars($CFG->Errors->$var)), "</Span>";
}

function Submit()
{
 global $CFG;
 echo "<Input Type='Submit' Value='", 'new'==$CFG->params->x? 'Создать' : 'Изменить', "' />";
}

function CheckBox($var, $text)
{
 global $CFG;
 $CFG->lastId++;
 echo "<Input Type='CheckBox' id='cb", $CFG->lastId,"' Name='", htmlspecialchars($var), "'";
 if($CFG->entry->$var) echo " Checked ";
 echo " />\n<Label For='cb", $CFG->lastId, "'> $text</Label>\n";
 itemError($var);
}

function RadioButton($var, $value, $text)
{
 global $CFG;
 $CFG->lastId++;
 echo "<Input Type='Radio' id='rb", $CFG->lastId,
    "' Name='", htmlspecialchars($var), "' Value='", htmlspecialchars($value), "'";
 if($CFG->entry->$var==$value) echo " Checked ";
 echo " />\n<Label For='rb", $CFG->lastId, "'> $text</Label>\n";
}

function RadioGroup($var, $Options)
{
 foreach($Options as $k=>$v):
  RadioButton($var, $k, $v); BR();
 endforeach;
 itemError($var);
}

function Input($var, $text)
{
 global $CFG;
 $CFG->lastId++;
 echo "<Label For='ed", $CFG->lastId, "'><Small>$text</Small></Label>";
 echo $CFG->defaults->Input->BR; // ) echo "<BR />\n";
 echo "<Input id='ed", $CFG->lastId,
    "' Name=\"", htmlspecialchars($var), "\" Value=\"", htmlspecialchars($CFG->entry->$var), 
    "\" Size='", $CFG->defaults->Input->W;
 if($CFG->defaults->Input->maxWidth) echo "' Style='width: 100%;";
 echo "'";
 if($CFG->defaults->Input->extraAttr) echo ' ', $CFG->defaults->Input->extraAttr;
 echo " />\n";
 itemError($var);
}

function TextArea($var, $text)
{
 global $CFG;
 $CFG->lastId++;
 echo "<Label For='tx", $CFG->lastId, "'><Small>$text</Small></Label>";
 echo $CFG->defaults->Input->BR; // ) echo "<BR />\n";
 echo "<TextArea id='tx", $CFG->lastId,
    "' Name=\"", htmlspecialchars($var), 
    "\" Cols='", $CFG->defaults->Input->W, "' Rows='", $CFG->defaults->Input->H, "'";
 if($CFG->defaults->Input->maxWidth) echo " Style='width: 100%;'";
 echo ">\n", htmlspecialchars($CFG->entry->$var),
    "</TextArea><BR />\n";
 itemError($var);
}

function Select($var, $Options, $text='')
{
 global $CFG;
 $CFG->lastId++;
 if($text) echo "<Label For='sl", $CFG->lastId, "'><Small>$text</Small></Label>";
 echo $CFG->defaults->Input->BR; // echo "<BR />\n";
 echo '<Select id="sl', $CFG->lastId, '"';
 if(strlen($var)) echo ' Name="', htmlspecialchars($var), '"';
 if($CFG->defaults->Input->extraAttr) echo ' ', $CFG->defaults->Input->extraAttr;
 echo ">\n";
 foreach($Options as $k=>$v):
  echo "<Option Value=\"", htmlspecialchars($k), "\" "; 
  if($var and $CFG->entry->$var==$k) echo "Selected ";
  echo "/>", $v, "\n";
 endforeach;
 echo "</Select>\n";
 itemError($var);
}

?>
