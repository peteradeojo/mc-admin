<?php

use Auth\Auth;
use Patient\Patient;

require '../init.php';

$patientid = @$_GET['patient'];
$action = @$_GET['action'];

Auth::redirectOnFalse(($patientid and $action), '/doc/');

$patient = new Patient($patientid);
$patient->loadVitals();

$title = ucfirst($action);
require '../header.php';

switch ($action) {
	case 'review':
		require 'review.php';
		exit();
}
?>
<div class="container">
	<h1>Attending To: <?= $patient->getInfo()['name'] ?></h1>
</div>
<div class="container">
	<div class="row row-md-reverse">
		<!-- Documentation form -->
		<div class="col-md-9 ">
			<h2>Documentation</h2>
			<form action="/doc/document.php" method="post" class="row ">
				<div class="form-group col-12">
					<label for="hospital_number">Card Number</label>
					<input type="text" name="hospital_number" readonly="readonly" class="form-control col-md-4" value="<?= $patient->getInfo()['name'] ?>">
				</div>
				<p class="col-12">(Keep all values comma separated)</p>
				<div class="form-group col-md-6">
					<label for="complaint">Complaints</label>
					<textarea name="complaint" id="complaint" class="form-control"></textarea>
				</div>
				<div class="form-group col-md-6">
					<label for="diagnosis">Diagnosis</label>
					<textarea name="diagnosis" id="diagnosis" class="form-control"></textarea>
				</div>
				<div class="form-group col-md-6">
					<label for="investigations">Investigations</label>
					<textarea name="investigations" id="investigations" cols="" rows="" class="form-control"></textarea>
				</div>
				<div class="form-group col-md-6">
					<label for="prescription">Prescription</label>
					<textarea name="prescription" id="prescription" cols="" rows="" class="form-control"></textarea>
				</div>
				<div class="form-group col-md-6">
					<label for="treatments">Treatments</label>
					<textarea name="treatments" id="treatments" cols="" rows="" class="form-control"></textarea>
				</div>
				<div class="form-group col-md-6">
					<label for="notes">Notes</label>
					<textarea name="notes" id="notes" cols="" rows="" class="form-control"></textarea>
				</div>
				<div class="form-group col-md-3">
					<label for="review">
						<input type="checkbox" name="review" id="review"> Set for Review
					</label>
				</div>
				<div class="form-group col-md-3">
					<label for="admit">
						<input type="checkbox" name="admit" id="admit"> Admit
					</label>
				</div>
			</form>
		</div>

		<!-- Vitals -->
		<div class="col-md-3">
			<h2>Vital Signs</h2>
			<div class="row">
				<div class="py-1 col-6 col-md-12">
					<b>Temperature: </b><?= $patient->getVitals()['temp'] ?> &deg;C
				</div>
				<div class="py-1 col-6 col-md-12">
					<b>Pulse: </b><?= $patient->getVitals()['pulse'] ?> bpm
				</div>
				<div class="py-1 col-6 col-md-12">
					<b>Respiration: </b><?= $patient->getVitals()['resp'] ?> rpm
				</div>
				<div class="py-1 col-6 col-md-12">
					<b>Blood Pressure: </b><?= $patient->getVitals()['bp'] ?>
				</div>
				<div class="py-1 col-6 col-md-12">
					<b>Weight: </b><?= $patient->getVitals()['weight'] ?> kg
				</div>
			</div>
		</div>
	</div>
	<!-- <?= print_r($patient, 1) ?> -->
</div>
<?php
require '../footer.php';
