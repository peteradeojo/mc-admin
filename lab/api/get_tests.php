<?php

use Database\Database;
use Logger\Logger;

require '../../init.php';

$database = new Database();
$database->connect();

$maxDate = date('Y-m-d', strtotime('-40 days'));

$tests = $database->join(['lab_tests as t', 'visits as vis', 'biodata as bio'], [
  ['type' => 'inner', 'on' => 't.id = vis.lab_tests_id'],
  ['type' => 'inner', 'on' => 'vis.hospital_number = bio.hospital_number']
], where: "t.status=1 and t.date > '$maxDate'");

// Truncate tests and results fields to 30 characters in tests array
$tests = array_map(function ($test) {
  $test['lab_tests'] = strlen($test['lab_tests']) > 30 ? substr($test['lab_tests'], 0, 30)  . '...' : $test['lab_tests'];
  $test['results'] = strlen($test['results']) > 30 ? substr($test['results'], 0, 30)  . '...' : $test['results'];
  $test['gender'] = $test['gender'] == 1 ? 'Male' : 'Female';
  return $test;
}, $tests);

echo json_encode($tests);
