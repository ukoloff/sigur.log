<?
//
// ���� ������� �� ����
//
class Tabel extends dbStream
{
  function fetchObject()
  {
    $row = $this->get();
    if (!$row)
      return;
    $x = $row->list;
    unset($row->list);

    unset($enter);
    foreach($x as $pass):
      if ($pass->dir != 2) continue;
      $enter = $pass;
      break;
    endforeach;
    storePass($row, $enter);

    unset($leave);
    foreach(array_reverse($x) as $pass):
      if ($pass->dir != 1) continue;
      $leave = $pass;
      break;
    endforeach;
    storePass($row, $leave);

    $row->�������� = count($x);
    $kn = '��� �������';
    $row->$kn = implode("\n", array_map(function ($row) {
      return implode(' ', array($row->time, directionName($row->dir), $row->gate));
    }, $x));
    return $row;
  }
}
