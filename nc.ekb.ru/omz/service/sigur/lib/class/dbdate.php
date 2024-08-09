<?
//
// Group by Date
//
class dbDate extends dbStream
{
  function fetchObject()
  {
    $res = (object) array();
    $id = null;
    $date = null;
    $first = 1;
    while ($row = $this->get()):
      if ($id && ($id != $row->id || $date != $row->date)):
        $this->unget($row);
        return $res;
      endif;
      $id = $row->id;
      $date = $row->date;

      $newUser = $id != $this->id;
      $this->id = $id;
      $mode = 0;
      $extra = (object) array();
      foreach ($row as $k => $v):
        switch ($k):
          case 'id':
            $mode = 1;
            if ($first)
              $res->Дата = $date;
          case 'date':
            break;
          default:
            if (!$mode):
              if ($first):
                $res->$k = $newUser ? $v : '';
              endif;
            else:
              $extra->$k = $v;
            endif;
        endswitch;
      endforeach;
      $first = 0;
      $res->list[] = $extra;
    endwhile;
    if ($res->list)
      return $res;
  }
}
