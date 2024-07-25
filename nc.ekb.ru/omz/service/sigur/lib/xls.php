<?
function renderXLS($stmt)
{
  $rows = 0;
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
      <?
      while ($row = $stmt->fetchObject()):
        if ($rows == 0):
          echo "<tr><th>№</th>\n";
          foreach ($row as $k => $v):
            echo "<th>", htmlspecialchars($k), "</th>\n";
          endforeach;
          echo "</tr>";
        endif;
        $rows++;
        echo "<tr><td>$rows</td>\n";
        foreach ($row as $k => $v):
          echo "<td>", htmlspecialchars($v), "</td>\n";
        endforeach;
        echo "</tr>";
      endwhile;
      ?>
    </table>
  </body>

  </html>
  <?
}
