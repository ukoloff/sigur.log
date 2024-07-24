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
  <input type='submit' value=' Отправить! ' />
  <?
  LoadLib('./depts');
  renderDepts(loadDepts());
  ?>
</form>
<iframe name=inner></iframe>
