<?
//
// Æóğíàë â îäíó êîëîíêó
// Âñå ïğîõîäû
//
class dbPasses extends dbList {
  function get_list($row)
  {
    return array_map(function ($row) {
      $res = null;
      $res->Âğåìÿ = $row->time;
      $res->Íàïğàâëåíèå = directionName($row->dir);
      $kn = 'Ò. äîñòóïà';
      $res->$kn = $row->gate;
      return $res;
    }, $row->list);
  }
}
