<?
function renderXLS()
{
  header("Content-Type: application/vnd.ms-excel");
  header('Content-disposition: attachment; filename="a.xls"');
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
        <td align="center"><b>1</b></td>
        <td>2</td>
        <td>3</td>
      </tr>
      <tr>
        <td>Превед</td>
        <td>Medved</td>
        <td>A3</td>
      </tr>

    </table>
  </body>

  </html>

  <?
}
?>
