<?php

use Auth\Auth;
use Lab\Test;
use Patient\Patient;

require '../init.php';

$visit_id = $_GET['id'];

Auth::redirectOnFalse($visit_id, '/doc/reviews.php');

$attrs = ['name', 'hospital_number', 'birthdate', 'gender'];
$attrs = join(',', array_map(function ($attr) {
  return "'$attr', $attr";
}, $attrs));
$lt_attrs = ['id', 'date', 'results', 'status', 'submitted_by'];
$lt_attrs = join(',', array_map(function ($attr) {
  return "'$attr', $attr";
}, $lt_attrs));


/**
 * Don't be fooled, this is unmaintainable
 * Don't change it, it works
 * IF you must change it, do not modify it,
 * comment it out and then re-implement
 * 
 * YOU CAN ONLY EDIT THE ATTRIBUTES ARRAYS ABOVE
 * 
 */
$sql = "SELECT *, (select JSON_OBJECT($attrs) from biodata as bio where hospital_number = vis.hospital_number) bio, (select JSON_OBJECT($lt_attrs) from lab_tests where id=vis.lab_tests_id) lt FROM visits as vis WHERE id = '$visit_id'";

$visit = $db->getClient()->query($sql)->fetch_all(MYSQLI_ASSOC)[0];

if (!$visit) {
  Auth::redirectOnFalse($visit, '/doc/reviews.php');
}

$visit['complaints'] = "Hello,world";

$visit['bio'] = json_decode($visit['bio'], true);
$visit['lt'] = json_decode($visit['lt'], true);

$visit['lt']['results'] = Test::parseTests($visit['lab_tests'], $visit['lt']['results']);
$visit['lt']['lab_scientist'] = $db->select('staff', where: "username='{$visit['lt']['submitted_by']}'", rows: "CONCAT(firstname, ' ',lastname) as name")[0]['name'] ?? 'Unknown';

$patient = new Patient($visit['bio']['hospital_number']);


$title = "Review for {$visit['bio']['name']}";

require '../header.php';
?>
<div class="container">
  <h1><?= $visit['bio']['name'] ?></h1>

  <div class="row">
    <div class="col-md-9">
      <form action="/doc/process_review.php" method="post">
        <div class="row">
          <?php require './forms/visit-form.php' ?>
        </div>
      </form>
    </div>
    <div class="col-md-3 mt-3">
      <h2>Vital Signs</h2>
      <div class="row">
        <div class="py-1">
          <b>Temperature: </b><?= $patient->getVitals()['temp'] ?> &deg;C
        </div>
        <div class="py-1 col-">
          <b>Pulse: </b><?= $patient->getVitals()['pulse'] ?> bpm
        </div>
        <div class="py-1 col-">
          <b>Respiration: </b><?= $patient->getVitals()['resp'] ?> rpm
        </div>
        <div class="py-1 col-">
          <b>Blood Pressure: </b><?= $patient->getVitals()['bp'] ?>
        </div>
        <div class="py-1 col-">
          <b>Weight: </b><?= $patient->getVitals()['weight'] ?> kg
        </div>
        <div class="py-1 col-">
          <b>Taken By: </b><?= "{$patient->getVitals()['taken_by']['firstname']} {$patient->getVitals()['taken_by']['lastname']}" ?>
        </div>
      </div>

      <h2 class="mt-3">Tests Results</h2>
      <?php foreach ($visit['lt']['results'] as $test) : ?>
        <div class="py-1 col-">
          <b><?= $test['name'] ?>: </b><?= $test['result'] ?>
        </div>
      <?php endforeach; ?>

      <div class="py-1 col-">
        <b>Submitted: </b><?= $visit['lt']['lab_scientist'] ?>
      </div>

    </div>
  </div>

  <div class="container">
    <?= print_r($visit, 1) ?>
  </div>
  <div class="row">
  </div>
</div>
<?php
$scripts = ['/doc/js/visit.js'];
require '../footer.php';
