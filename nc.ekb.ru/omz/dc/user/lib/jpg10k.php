<? // Shrink JPG to 10k to fit into .jpegPhoto

function jpgShrink10k($jpg, $limit = 10000)
{
  if (strlen($jpg) <= $limit)
    return $jpg;
}
