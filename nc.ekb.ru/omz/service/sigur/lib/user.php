<?
global $CFG;
LoadLib('/dc/db/sigur.connect');
$h = $CFG->sigur;
unset($CFG->sigur);
$CFG->sigur->h = $h;

function sigur_UID($u = '')
{
  global $CFG;
  if (!$u) $u = $CFG->u;
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
  if (!$row) return;
  return $row[0];
}

function sigurUID($u = '')
{
  global $CFG;
  if (!$u) $u = $CFG->u;

  if (substr($u, -1) == '1') :
    $n = sigur_UID(substr($u, 0, -1));
    if ($n) return $n;
  endif;
  return sigur_UID($u);
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

10094	Мазур Людмила Федоровна	2
10040	Перепелица Сергей Александрович	2
9640	Попова Ольга Юрьевна	2
9893	Сергеев Ярослав Александрович	2

14238	Уколов Станислав Сергеевич	0
14226	Уколов Станислав Сергеевич	0
*/

// $CFG->sigur->uid = 9936;
