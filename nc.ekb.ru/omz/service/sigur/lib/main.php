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
          ������������<br />
          �����!
        </button>
      </td>
    </tr>
  </table>

  <fieldset>
    <legend><small>������������� (<span></span>)</small></legend>
    <?
    LoadLib('./depts');
    renderDepts(loadDepts());
    ?>
  </fieldset>
</form>

<small>
  <sup>*</sup>
  ����������� ������ Microsoft Excel �������������,
  ��� ���������� XLS-���� �������� ��������,
  ��� �� �����, ��������� ��� ���������.
</small>
