<?
header("Content-Type: text/csv");

$rows = 0;
while ($row = $CFG->sigur->data->fetchObject()):
  if ($rows == 0):
    $keys = array_keys(get_object_vars($row));
    echo "¹;", implode(';', $keys), "\n";
  endif;
  $rows++;
  echo $rows;
  foreach ($keys as $k):
    $v = $row->$k;
    if (preg_match('/^\s+|\s+$|[,"\r\n]/', $v))
      $v = '"' . str_replace('"', '""', $v) . '"';
    echo ";$v";
  endforeach;
  echo "\n";
endwhile;
