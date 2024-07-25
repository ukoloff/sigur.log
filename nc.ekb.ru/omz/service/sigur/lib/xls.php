<?
function renderXLS()
{
  $t = new DateTime();
  $t = $t->format('Y-m-d-H-i-s');
  header("Content-Type: application/vnd.ms-excel");
  header("Content-disposition: attachment; filename=\"sigur-$t.xls\"");
  ?>
  <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel"
    xmlns="http://www.w3.org/TR/REC-html40">

  <head>
    <!--[if gte mso 9]><xml>
<x:ExcelWorkbook>
<x:ExcelWorksheets>
<x:ExcelWorksheet>
<x:Name>Отчёт Sigur</x:Name>
<x:WorksheetOptions>
<x:NoSummaryRowsBelowDetail/>
<x:NoSummaryColumnsRightDetail/>
</x:WorksheetOptions>
</x:ExcelWorksheet>
</x:ExcelWorksheets>
</x:ExcelWorkbook>
</xml><![endif]-->
    <meta http-equiv="Content-Type" content='text/html; charset=windows-1251' />
  </head>

  <body>
    <table>
      <tr>
        <th>Параметр</th>
        <th>Значение</th>
        <?
        foreach ($_POST as $k => $v):
          echo "<tr><td>", htmlspecialchars($k), "</td><td>", htmlspecialchars($v), "</td></tr>\n";
        endforeach;
        ?>
    </table>
  </body>

  </html>
  <?
}
