<?
global $CFG;
LoadLib('/dc/db/sigur.connect');
$h = $CFG->sigur;
unset($CFG->sigur);
$CFG->sigur->h = $h;

// Çàïàñíîé âõîä â AD
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
  global $CFG;
  if (!$u)
    $u = $_SESSION['u'];

  if (substr($u, -1) == '1'):
    $n = sigur_UID(substr($u, 0, -1));
    if ($n)
      return $n;
  endif;
  return sigur_UID($u);
}

$CFG->sigur->uid = sigurUID();

/*
6388	Áàéåğ Åêàòåğèíà Àíäğååâíà	756
9950	Õîìÿêîâ Ìèõàèë Ãğèãîğüåâè÷	734
8240	Ñìîëèí Ñåğãåé Âèêòîğîâè÷	680

12390	Èáğàãèìîâà Åëåíà Àíàòîëüåâíà	107
9602	Öàğüêîâà Åëåíà Îëåãîâíà	99
9508	Òèíüãàåâà Îëüãà Âëàäèìèğîâíà	95

9687	Êîğåïèíà Íàòàëüÿ Íèêîëàåâíà	13
9936	Êó÷èíà Åëåíà Âëàäèìèğîâíà	12
9708	Ïåòóõîâà Åëèçàâåòà Èãîğåâíà	12

9745	Êğàâ÷åíêî Ñâåòëàíà Ñåğãååâíà	7
9985	Âîéíîâ Ğèì Ğàôàıëåâè÷	7
9532	Ñàëèõîâà Ëàğèñà Ïåòğîâíà	7

10138	Ìàìèí Ìàğèíà Ğèíàòîâíà	3
10550	Áğûêñèíà Ñâåòëàíà Àíàòîëüåâíà	3
13337	Áåğ¸çêèíà Îëüãà Ëåîíèäîâíà	3

10094	Ìàçóğ Ëşäìèëà Ôåäîğîâíà	2
10040	Ïåğåïåëèöà Ñåğãåé Àëåêñàíäğîâè÷	2
9640	Ïîïîâà Îëüãà Şğüåâíà	2
9893	Ñåğãååâ ßğîñëàâ Àëåêñàíäğîâè÷	2

14238	Óêîëîâ Ñòàíèñëàâ Ñåğãååâè÷	0
14226	Óêîëîâ Ñòàíèñëàâ Ñåğãååâè÷	0
*/

// $CFG->sigur->uid = 9936;
