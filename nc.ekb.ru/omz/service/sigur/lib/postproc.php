<?
spl_autoload_register(function ($class) {
  include __DIR__ . '/class/' . strtolower($class) . '.php';
});

$z = new dbDate($s);

echo "<pre>";
for ($i = 0; $i < 5; $i++):
  $row = $z->fetchObject();
  print_r($row);
endfor;
