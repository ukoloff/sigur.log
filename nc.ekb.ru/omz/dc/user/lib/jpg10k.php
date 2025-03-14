<? // Shrink JPG to 10k to fit into .jpegPhoto

function jpgShrink10k($jpg, $limit = 10000)
{
  if (strlen($jpg) <= $limit)
    return $jpg;

  // Convert to image
  $i = imagecreatefromstring($jpg);

  $Sz[0] = imagesx($i);
  $Sz[1] = imagesy($i);
  $idx = (int)($Sz[1] > $Sz[0]);  // 0 or 1

  print_r($Sz);
}
