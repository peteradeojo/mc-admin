<?php

use Lab\Test;
use Auth\Auth;
use Patient\Patient;

require '../init.php';

$patientid = @$_GET['patient'];
$action = @$_GET['action'];

Auth::redirectOnFalse(($patientid and $action), '/doc/');

$patient = new Patient($patientid);
$patient->loadVitals();

$title = ucfirst($action);
$patientVisits = $db->join(
	['visits as vis', 'biodata as bio', 'lab_tests as tests'],
	[
		[
			'type' => 'left',
			'on' => 'vis.hospital_number = bio.hospital_number'
		],
		[
			'type' => 'left',
			'on' => 'tests.id = vis.lab_tests_id'
		]
	],
	table_rows: "bio.name, bio.hospital_number, vis.*, tests.*",
	orderby: "vis.id desc",
	where: "bio.hospital_number = '$patientid'"
);

$visits = array_map(function ($visit) {
	if ($visit['lab_tests']) {
		$visit['test_results'] = Test::parseTests($visit['lab_tests'], $visit['results']);
	} else {
		$visit['test_results'] = [];
	}

	$visit['prescriptions'] = json_decode($visit['prescriptions'], true);

	return $visit;
}, $patientVisits);


$stylesheets = ['/assets/css/visits.css'];
require '../header.php';

switch ($action) {
	case 'review':
		require 'review.php';
		exit();
	case 'view':
		require 'view-visit.php';
		exit();
}
?>

<div class="modal" id="previous-visits-modal">
	<div class="modal-content p-2">
		<span class="close btn" data-dismiss='#previous-visits-modal'>&times;</span>
		<div class="modal-header pb-1">
			<h2><?= $patient->getInfo()['name']  . ": " . (count($visits)) . " previous visits" ?></h2>
		</div>
		<div class="modal-body py-1" id="previous-visits">
			<?php foreach ($visits as $visit) : ?>
				<div class="container">
					<table id="previous-visits-table">
						<tbody>
							<tr>
								<td>Complaints</td>
								<td><?= $visit['complaints'] ?? "No complaints registered." ?></td>
							</tr>
							<tr>
								<td>Diagnoses</td>
								<td><?= $visit['assessments'] ?? "Not present." ?></td>
							</tr>
							<tr>
								<td>Lab Tests</td>
								<td>
									<ul>
										<?php
										if (count($visit['test_results'])) {
											foreach ($visit['test_results'] as $test) {
												echo <<<_
													<li>{$test['name']} - $test[result]</li>
												_;
											}
										} else {
											echo "<li>No tests taken</li>";
										}
										?>
									</ul>
								</td>
							</tr>
							<tr>
								<td>Prescriptions</td>
								<td>
									<ul>
										<?php
										if (count($visit['prescriptions'])) {
											foreach ($visit['prescriptions'] as $prescription => $data) {
												echo <<<_
														<li>$prescription - $data[quantity] $data[mode] ($data[duration])</li>
													_;
											}
										} else {
											echo "No prescriptions";
										}
										?>
									</ul>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<div class=" container">
	<div class="d-flex justify-content-space-between">
		<h1>Attending To: <?= $patient->getInfo()['name'] ?></h1>
		<button class="btn btn-primary modal-open" data-target="#previous-visits-modal">View Previous Visits</button>
	</div>
</div>
<div class="container">
	<button id="patient-biodata-btn" class="btn mb-1">Biodata</button>
	<button id="allergies-btn" class="btn">Allergies and Drug Reactions</button>
	<button id="pmh-btn" class="btn">Past Medical History</button>
	<button id="psh-btn" class="btn">Past Surgical History</button>

	<?php if ($patient->getInfo()['gender'] == 'F') : ?>
		<button id="patient-gyne-btn" class="btn">Gynecological History</button>
		<button id="delivery-history-btn" class="btn">Delivery History</button>
	<?php endif; ?>

	<button id="fsoc-btn" class="btn">Family History</button>
