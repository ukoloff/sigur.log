<script src=sigur.js></script>
<?
global $CFG;
LoadLib('./user');
if (!$CFG->sigur->uid) return LoadLib('./403');
?>
<i>������������ �����</i>:
<?
$s = $CFG->sigur->h->prepare('Select NAME From personal Where ID = ?');
$s->execute(array($CFG->sigur->uid));
$row = $s->fetch();
echo htmlspecialchars($row[0]);

?>
<form method='POST' target='inner'>
  <label>
    � ����
    <input type='date' name='dA' required />
  </label>
  <label>
    �� ����
    <input type='date' name='dZ' required />
  </label>
  <input type='submit' value=' ���������! ' />
  <?
  LoadLib('./depts');
  renderDepts(loadDepts());
  ?>
</form>
<iframe name=inner></iframe>
