<?
//
// Одна строчка на дату
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
    $row->Проходов = count($x);
    $kn = 'Все Проходы';
    $row->$kn = implode("\n", array_map(function ($row) {
      return implode(' ', array($row->time, directionName($row->dir), $row->gate));
    }, $x));
    return $row;
  }
}
