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
    $row->Проходы = implode("\n", array_map(function ($row) {
      return implode(' ', array_values(get_object_vars($row)));
    }, $x));
    return $row;
  }

}
