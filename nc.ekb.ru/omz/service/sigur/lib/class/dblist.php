<?
//
// Generate sublist (several rows)
//
class dbList extends dbStream
{
  function get_list($row)
  {
  }

  function fetchObject()
  {
    unset($row);
    while (!$this->queue || !count($this->queue)):
      $row = $this->get();
      if (!$row)
        return;
      $list = $this->get_list($row);
      if (!$list)
        $list = $row->list;
      unset($row->list);
      $this->queue = $list;
    endwhile;
    $data = array_shift($this->queue);
    if (!$row):
      $row = $data;
    else:
      foreach ($data as $k => $v)
        $row->$k = $v;
    endif;
    return $row;
  }
}
