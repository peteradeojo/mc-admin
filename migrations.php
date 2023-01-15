<?php

// $db = new Database();
// $db->connect();

$dbMigrations = $db->select('migrations');

$migrations = [];

foreach (glob('./migrations/*.php') as $file) {
  $migrations[] = $file;
}

echo '<pre>';
var_dump($dbMigrations);
var_dump($migrations);
echo '</pre>';
die();
