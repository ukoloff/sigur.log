<?
$d = new DateTime();
$d0 = d2s($d);
$d->modify('first day of this month');
$d1 = d2s($d);
$d->modify('last day of this month');
$dZ = d2s($d);

$s = $CFG->sigur->h->prepare(<<<SQL
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

function d2s($date)
{
  return preg_replace('/T.*/', '', $date->format('c'));
}
?>
<fieldset>
  <legend><small>Даты</small></legend>
  <label>
    С
    <input type='date' name='dA' <?= $minmax ?> required value='<?= $d1 ?>' />
  </label>
  <br />
  <label>
    По
    <input type='date' name='dZ' <?= $minmax ?> required value='<?= $d0 ?>' />
  </label>
  <div id="datez">
    <a href="#">Выбор</a>
    <? LoadLib('h.popup') ?>
  </div>
</fieldset>
