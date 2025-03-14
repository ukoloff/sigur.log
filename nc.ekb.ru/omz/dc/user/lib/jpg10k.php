<? // Shrink JPG to 10k to fit into .jpegPhoto

function jpgShrink10k($jpg, $limit = 10000)
{
  if (strlen($jpg) <= $limit)
    return $jpg;

  // Convert to image
  $i = imagecreatefromstring($jpg);

  $Sz[0] = imagesx($i);
  $Sz[1] = imagesy($i);
  $idx = (int) ($Sz[1] > $Sz[0]);  // 0 or 1

  $a = 1;
  $z = $Sz[$idx];
  while ($a + 1 < $z):
    $w = (int)(($a + $z) / 2);
    $Nz[$idx] = $w;
    $Nz[1 - $idx] = (int) ($Sz[1 - $idx] / $Sz[$idx] * $w);
    $j = imagecreatetruecolor($Nz[0], $Nz[1]);
    imagecopyresampled(
      $j,
      $i,
      0,
      0,
      0,
      0,
      $Nz[0],
      $Nz[1],
      $Sz[0],
      $Sz[1]
    );

    $tmp = tempnam('/var/tmp', 'jpg10k');
    imagejpeg($j, $tmp);
    imagedestroy($j);
    $N = filesize($tmp);
    unlink($tmp);
    if ($N > $limit)
      $z = $w;
    else
      $a = $w;
  endwhile;

  $Nz[$idx] = $w;
  $Nz[1 - $idx] = (int) ($Sz[1 - $idx] / $Sz[$idx] * $w);
  $j = imagecreatetruecolor($Nz[0], $Nz[1]);
  imagecopyresampled(
    $j,
    $i,
    0,
    0,
    0,
    0,
    $Nz[0],
    $Nz[1],
    $Sz[0],
    $Sz[1]
  );
  imagedestroy($i);
  $tmp = tempnam('/var/tmp', 'jpg10k');
  imagejpeg($j, $tmp);
  imagedestroy($j);
  $res = file_get_contents($tmp);
  unlink($tmp);
  return $res;
}
