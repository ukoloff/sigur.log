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
    $extraFields = array();
    while ($row = $this->get()):
      if (!$first && ($id != $row->id || $date != $row->date)):
        $this->unget($row);
        return $res;
      endif;
      $id = $row->id;
      $date = $row->date;

      if ($first):
        $newUser = $id != $this->id;
        $this->id = $id;
        $mode = 0;
        foreach ($row as $k => $v):
          switch ($k):
            case 'id':
              $mode = 1;
            case 'date':
              break;
            default:
              if ($mode):
                $extraFields[] = $k;
              elseif ($newUser):
                $res->$k = $v;
              endif;
          endswitch;
        endforeach;
        $res->Дата = $date;
      endif;
      $first = 0;

      $extra = (object) array();
      foreach ($extraFields as $k):
        $extra->$k = $row->$k;
      endforeach;
      $res->list[] = $extra;
    endwhile;
    if (!$first)
      return $res;
  }
}
