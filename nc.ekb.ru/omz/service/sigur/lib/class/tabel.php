<?
//
// ���� ������� �� ����
//
class Tabel extends dbStream
{
  function fetchObject()
  {
    $row = $this->src->fetchObject();
    if (!$row)
      return;
    $x = $row->list;
    unset($row->list);
    $row->�������� = count($x);
    $kn = '��� �������';
    $row->$kn = implode("\n", array_map(function ($row) {
      return implode(' ', array($row->time, directionName($row->dir), $row->gate));
    }, $x));
    return $row;
  }
}
