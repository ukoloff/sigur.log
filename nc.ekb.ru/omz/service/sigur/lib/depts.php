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
  $row->count = 0;  // Количество всех потомков
  $row->ch = array();
endwhile;
unset($root);
$root->ch = array();
foreach ($idx as $k => $v) :
  $pid = $v->pid;
  $p = $idx->$pid;
  if (!$p) $p = $root;
  $p->ch[] = $v;
endforeach;

function count_children($dept)
{
  $res = 0;
  foreach ($dept->ch as $k => $v) :
    $res += count_children($v) + 1;
    if ($v->Z) $dept->Z = 1;  // В подразделении вообще есть проходы
  endforeach;
  return $dept->count = $res;
}
count_children($root);

// Достанем отделы пользователя
$s = $CFG->sigur->h->prepare(
  <<<SQL
  select
      EMP_ID
  from
      reportuserdep
  where
      USER_ID = ?
SQL
);
$s->execute(array($CFG->sigur->uid));
while ($row = $s->fetch()) :
  $id = $row[0];
  if ($idx->$id) $idx->$id->view = 1; // Пометили, что заказан просмотр подразделения
endwhile;

function count_views($dept)
{
  $res = 0;
  foreach ($dept->ch as $k => $v) :
    $res += count_views($v);
    if ($v->view) $dept->view = 1;
  endforeach;
  $dept->vcount = $res;
  if ($dept->view) $res++;
  return $res;
}
count_views($root);

if (!$root->vcount) :
  foreach ($idx as $k => $v) :
    if ($v->Z) $v->view = 1;
  endforeach;
  count_views($root);
endif;

function drop_depts($dept)
{
  $dept->ch = array_values(array_filter($dept->ch, function ($v, $k) {
    if (!$v->view) return;
    drop_depts(($v));
    return 1;
  }));
}
drop_depts($root);

// Пометим подразделения, которые невозможно будет выбрать
$root->avail = $root->vcount;
foreach ($idx as $k => $v) :
  if (!$v->view) continue;
  $v->ro = !$v->Z || $v->vcount && $v->vcount != $v->count;
  if ($v->ro) $root->avail--;
endforeach;

echo "\n<div id='/*'>\n";

function out_dept($dept)
{
  foreach ($dept->ch as $d) :
    $collapse = count($d->ch) > 1;
    echo '<div><a class=Q id=:', $d->id, ' href=#>', $collapse ? '+' : '-' ,'</a>',
      '<label><input type=checkbox',
      $d->ro ? ' disabled' : '',
      ">\n",
      htmlspecialchars($d->name),
      "</label>\n";
    if (count($d->ch)):
      echo '<div class="Q', $collapse ? ' hide' : '', '" id=/', $d->id,'>';
      out_dept($d);
      echo "</div>";
    endif;
    echo "</div>";
  endforeach;
}
out_dept($root);
?>
</div>
