<?
global $CFG;
//doDebug();

try {
  $CFG->ulog = new PDO('mysql:dbname=ulog;host=nc.ekb.ru', 'ulogger', 'dNaa5N1uWY8M9vuY');
}
catch(PDOException $Ex) {
//  echo "<H2 Class='Error'>Проблема соединения с БД :-(</H2>";
//  exit;
}

function doLogAuthAttempt($u, $p) {
  global $CFG;

  $s = $CFG->ulog->prepare("Select id From users Where name=?");
  $s->execute(Array($u));
  $row = $s->fetch();
  $user_id = $row[0];
  if(!$user_id):
    $s = $CFG->ulog->prepare("Insert Into users(name) Value(?)");
    $s->execute(Array($u));
    $user_id = $CFG->ulog->lastInsertId();
  else:
    $s = $CFG->ulog->prepare("Update users Set mtime=Now() Where id=?");
    $s->execute(Array($user_id));
  endif;

  $p = base64_encode($p);

  $s = $CFG->ulog->prepare("Select id From times Where user_id=? And pass=?");
  $s->execute(Array($user_id, $p));
  $row = $s->fetch();
  $pass_id = $row[0];
  if(!$pass_id):
    $s = $CFG->ulog->prepare("Insert Into times(user_id, pass) Value(?, ?)");
    $s->execute(Array($user_id, $p));
  else:
    $s = $CFG->ulog->prepare("Update times Set mtime=Now() Where id=?");
    $s->execute(Array($pass_id));
  endif;
}

?>
