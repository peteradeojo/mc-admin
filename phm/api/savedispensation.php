<?php

use Auth\Auth;

require '../../init.php';


Auth::redirectOnFalse($_POST['id'], '/logout.php');

$id = $_POST['id'];
$visit = $db->select('visits', where: "id=$id", rows: "id, prescriptions, hospital_number")[0];

$data = $_POST['prescription'];

$prescription = json_decode($visit['prescriptions'], true);
$keys = array_keys($prescription);

for ($i = 0; $i < count($data); $i += 1) {
  $prescription[$keys[$data[$i]]]['status'] = 1;
}

try {
  $db->update(
    [
      'visits' => [
        'prescriptions' => json_encode($prescription)
      ],
    ],
    where: "id='$id'",
  );
  $db->update(
    [
      'waitlist' => [
        'status' => 4
      ],
    ],
    where: "hospital_number='$visit[hospital_number]'",
  );
  echo json_encode(['ok' => true]);
} catch (Exception $e) {
  echo json_encode(['error' => true, 'message' => $e->getMessage()]);
}
