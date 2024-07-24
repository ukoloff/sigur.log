<script src=sigur.js></script>
<?
global $CFG;
LoadLib('./user');
if (!$CFG->sigur->uid) return LoadLib('./403');
?>
<i>Пользователь Сигур</i>:
<?
$s = $CFG->sigur->h->prepare('Select NAME From personal Where ID = ?');
$s->execute(array($CFG->sigur->uid));
$row = $s->fetch();
echo htmlspecialchars($row[0]);

?>
<form method='POST' target='inner'>
  <label>
    С даты
    <input type='date' name='dA' required value='2024-07-01' />
  </label>
  <label>
    По дату
    <input type='date' name='dZ' required value='2024-07-31' />
  </label>
  <input type='submit' value=' Отправить! ' />
  <fieldset><legend>Подразделения (<span></span>)</legend>
  <?
  LoadLib('./depts');
  renderDepts(loadDepts());
  ?>
  </fieldset>
</form>
<iframe name=inner></iframe>
