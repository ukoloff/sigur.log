<?
//
// ������ � ���� �������
// ��� �������
//
class dbPasses extends dbList {
  function get_list($row)
  {
    return array_map(function ($row) {
      $res = null;
      $res->����� = $row->time;
      $res->����������� = directionName($row->dir);
      $kn = '�. �������';
      $res->$kn = $row->gate;
      return $res;
    }, $row->list);
  }
}
