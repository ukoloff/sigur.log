<?
global $CFG;

LoadLib('./user');
LoadLib('h.user');
?>
<form method='POST' x-target='inner'>
  <table cellspacing="0">
    <tr>
      <td align="right">
        <? LoadLib('h.dates') ?>
      </td>
      <td>
        <? LoadLib('h.report') ?>
      </td>
      <td>
        <? LoadLib('h.format') ?>
      </td>
      <td>
        <button type='submit' disabled>
          Сформировать<br />
          отчёт!
        </button>
      </td>
    </tr>
  </table>

  <fieldset>
    <legend><small>Подразделения (<span></span>)</small></legend>
    <?
    LoadLib('./depts');
    renderDepts(loadDepts());
    ?>
  </fieldset>
</form>

<small>
  <sup>*</sup>
  Современные версии Microsoft Excel предупреждают,
  что полученный XLS-файл возможно повреждён,
  тем не менее, открывают его нормально.
</small>
