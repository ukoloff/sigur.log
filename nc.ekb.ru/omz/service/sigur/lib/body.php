<script src=sigur.js></script>
<?
global $CFG;
LoadLib('./user');
if (!$CFG->sigur->uid)
  return LoadLib('./403');
?>
<i>������������ �����</i>:
<?
$s = $CFG->sigur->h->prepare('Select NAME From personal Where ID = ?');
$s->execute(array($CFG->sigur->uid));
$row = $s->fetch();
echo htmlspecialchars($row[0]);

$d = new DateTime();
$d->modify('first day of this month');

function d2s($date) {
  return preg_replace('/T.*/', '', $date->format('c'));
}

?>
<form method='POST' target='inner'>
  <label>
    � ����
    <input type='date' name='dA' required value='<?= d2s($d) ?>' />
  </label>
  <? $d->modify('last day of this month'); ?>
  <label>
    �� ����
    <input type='date' name='dZ' required value='<?= d2s($d) ?>' />
  </label>
  <input type='submit' value=' ������������ �����! ' />
  <fieldset>
    <legend>������������� (<span></span>)</legend>
    <?
    LoadLib('./depts');
    renderDepts(loadDepts());
    ?>
  </fieldset>
</form>
<iframe name=inner></iframe>
