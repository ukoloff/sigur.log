<?
doDebug();
global $CFG;
LoadLib('/dc/db/sigur.connect');
$h = $CFG->sigur;
unset($CFG->sigur);
$CFG->sigur->h = $h;


$depts = array(6223, 6225, 6229, 6231, 6254, 6260, 6266, 6273, 6280, 6287, 6307, 6309, 6311, 6313, 6316, 6325, 6352, 6387, 6407, 6419, 6431, 6433, 6442, 6452, 6505, 6549, 6594, 6676, 6738, 6788, 6801, 6859, 6863, 6865, 6912, 6942, 6990, 7075, 7144, 7262, 7273, 7369, 7415, 7550, 7628, 7694, 7803, 7932, 8188, 8206, 8466, 8517, 8730, 8759, 12560, 12629, 12633, 12634, 12816, 12977, 13168, 13172, 13364, 13395, 13592, 13616, 13619, 13620, 13621, 13624, 13625, 13629, 13634, 13636, 13637, 13641, 13643, 13663, 13684, 13685, 13686, 13689, 13703, 14078, 14094, 14233, 14260, 14307, 14367, 14397, 14578, 14725, 14727, 14728, 14804, 14841, 14843, 14920, 14938);
$depts = implode(', ', $depts);

$CFG->sigur->h->query(<<<SQL
  set @day = cast(now() as date);
  set @wday = dayofweek(@day);
  set @day = @day - interval if(@wday < 3, @wday + 1, 1) day
SQL
);

$day = $CFG->sigur->h->query('select @day')->fetch();
$day = $day[0];

$s = $CFG->sigur->h->query(
  <<<SQL
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
    U.ID as id,
    cast(L.LOGTIME as date) as "date",
    cast(L.LOGTIME as time) as "time",
    dir,
    Dv.NAME as "gate"
  from
    personal D
    join personal U on D.ID = U.PARENT_ID
    join Logs L on U.ID = L.EMPHINT
    join devices Dv on Dv.ID = DEVHINT
  where
    D.ID in ($depts)
    and Dv.PARENT_ID = 27 -- Инженерный корпус
    and LOGTIME >= @day
    and LOGTIME < @day + interval 1 day
  order by
    D.NAME,
    U.NAME,
    LOGTIME
SQL
);

header("Content-disposition: attachment; filename=\"sigur-$day.csv\"");

$CFG->sigur->data = $s;
require __DIR__ . '/../../lib/csv.php';

exit();