</div>
<div class="relative">

	<!-- Biodata -->
	<div class="floating-tab container" data-hook="#patient-biodata-btn" id="patient-biodata-tab">
		<div class="d-flex justify-content-space-between">
			<h2>Biodata</h2>
			<span class="close btn btn-small" data-dismiss="#patient-biodata-tab">&times;</span>
		</div>
		<?php require './src/biodata.php' ?>
	</div>

	<!-- Allergies & Drug Reactions -->
	<div class="floating-tab container" data-hook="#allergies-btn" id="allergies-tab">
		<div class="d-flex justify-content-space-between">
			<h2>Allergies & Drug Reactions</h2>
			<span class="close btn btn-small" data-dismiss="#allergies-tab">&times;</span>
		</div>
		<?php require './src/allergies.php' ?>
	</div>

	<!-- Past Medical History -->
	<div class="floating-tab container" id="past-medical-history" data-hook="#pmh-btn">
		<div class="d-flex justify-content-space-between">
			<h2>Past Medical History</h2>
			<span class="close btn btn-small" data-dismiss="#past-medical-history">&times;</span>
		</div>

		<table class="table-for-info">
			<thead>
				<tr>
					<th>Previous Admissions</th>
					<th>Reason for Admission</th>
					<th>Year</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>

	<!-- Passt Surgical History -->
	<div class="floating-tab container" id="psh-tab" data-hook="#psh-btn">
		<div class="d-flex justify-content-space-between">
			<h2>Past Surgical History</h2>
			<span class="close btn btn-small" data-dismiss="#psh-tab">&times;</span>
		</div>

		<table class="table-for-info">
			<thead>
				<tr>
					<th>Type of Surgery</th>
					<th>Year of Surgery</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>

	<?php if ($patient->getInfo()['gender'] == 'F') : ?>
		<!-- Gynecological History -->
		<div class="floating-tab container" data-hook="#patient-gyne-btn" id="patient-gyne-tab">
			<div class="d-flex justify-content-space-between">
				<h2>Gynecological History</h2>
				<span class="close btn btn-small" data-dismiss="#patient-gyne-tab">&times;</span>
			</div>

			<table class="table-for-info">
				<tr>
					<td>Age of 1st Menses</td>
					<td></td>
				</tr>
				<tr>
					<td>Length of Menstrual Cycle in days</td>
					<td></td>
				</tr>
				<tr>
					<td>Duration of Menstruation</td>
					<td></td>
				</tr>
				<tr>
					<td>Age at first coitus:</td>
					<td></td>
				</tr>
				<tr>
					<td>Date of first coitus:</td>
					<td></td>
				</tr>
				<tr>
					<td>Number of Sexual Partners</td>
					<td></td>
				</tr>
				<tr>
					<td>Type of Contraception currently on</td>
					<td></td>
				</tr>
				<tr>
					<td>Date of last PAP Smear</td>
					<td></td>
				</tr>
			</table>
		</div>

		<!-- Pregnancy & Delivery History -->
		<div class="floating-tab container" data-hook="#delivery-history-btn" id="delivery-history-tab">
			<div class="d-flex justify-content-space-between">
				<h2>Pregnancy & Delivery History</h2>
				<span class="close btn btn-small" data-dismiss="#delivery-history-tab">&times;</span>
			</div>

			<table class="table-for-info">
				<thead>
					<tr>
						<th>Duration</th>
						<th>Type of Delivery</th>
						<th>Sex of baby</th>
						<th>Alive or Dead</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</div>
	<?php endif; ?>

	<!-- Family & Social History -->
	<div class="floating-tab container" id="fam-soc-history" data-hook="#fsoc-btn">

		<div class="d-flex justify-content-space-between">
			<h2>Family & Social History</h2>
			<span class="close btn btn-small" data-dismiss="#fam-soc-history">&times;</span>
		</div>

		<table class="table-for-info">
			<tbody>
				<tr>
					<td>Type of Marriage</td>
					<td></td>
				</tr>
				<tr>
					<td>Occupation of the Patient</td>
					<td></td>
				</tr>
				<tr>
					<td>Occupation of the Spouse</td>
					<td></td>
				</tr>
				<tr>
					<td>Smoking Cigarettes</td>
					<td></td>
				</tr>
				<tr>
					<td>Drinking Alcholic Beverages</td>
					<td></td>
				</tr>
				<tr>
					<td>Other substance of abuse</td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<div class="container">
	<div class="row row-md-reverse">
		<!-- Documentation form -->
		<div class="col-md-9 ">
			<h2>Documentation</h2>
			<form action="/doc/process_visit.php" method="post" class="row bold-labels">
				<div class="form-group col-12">
					<label for="hospital_number">Card Number</label>
					<input type="text" name="hospital_number" readonly="readonly" class="form-control col-md-4" value="<?= $patient->getInfo()['hospital_number'] ?>">
				</div>

				<!-- Complaints -->
				<div class="form-group col-md-6">
					<label for="complaint">Complaints</label>
					<div id="complaints-container" class="py-1"></div>
					<textarea name="complaints_history" id="complaints-history" class="form-control" placeholder="Complaints history"></textarea>
					<button type="button" class="btn" id="add-complaint" data-action="addInput" data-target="#complaints-container" data-inputname="complaints[]" data-inputtype="text" data-datalist="complaints-list" data-adjoint="complaints_duration[],text,Duration">Add Complaint</button>
					<datalist id="complaints-list">
						<!-- <option value="Fever"></option> -->
						<?php
						$options = $db->select('available_complaints');
						$options = @$options[0] ? $options : [$options];
						foreach ($options as $option) {
							# code...
							echo "<option value='$option[complaint]'>";
						}
						?>
					</datalist>
				</div>

				<div class="col-md-6"></div>

				<!-- Diagnoses -->
				<div class="form-group col-md-7">
					<label for="diagnoses">Initial Diagnoses</label>
					<div id="diagnoses" class="py-1"></div>
					<button type="button" class="btn" data-action="addInput" data-datalist="diagnoses-list" data-target="#diagnoses" data-inputname="diagnosis[]">Add Initial Diagnosis</button>
					<datalist id="diagnoses-list">
						<?php
						$options = $db->select('available_diagnoses');
						$options = @$options[0] ? $options : [$options];
						foreach ($options as $option) {
							# code...
							echo "<option value='$option[diagnosis]'>";
						}
						?>
					</datalist>
				</div>

				<!-- Assessments -->
				<!-- <div class="form-group col-md-6">
					<label for="diagnosis" class="d-flex justify-content-space-between">
						<span>Assessments</span>
					</label>
					<div id="assessments" class="py-1"></div>
					<button type="button" data-action="addInput" data-target="#assessments" data-inputname="assessments[]" data-datalist="assessments-list" class="btn">Add Assessment</button>
					<datalist id="assessments-list">
						<?php
						$options = $db->select('assessments');
						$options = @$options[0] ? $options : [$options];
						foreach ($options as $option) {
							# code...
							echo "<option value='$option[assessment]'>";
						}
						?>
					</datalist>
				</div> -->

				<!-- Investigations -->
				<div class="form-group col-md-6">
					<label for="investigations">Investigations </label>

					<div id="investigations" class="py-1"></div>
					<datalist id="investigations-list">
						<?php
						$options = $db->select('available_investigations');
						$options = @$options[0] ? $options : [$options];
						foreach ($options as $option) {
							# code...
							echo "<option value='$option[investigation]'>";
						}
						?>
					</datalist>

					<button class="btn" type="button" data-action="addInput" data-target="#investigations" data-inputname="investigations[]" data-datalist="investigations-list">Add Investigation</button>

				</div>

				<!-- Lab Tests -->
				<div class="form-group col-md-6">
					<label>Lab Tests</label>
					<div id="lab-tests" class="py-1"></div>
					<button class="btn" type="button" data-inputname="lab_tests[]" data-target="#lab-tests" data-action="addInput" data-datalist="lab-tests-list">Add Test</button>
					<datalist id="lab-tests-list">
						<?php
						$options = $db->select('available_laboratory_tests');
						$options = @$options[0] ? $options : [$options];
						foreach ($options as $option) {
							# code...
							echo "<option value='$option[test]'>";
						}
						?>
					</datalist>
				</div>

				<!-- PRescriptions -->
				<div class="form-group col-12">
					<label for="prescription">Prescription</label>
					<div id="prescriptions" class="py-1"></div>
					<datalist id="prescriptions-list">
						<?php
						$options = $db->select('available_prescriptions');
						$options = @$options[0] ? $options : [$options];
						foreach ($options as $option) {
							# code...
							echo "<option value='$option[prescription]'>";
						}
						?>
					</datalist>
					<datalist id="quantity-list">
						<?php
						$options = $db->select('available_prescription_quantities');
						$options = @$options[0] ? $options : [$options];
						foreach ($options as $option) {
							# code...
							echo "<option value='$option[quantity]'>";
						}
						?>
					</datalist>
					<datalist id="mode-list">
						<?php
						$options = $db->select('available_prescription_modes');
						$options = @$options[0] ? $options : [$options];
						foreach ($options as $option) {
							# code...
							echo "<option value='$option[mode]'>";
						}
						?>
					</datalist>
					<datalist id="duration-list">
						<?php
						$options = $db->select('available_prescription_durations');
						$options = @$options[0] ? $options : [$options];
						foreach ($options as $option) {
							# code...
							echo "<option value='$option[duration]'>";
						}
						?>
					</datalist>
					<button type="button" data-action="addPrescription" data-inputtype="text" data-datalist="prescriptions-list" data-inputname="prescriptions[]" data-target="#prescriptions" class="btn" onclick="addPrescription(this)">Add Prescription</button>
				</div>
				<div class="form-group col-md-6">
					<label for="admit">
						<input type="checkbox" name="admitted" id="admit" class="conditional-input" data-area='#admission-form'> Admit
					</label>
				</div>
				<div class="form-group col-md-6">
					<label for="review">
						<input type="checkbox" name="review" id="review" class="conditional-input" data-area="#review-form"> Set for Review of Tests & Investigations
					</label>
				</div>

				<div id="admission-form" class="col-12 row" style="display: none">
					<h3>Admission Plans</h3>
					<div class="form-group col-md-6" id="admission-fluids-container">
						<label>Fluids</label>
						<div id="admission-fluids" class="py-1"></div>
						<button type="button" data-action="addFluid" data-target="#admission-fluids" data-inputtype="text" data-inputname="admission-instruction[]" data-datalist="fluids-list" class="btn" onclick="addAdmissionInstruction(this)">Add Fluid</button>
						<datalist id="fluids-list">
							<?php
							$options = $db->select('available_prescriptions', where: "type='0'");
							if (count($options)) {
								$options = @$options[0] ? $options : [$options];
								foreach ($options as $option) {
									echo "<option value='$option[prescription]'>$option[prescription]</option>";
								}
							}
							?>
						</datalist>
					</div>
					<div class="form-group col-md-6" id="admission-drugs-container">
						<label>Drugs</label>
						<div id="admission-drugs" class="py-1"></div>
						<button type="button" data-action="addDrug" data-target="#admission-drugs" data-inputtype="text" data-inputname="admission-drugs[]" data-datalist="prescriptions-list" class="btn" onclick="addAdmissionInstruction(this)">Add Drug</button>
					</div>
				</div>
				<div id="review-form" style="display:none" class="col-12">
					<div class="form-group col-md-6">
						<label for="reviewdate">Review Date</label>
						<input type="date" name="review_date" id="reviewdate" class="form-control">
					</div>
					<div class="form-group col-md-6">
						<label for="review-notes">Review Notes</label>
						<textarea name="review_notes" id="review-notes" class="form-control"></textarea>
					</div>
				</div>
				<div class="form-group col-12">
					<label for="notes">Notes / Summary</label>
					<textarea name="notes" id="notes" cols="" rows="" class="form-control"></textarea>
				</div>
				<div class="form-group">
					<button type="submit" class="btn">Submit</button>
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
				<div class="py-1 col-6 col-md-12">
					<b>Taken By: </b><?= "{$patient->getVitals()['taken_by']['firstname']} {$patient->getVitals()['taken_by']['lastname']}" ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$scripts = ['/doc/js/visit.js'];
require '../footer.php';
