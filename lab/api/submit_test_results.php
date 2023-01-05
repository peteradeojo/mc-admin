<?php

require '../../init.php';

use Auth\Auth;
use Lab\Test;

Auth::redirectOnFalse($_POST['testid'], '/lab');

$test = new Test($_POST['testid']);
$test->connect();

$completed = $_POST['completed'] === 'on' ?? false;

$data = $test->load_test();

if ($data['status'] == 1) {
  $test->markCompleted();
  http_response_code(403);
  echo json_encode([
    'error' => true,
    'message' => 'This test has already been completed.',
  ]);
  die();
}

$results = array_values($_POST['tests']);

foreach ($results as $key => $value) {
  $results[$key] = sanitizeInput($value);
}

$test->saveResults($results, sanitizeInput($_POST['user']));
if ($completed) {
  $test->markCompleted();
}

newFlash('success', "Test results submitted successfully.");
http_response_code(201);
$result = [
  'message' => 'Test results submitted successfully.',
];

echo json_encode($result);
