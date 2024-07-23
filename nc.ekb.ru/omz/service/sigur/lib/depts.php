<?php // Load Departments...
$s = $CFG->sigur->h->prepare(
  <<<SQL
  select
    ID as id,
    PARENT_ID as pid,
    NAME as name,
    exists(
      select
        *
      from
        personal as U
        join `tc-db-log`.logs as L on U.id = L.EMPHINT
      where
        U.PARENT_ID = p.ID
    ) as Z
  from
    personal p
  where
    type = 'DEP'
    and STATUS = 'AVAILABLE'
  order by name
SQL
);
$s->execute();

// doDebug();
unset($idx);
while ($row = $s->fetch(PDO::FETCH_OBJ)) :
  $key = $row->id;
  $idx->$key = $row;
  $row->count = 0;
  $row->ch = Array();
endwhile;
unset($root);
$root->ch = Array();
foreach ($idx as $k=>$v):
  // print_r($v);
  $pid = $v->pid;
  $p = $idx->$pid;
  if (!$p) $p = $root;
  $p->ch[] = $v;
endforeach;

function count_children($dept) {
  $res = 0;
  foreach ($dept->ch as $k=>$v):
    $res += count_children($v) + 1;
    if ($v->Z) $dept->Z = 1;
  endforeach;
  return $dept->count = $res;
}
count_children($root);

echo "<pre>";
print_r($root);
