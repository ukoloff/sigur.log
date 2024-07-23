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
SQL
);
$s->execute();

// doDebug();
unset($idx);
while ($row = $s->fetch(PDO::FETCH_OBJ)) :
  $key = $row->id;
  $idx->$key = $row;
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
echo "<pre>";
print_r($root);
