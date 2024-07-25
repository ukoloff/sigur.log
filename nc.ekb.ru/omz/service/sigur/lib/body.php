<script src=sigur.js></script>
<?
global $CFG;
LoadLib('./user');
?>
<i>Пользователь Сигур</i>:
<?
if ($CFG->sigur->uid):
  $s = $CFG->sigur->h->prepare('Select NAME From personal Where ID = ?');
  $s->execute(array($CFG->sigur->uid));
  $row = $s->fetch();
  echo htmlspecialchars($row[0]);
else:
  echo "<b>Доступ не предоставлен</b>";
endif;
// echo "<hr>";

function d2s($date)
{
  return preg_replace('/T.*/', '', $date->format('c'));
}

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

?>
<form method='POST' target='inner'>
  <table cellspacing="0">
    <tr>
      <td align="right">
        <label>
          С
          <input type='date' name='dA' <?= $minmax ?> required value='<?= $d1 ?>' />
        </label>
        <br />
        <label>
          По
          <input type='date' name='dZ' <?= $minmax ?> required value='<?= $d0 ?>' />
        </label>
      </td>
      <td width="30%">
        <label>
          <input type="radio" name="format" value="xls" checked>
          XLS<sup>*</sup>
        </label>
        <br />
        <label>
          <input type="radio" name="format" value="csv">
          CSV
        </label>
      </td>
      <td>
        <button type='submit'>
          Сформировать<br />
          отчёт!
        </button>
      </td>
    </tr>
  </table>


  <fieldset>
    <legend>Подразделения (<span></span>)</legend>
    <?
    LoadLib('./depts');
    renderDepts(loadDepts());
    ?>
  </fieldset>
</form>
<iframe name=inner></iframe>

<small>
  <sup>*</sup>
  Современные версии Microsoft Excel предупреждают,
  что полученный XLS-файл возможно повреждён,
  тем не менее, открывают его нормально.
</small>
