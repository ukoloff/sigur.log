<?
header('Content-disposition: attachment; filename="a.xlsx"');
?>
Hello, world!
<pre>
<?
print_r($_REQUEST);
exit();
?>
