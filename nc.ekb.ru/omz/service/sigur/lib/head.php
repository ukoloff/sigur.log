<?
function renderScript($js)
{
  if (!$_SESSION['u'])
    return;
  $d = getdate(filemtime(dirname(__FILE__) . '/../' . $js));
  $d = $d['seconds'];
  echo "<script src=$js?$d></script>";
}
renderScript('sigur.js');
