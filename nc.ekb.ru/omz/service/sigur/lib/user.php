<?
global $CFG;
LoadLib('/dc/db/sigur.connect');
$h = $CFG->sigur;
unset($CFG->sigur);
$CFG->sigur->h = $h;

function sigur_UID($u = '')
{
  global $CFG;
  if (!$u) {
    $u = $CFG->u;
  }
  $dn = user2dn($u);
  if (!$dn) return;
  $dn = substr(utf2str($dn), 0, -1 - strlen($CFG->AD->baseDN));

  $s = $CFG->sigur->h->prepare(
    <<<SQL
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
  $s->execute(array($dn));
  $row = $s->fetch();
  if (!$row) return;
  return $row[0];
}

function sigurUID($u = '')
{
  global $CFG;
  if (!$u) {
    $u = $CFG->u;
  }
  $n = sigur_UID($u);
  if ($n) return $n;
  if (!preg_match('/\D1$/', $u)) return;
  return sigur_UID(substr($u, 0, -1));
}

$CFG->sigur->uid = sigurUID();
?>
