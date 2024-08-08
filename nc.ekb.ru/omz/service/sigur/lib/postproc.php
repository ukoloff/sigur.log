<?
spl_autoload_register(function ($class) {
  include __DIR__ . '/class/' . strtolower($class) . '.php';
});

$z = new dbDate($s);
$z = new Tabel($z);

$CFG->sigur->data = $z;

header("Content-disposition: attachment; filename=\"sigur-$t.$format\"");
LoadLib($format);

