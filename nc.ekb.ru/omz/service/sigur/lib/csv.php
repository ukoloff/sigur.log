<?
header("Content-Type: text/csv");

$rows = 0;
while ($row = $CFG->sigur->data->fetchObject()):
  if ($rows == 0):
    echo "¹,";
    foreach ($row as $k => $v):
      echo "$k,";
    endforeach;
    echo "\n";
  endif;
  $rows++;
  echo "$rows,";
  foreach ($row as $k => $v):
    if (preg_match('/[,"]/', $v))
      $v = '"' . str_replace('"', '""', $v) . '"';
    echo $v, ",";
  endforeach;
  echo "\n";
endwhile;
