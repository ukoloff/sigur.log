<?
global $CFG;

LoadLib('./user');
?>
<i>Пользователь Сигур</i>:
<?
if ($CFG->sigur->uid):
  $s = $CFG->sigur->h->prepare(<<<SQL
    select
        U.NAME,
        D.NAME
    from
        personal U
        left join personal D on U.PARENT_ID = D.ID
    where
        U.ID = ?
SQL
  );
  $s->execute(array($CFG->sigur->uid));
  $row = $s->fetch();
  echo htmlspecialchars($row[0]), ' (<i>', htmlspecialchars($row[1]), '</i>)';
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
<form method='POST' x-target='inner'>
  <table cellspacing="0">
    <tr>
      <td align="right">
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
        </fieldset>
      </td>
      <td valign="top">
        <fieldset>
          <legend><small>Формат файла</small></legend>
          <label>
            <input type="radio" name="format" value="xls" checked>
            XLS<sup title="См. примечание внизу страницы">*</sup>
          </label>
          <br />
          <label>
            <input type="radio" name="format" value="csv">
            CSV
          </label>
        </fieldset>
      </td>
      <td>
        <button type='submit' disabled>
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
