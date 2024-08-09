<?
//
// Журнал в две колонки
// Журнал входов-выходов
//
class dbInOut extends dbList
{
  function get_list($row)
  {
    $res = array();
    $lastPass = null;
    foreach ($row->list as $pass):
      if ($pass->dir != 1 && $pass->dir != 2)
        continue;
      if ($lastPass->dir == 2 && $pass->dir == 1):
        $z = end($res);
      else:
        $z = emptyPass();
        $res[] = $z;
      endif;
      storePass($z, $pass);
      $lastPass = $pass;
    endforeach;
    return $res;
  }
}

