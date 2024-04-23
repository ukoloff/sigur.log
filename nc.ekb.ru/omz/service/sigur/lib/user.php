<?
global $CFG;
LoadLib('../../dc/db/sigur.connect');
$h = $CFG->sigur;
unset($CFG->sigur);
$CFG->sigur->h = $h;

function sigurUID($u='') {
  global $CFG;
  if (!$u) { $u = $CFG->u; }
  $dn = user2dn($u);
  if (!$dn) return;
  $dn = substr(utf2str($dn), 0, -1-strlen($CFG->AD->baseDN));

  $s = $CFG->sigur->h->prepare(<<<SQL
    Select
      ID
    From
      personal
    Where
      USER_ENABLED
    And
      AD_USER_DN=?
SQL
  );
  $s->execute(Array($dn));
  $row = $s->fetch();
  if (!$row) return;
  return $row[0];
}

?>
