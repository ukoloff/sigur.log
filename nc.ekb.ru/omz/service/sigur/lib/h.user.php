<i>Пользователь Сигур</i>:
<?
if ($CFG->sigur->uid):
  $s = $CFG->sigur->h->prepare(<<<SQL
    select
        U.NAME,
        D.NAME
    from
        personal U
        left join personal D on U.PARENT_ID = D.ID
    where
        U.ID = ?
SQL
  );
  $s->execute(array($CFG->sigur->uid));
  $row = $s->fetch();
  echo htmlspecialchars($row[0]), ' (<i>', htmlspecialchars($row[1]), '</i>)';
else:
  echo "<b>Доступ не предоставлен</b>";
endif;
