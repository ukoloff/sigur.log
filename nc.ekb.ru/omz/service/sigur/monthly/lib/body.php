<?
LoadLib('/dc/db/sigur.connect');

$s = $CFG->sigur->prepare(<<<SQL
  select
    cast(max(LOGTIME) as date) as max,
    cast(min(LOGTIME) as date) as min
  from
    `tc-db-log`.logs
SQL
);
$s->execute();
$dates = $s->fetchObject();
$minmax = "min=$dates->min max=$dates->max";

$today = date('Y-m-d')
?>
<form action="post">
  <input type="date" <?= $minmax ?> value="<?= $today ?>" required />
</form>
