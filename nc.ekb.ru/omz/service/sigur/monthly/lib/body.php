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

$today = date('Y-m-d');
$start = date('m.Y');
?>
<form method="post">
  <div style="display: none;">
    <input type="date" <?= $minmax ?> value="<?= $today ?>" required />
  </div>
  Месяц:
  <span><?= $start ?></span>
  <small>
    <?
    foreach (explode(',', 'Этот,Предыдущий,Выбрать') as $a):
      echo "<a>$a</a>\n";
    endforeach;
    ?>
  </small>
  <input type="submit" value="Сформировать отчёт!" />
</form>
