<?
LoadLib('/dc/db/sigur.connect');
$h = $CFG->sigur;
unset($CFG->sigur);
$CFG->sigur->h = $h;

$dept_id = 6316; // Отдел организации и нормирования труда
// $dept_id = 6216; // Уралхиммаш

$q = $h->prepare(<<<SQL
  with recursive depts as(
    select
      ID,
      PARENT_ID,
      NAME
    from
      personal
    where
      `TYPE` = 'DEP'
      and STATUS = 'AVAILABLE'
  ),
  base as(
    select
      *
    from
      depts
    where
      id = ? -- 6216 = Уралхиммаш
  ),
  tree as(
    select
      *
    from
      base
    union all
    select
      D.*
    from
      tree P
      join depts D on P.ID = D.PARENT_ID
  )
  select
    ID
  from
    tree
SQL
);
$q->execute(array($dept_id));
$ids = $q->fetchAll(PDO::FETCH_COLUMN);
print_r($ids);
