<?php

use Lab\Laboratory;

require '../../init.php';

try {
  $lab = new Laboratory();
  $lab->connect();

  $data = $lab->load_pending_tests();
  echo json_encode($data);
} catch (Exception $e) {
  echo json_encode(['error' => true]);
}
