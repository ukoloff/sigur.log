<?
LoadLib('./user');
LoadLib('./depts');

function index_dept($dept, $idx)
{
  foreach ($dept->ch as $d):
    $id = $d->id;
    $idx->$id = 1;
    index_dept($d, $idx);
  endforeach;
}

$idx = (object) null;
index_dept(loadDepts(), $idx);

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

$s = $CFG->sigur->h->prepare($sql = <<<SQL
  with Logs as (
    select
      *,
      ord(substr(LOGDATA, 5, 1)) as dir
    from
      `tc-db-log`.logs
  )
  select
    D.NAME as "Отдел",
    U.NAME as "ФИО",
    U.TABID as "Таб. №",
    U.POS as "Должность",
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
LoadLib('./xls');
renderXLS($s);
exit();
?>
