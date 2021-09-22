<?php

use Auth\Auth;
use Patient\Patient;

require '../init.php';

$patientID = @$_GET['patient'];
Auth::redirectOnFalse($patientID, '/nur/waitlist.php');
$action = @$_GET['action'];

if ($action == 'view') {
	require 'viewvitals.php';
	exit();
}

try {
	$patient = new Patient($patientID);
	$patient->connect();

	if ($_POST and $action == 'take') {
		$patient->takeVitals([
			'temp' => $_POST['temp'],
			'resp' => $_POST['resp'],
			'pulse' => $_POST['pulse'],
			'bp' => $_POST['bp'],
			'weight' => $_POST['weight'],
			'taken_by' => $staff->getUsername(),
		]);

		try {
			$patient->saveVitals();
			header("Location: /nur/waitlist.php");
		} catch (Exception $ex) {
			echo $ex->getMessage();
			exit();
		}
	}
} catch (Exception $e) {
	echo $e->getMessage();
}

$title = 'Take Vitals';
require '../header.php';
?>
<div class="container">
	<h1>Vitals</h1>
</div>
<div class="container">
	<h3><?= $patient->getInfo()['name'] ?></h3>
	<p class="p-1">Vitals must be taken carefully as it will not be possible to modify after submission</p>
	<form action="" method="post" class="mt-2">
		<div class="row">
			<div class="col-sm-6 col-md-2">
				<div class="form-group">
					<label for="temp">Temperature</label>
					<input type="number" name="temp" id="temp" step="0.1" class="form-control" required>
				</div>
			</div>
			<div class="col-sm-6 col-md-2">
				<div class="form-group">
					<label for="pulse">Pulse</label>
					<input type="number" name="pulse" id="pulse" class="form-control" required>
				</div>
			</div>
			<div class="col-sm-6 col-md-2">
				<div class="form-group">
					<label for="resp">Respiration</label><input type="number" name="resp" id="resp" class="form-control" required>
				</div>
			</div>
			<div class="col-sm-6 col-md-2">
				<div class="form-group">
					<label for="bp">B/P</label>
					<input type="text" name="bp" id="bp" class="form-control">
				</div>
			</div>
			<div class="col-sm-6 col-md-2">
				<div class="form-group">
					<label for="weight">Weight</label>
					<input type="number" name="weight" id="weight" step="0.1" class="form-control" required>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<button type="submit" class="btn">Submit</button>
				</div>
			</div>
		</div>
	</form>
</div>
<?php
require '../footer.php';
