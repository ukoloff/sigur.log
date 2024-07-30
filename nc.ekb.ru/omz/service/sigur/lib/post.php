<?
LoadLib('./user');
LoadLib('./depts');

$idx = index_depts(loadDepts());

$depts = array();
foreach ($_POST as $k => $v):
  if (!preg_match('/^\?(\d+)$/', $k, $match))
    continue;
  $i = (int) $match[1];
  if ($idx->$i)
    $depts[] = $i;
endforeach;

$depts = count($depts) ? implode($depts, ', ') : 'NULL';

$dA = $_POST['dA'];
if (!preg_match('/^\d{4}(-\d{2}){2}$/', $dA)):
  $dA = new DateTime();
  $dA = $dA->format('Y-m-d');
endif;
$dZ = $_POST['dZ'];
if (!preg_match('/^\d{4}(-\d{2}){2}$/', $dZ))
  $dZ = $dA;
if ($dZ < $dA):
  $tmp = $dZ;
  $dZ = $dA;
  $dA = $tmp;
endif;

$s = $CFG->sigur->h->prepare(<<<SQL
  with Logs as (
    select
      *,
      ord(substr(LOGDATA, 5, 1)) as dir
    from
      `tc-db-log`.logs
    where
      substr(LOGDATA, 1, 2)=0xFE06
  )
  select
    D.NAME as "Отдел",
    U.POS as "Должность",
    U.NAME as "ФИО",
    U.TABID as "Таб. №",
    cast(L.LOGTIME as date) as "Дата",
    cast(L.LOGTIME as time) as "Время",
    Dv.NAME as "Т. доступа",
    case
      when dir = 1 then 'Выход'
      when dir = 2 then 'Вход'
      else concat('?', dir)
    end As "Направление"
  from
    personal D
    join personal U on D.ID = U.PARENT_ID
    join Logs L on U.ID = L.EMPHINT
    left join devices Dv on Dv.ID = DEVHINT
  where
    D.ID in ($depts)
    and LOGTIME >= ?
    and LOGTIME < ? + interval 1 day
  order by
    D.NAME,
    U.NAME,
    LOGTIME
SQL
);
$s->execute(array($dA, $dZ));
$CFG->sigur->data = $s;

$formats = explode(':', 'xls:csv');
$format = $_POST['format'];
if (!in_array($format, $formats))
  $format = $formats[0];

$t = new DateTime();
$t = $t->format('Y-m-d-H-i-s');
header("Content-disposition: attachment; filename=\"sigur-$t.$format\"");

LoadLib($format);
exit();
?>
