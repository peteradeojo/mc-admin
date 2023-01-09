<?php

use Patient\Patient;
use Database\Builder;
use Database\Migration;
use Patient\NewPatient;
use Patient\AntenatalPatient;

require '../../init.php';

$data = array_map(function ($item) {
  return sanitizeInput($item);
}, $_POST);

unset($_POST);

$hospital_number = Patient::calculateHospitalNumber($data['category']);

$data['name'] = ucfirst($data['name']);
$data['phone_number'] ??= $data['phone'];
$data['email_address'] ??= $data['email'];
$data['gender'] = strtolower($data['gender']) == 'male' ? 1 : 0;
$data['tribe'] = substr($data['tribe'], 0, 9); // == 'male' ? 1 : 0;
if (strtolower($data['category']) === 'anc') {
  $patient = new AntenatalPatient($data);

  try {
    $patient->save();
    $patient->startAntenatalSession();
    newFlash('success', 'Patient registered successfully');
  } catch (Throwable $th) {
    newFlash('error', $th->getMessage());
  }
} else {
  $patient = new NewPatient($data);

  try {
    $patient->save();
    newFlash('success', 'Patient registered successfully');
  } catch (Exception $e) {
    newFlash('error', $e->getMessage());
  }
}

header("Location: /rec/patients.php");
