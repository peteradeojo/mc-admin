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

(function () {
  Migration::createTableIfNotExists('antenatal_sessions', function (Builder $table) {
    $table->id();
    $table->integer('patient_id');
    $table->foreign('patient_id', 'biodata');

    $table->date('flmp', false);
    $table->date('edd', false);

    $table->string('delivery_status', 30, false);
    $table->timestamps();
  });

  Migration::createTableIfNotExists('anc_session_personal_history', function (Builder $table) {
    $table->id();
    $table->integer('anc_session_id');
    $table->foreign('anc_session_id', 'antenatal_sessions');

    $table->boolean('chest_disease', false)->default(false);
    $table->boolean('kidney_disease', false)->default(false);
    $table->boolean('blood_transfusion', false)->default(false);
    $table->boolean('operation_no_cs', false)->default(false);
    $table->string('others', 255, false)->default('');
    $table->timestamps();
  });

  Migration::createTableIfNotExists('anc_session_family_history', function (Builder $table) {
    $table->id();
    $table->integer('anc_session_id');
    $table->foreign('anc_session_id', 'antenatal_sessions');

    $table->boolean('multiple_births', false)->default(false);
    $table->string('multiple_births_relation', 30, false);
    $table->boolean('hypertension', false)->default(false);
    $table->string('hypertension_relation', 30, false);
    $table->boolean('tuberculosis', false)->default(false);
    $table->string('tuberculosis_relation', 30, false);
    $table->boolean('heart_diseases', false)->default(false);
    $table->string('heart_disease_relation', 30, false);
    $table->string('others', 100, false);
    $table->string('others_relations', 100, false);
    $table->timestamps();
  });

  Migration::createTableIfNotExists('anc_session_objective_history', function (Builder $table) {
    $table->id();
    $table->integer('anc_session_id');
    $table->foreign('anc_session_id', 'antenatal_sessions');

    $table->integer('gravidity');
    $table->integer('parity');
    $table->timestamps();
  });

  Migration::createTableIfNotExists('anc_session_pregnancy_history', function (Builder $table) {
    $table->id();
    $table->integer('anc_session_id');
    $table->foreign('anc_session_id', 'antenatal_sessions');

    $table->date('date_of_delivery', false);
    $table->string('delivery_type', 30, false);
    $table->string('delivery_place', 30, false);
    $table->string('duration_of_pregnancy', 20, false);
    $table->integer('living_status', false)->default(1);
    $table->string('plp', 20, false);
    $table->boolean('gender', false)->default(true);

    $table->timestamps();
  });

  Migration::createTableIfNotExists('anc_session_current_history', function (Builder $table) {
    $table->id();
    $table->integer('anc_session_id');
    $table->foreign('anc_session_id', 'antenatal_sessions');

    $table->decimal('weight', 5, 2, false);
    $table->decimal('height', 5, 2, false);
    $table->string('bp', 9, false);

    $table->string('vaginal_bleeding', 20, false);
    $table->string('vaginal_discharge', 20, false);
    $table->string('urinary_symptoms', 20, false);
    $table->string('leg_swelling', 20, false);
    $table->string('other_symptoms', 60, false);

    $table->timestamps();
  });
});

(function () {
  Migration::alterTable('antenatal_sessions', function (Builder $table) {
    $table->modify('delivery_status', 'int not null default 0');
  });
});

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
