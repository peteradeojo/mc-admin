<?php

require '../../init.php';

// echo json_encode("hey");
$id = @$_GET['id'];
if (!$id) {
  echo json_encode(['error' => true, 'message' => 'No visit ID provided. ']);
  exit();
}

$prescription = $db->join(['visits as vis', 'biodata as bio'], [
  ['type' => 'inner', 'on' => 'vis.hospital_number = bio.hospital_number'],
], where: "vis.id = '$id' and prescriptions LIKE '%\"status\": 0%'", table_rows: "prescriptions, name");

echo json_encode($prescription[0]);
