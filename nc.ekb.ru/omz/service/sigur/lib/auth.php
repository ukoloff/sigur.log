��������� �����������!

<table width="100%">
  <tr valign="top">
    <td width="50%" align="center">
      <form action="https://ad.ekb.ru/auth/krb/">
        <input type="hidden" name="auth" />
        <button type="submit">
          ����� � �������<br>
          ������� �������
        </button>
        <p>
        <i>���, ��� �� ����� � ���������</i>
      </form>
    </td>
    <td width="50%" align="center">
      <form action="./">
        <input type="hidden" name="auth" value="AD" />
        <button type="submit">
          <? if ($CFG->u): ?>
            ����� ��� <tt><?= htmlspecialchars($CFG->u) ?></tt><br>
            <? $e = getEntry(user2dn($CFG->u)); ?>
            <i><?= utf2str($e['cn'][0]) ?></i>
          <? else: ?>
            ������ ������� ������<br>
            � ������
          <? endif; ?>
        </button>
        <p>
        <i>������� ������ �������� <b>���</b> ������ (<s>OMZGLOBAL\</s>)</i>
      </form>
    </td>
  </tr>
</table>

<script>
  document.forms[0].auth.value = location
</script>
