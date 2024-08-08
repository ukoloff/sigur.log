<?
spl_autoload_register(function ($class) {
  include __DIR__ . '/class/' . strtolower($class) . '.php';
});

$z = new dbDate($s);
print_r($z);
