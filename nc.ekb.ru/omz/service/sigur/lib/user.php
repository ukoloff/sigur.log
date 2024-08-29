<?
global $CFG;
LoadLib('/dc/db/sigur.connect');
$h = $CFG->sigur;
unset($CFG->sigur);
$CFG->sigur->h = $h;

// �������� ���� � AD
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
        EXTID = ?
        And USER_ENABLED
        And USER_T_SSPILOGIN
        And USER_T_REPORTS
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

  $uid = sigur_UID($u);
  if ($uid)
    return $uid;

  if (!preg_match('/\D1$/', $u))
    return;
  return sigur_UID(substr($u, 0, -1));
}

$CFG->sigur->uid = sigurUID();

/*
6388	����� ��������� ���������	756
9950	������� ������ �����������	734
8240	������ ������ ����������	680

12390	���������� ����� �����������	107
9602	�������� ����� ��������	99
9508	��������� ����� ������������	95

9687	�������� ������� ����������	13
9936	������ ����� ������������	12
9708	�������� ��������� ��������	12

9745	��������� �������� ���������	7
9985	������ ��� ����������	7
9532	�������� ������ ��������	7

10138	����� ������ ���������	3
10550	�������� �������� �����������	3
13337	�������� ����� ����������	3

10094	����� ������� ���������	2
10040	���������� ������ �������������	2
9640	������ ����� �������	2
9893	������� ������� �������������	2

14238	������ ��������� ���������	0
14226	������ ��������� ���������	0
*/

// $CFG->sigur->uid = 9936;
