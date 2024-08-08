<?
header("Content-Type: application/vnd.ms-excel");
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
  <style>
    tr {
      height: 1.23em;
    }
    br {
      /* https://stackoverflow.com/a/4758535 */
      /* https://www.bennadel.com/blog/1095-maintaining-line-breaks-in-an-html-excel-file.htm */
      mso-data-placement:same-cell;
    }
  </style>
</head>

<body>
  <table>
    <?
    $rows = 0;
    while ($row = $CFG->sigur->data->fetchObject()):
      if ($rows == 0):
        echo "<tr>\n";
        foreach ($row as $k => $v):
          echo "<th>", htmlspecialchars($k), "</th>\n";
        endforeach;
        echo "</tr>";
      endif;
      $rows++;
      echo "<tr>\n";
      foreach ($row as $k => $v):
        echo "<td>", nl2br(htmlspecialchars($v)), "</td>\n";
      endforeach;
      echo "</tr>";
    endwhile;
    ?>
  </table>
</body>

</html>
