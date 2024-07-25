<?
require_once(dirname(__FILE__).'/spyc.php');

function obj2array($data)
{
 if(!is_array($data) and !is_object($data))return $data;
 $result = array();
 foreach ($data as $key => $value)
  $result[$key] = obj2array($value);
 return $result;
}
?>
