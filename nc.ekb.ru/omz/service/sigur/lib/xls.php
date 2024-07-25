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
</head>

<body>
  <table>
    <?
    $rows = 0;
    while ($row = $CFG->sigur->data->fetchObject()):
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
