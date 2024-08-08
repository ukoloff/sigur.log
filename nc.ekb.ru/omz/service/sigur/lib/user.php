<?
global $CFG;
LoadLib('/dc/db/sigur.connect');
$h = $CFG->sigur;
unset($CFG->sigur);
$CFG->sigur->h = $h;

// Запасной вход в AD
if (!$CFG->u):
  require '/etc/nc.ekb.ru/passwd/nc.ekb.ru.php';
  ldapCheckPass($adLogin, $adPassword);
endif;


function sigur_UID($u = '')
{
  global $CFG;
  if (!$u)
    $u = $_SESSION['u'];
  $g = getEntry(user2dn($u), 'objectguid');
  $g = bin2hex($g['objectguid'][0]);

  $s = $CFG->sigur->h->prepare(
    <<<SQL
      Select
        ID
      From
        personal
      Where
        USER_ENABLED
        And EXTID = ?
SQL
  );
  $s->execute(array($g));
  $row = $s->fetch();
  if (!$row)
    return;
  return $row[0];
}

function sigurUID($u = '')
{
  if (!$u)
    $u = $_SESSION['u'];
  echo "<!-- [$u] -->";
  $uid = sigur_UID($u);
  if ($uid)
    return $uid;

  if (!preg_match('/\D1$/', $u))
    return;
  return sigur_UID(substr($u, 0, -1));
}

$CFG->sigur->uid = sigurUID();

/*
6388	Байер Екатерина Андреевна	756
9950	Хомяков Михаил Григорьевич	734
8240	Смолин Сергей Викторович	680

12390	Ибрагимова Елена Анатольевна	107
9602	Царькова Елена Олеговна	99
9508	Тиньгаева Ольга Владимировна	95

9687	Корепина Наталья Николаевна	13
9936	Кучина Елена Владимировна	12
9708	Петухова Елизавета Игоревна	12

9745	Кравченко Светлана Сергеевна	7
9985	Войнов Рим Рафаэлевич	7
9532	Салихова Лариса Петровна	7

10138	Мамин Марина Ринатовна	3
10550	Брыксина Светлана Анатольевна	3
13337	Берёзкина Ольга Леонидовна	3

10094	Мазур Людмила Федоровна	2
10040	Перепелица Сергей Александрович	2
9640	Попова Ольга Юрьевна	2
9893	Сергеев Ярослав Александрович	2

14238	Уколов Станислав Сергеевич	0
14226	Уколов Станислав Сергеевич	0
*/

// $CFG->sigur->uid = 9936;
