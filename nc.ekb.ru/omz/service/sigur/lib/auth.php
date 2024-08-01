Требуется авторизация!

<table width="100%">
  <tr>
    <td width="50%" align="center">
      <form action="https://ad.ekb.ru/auth/krb/">
        <input type="hidden" name="auth" />
        <button type="submit">
          Авторизация в<br>
          Kerberos!
        </button>
      </form>
    </td>
    <td width="50%" align="center">
      <form action="./">
        <input type="hidden" name="auth" value="AD" />
        <button type="submit">
          Авторизация в<br>
          AD!
        </button>
      </form>
    </td>
  </tr>
</table>

<script>
  document.forms[0].auth.value = location
</script>
