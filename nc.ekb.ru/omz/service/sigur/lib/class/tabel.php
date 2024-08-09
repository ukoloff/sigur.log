<?
//
// Одна строчка на дату
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
    unset($leave);
    foreach(array_reverse($x) as $pass):
      if ($pass->dir != 1) continue;
      $leave = $pass;
      break;
    endforeach;
    $row->Вход = $enter->time;
    $row->Куда = $enter->gate;
    $row->Выход = $leave->time;
    $row->Откуда = $leave->gate;
    $row->Проходов = count($x);
    $kn = 'Все Проходы';
    $row->$kn = implode("\n", array_map(function ($row) {
      return implode(' ', array($row->time, directionName($row->dir), $row->gate));
    }, $x));
    return $row;
  }
}
