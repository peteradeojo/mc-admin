<?php

use Database\Builder;
use Database\Migration;

require '../../init.php';

$patient_id = sanitizeInput($_POST['id']);

$session = $db->join(['biodata as bio', 'antenatal_sessions as ancs'], [
  [
    'type' => 'inner',
    'on' => 'bio.id = ancs.patient_id'
  ]
], where: "bio.id = $patient_id", table_rows: "bio.*, ancs.*", orderby: "ancs.created_at desc")[0] ?? null;

if (!$session) {
  response([
    'status' => 'error',
    'message' => 'Patient does not have an antenatal session. Please start an antenatal session for this patient.'
  ], 500);
}

$check = $db->select('antenatal_visits', where: "session_id = {$session['id']} and status < 5")[0] ?? null;

if ($check) {
  return response([
    'status' => 'error',
    'message' => 'Patient already has an active antenatal visit. Please conclude the visit before starting a new one.'
  ], 500);
}

try {
  $db->insert([
    'antenatal_visits' => [
      'session_id' => $session['id'],
      'checked_in_by' => $staff->getUserdata()['id'],
    ]
  ]);

  return response([
    'status' => 'success',
    'message' => 'Antenatal visit started successfully.'
  ], 201);
} catch (Throwable $th) {
  return response([
    'status' => 'error',
    'message' => $th->getMessage()
  ], 500);
}
