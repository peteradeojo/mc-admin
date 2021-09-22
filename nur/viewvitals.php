<?php

use Patient\Patient;

$patient = new Patient($patientID);

try {
	$patient->loadVitals();
	$vitals = $patient->getInfo();
	// print_r($vitals);
} catch (Exception | Error $e) {
	echo $e->getMessage();
}

require '../header.php';
?>
<div class="container">
	<h1>Vitals</h1>
</div>
<div class="container py-1">
	<h3><?= $patient->getInfo()['name'] ?></h3>
	<div class="row">
		<div class="col-sm-6 col-md-3">
			<div class="action-card">
				<i class="fa fa-2x fa-thermometer"></i>
				<span class="label">Temperature</span>
				<span class="count"><?= $patient->getInfo()['vitals']['temp'] ?>&deg;C</span>
			</div>
		</div>
		<div class="col-sm-6 col-md-3">
			<div class="action-card">
				<i class="fa fa-2x fa-syringe"></i>
				<span class="label">B/P</span>
				<span class="count"><?= $patient->getInfo()['vitals']['bp'] ?></span>
			</div>
		</div>
		<div class="col-sm-6 col-md-3">
			<div class="action-card">
				<i class="fa fa-2x fa-heartbeat"></i>
				<span class="label">Pulse</span>
				<span class="count"><?= $patient->getInfo()['vitals']['pulse'] ?></span>
			</div>
		</div>
		<div class="col-sm-6 col-md-3">
			<div class="action-card">
				<i class="fa fa-2x fa-lungs"></i>
				<span class="label">Respiration</span>
				<span class="count"><?= $patient->getInfo()['vitals']['resp'] ?></span>
			</div>
		</div>
		<div class="col-sm-6 col-md-3">
			<div class="action-card">
				<i class="fa fa-weight fa-2x"></i>
				<span class="label">Weight</span>
				<span class="count"><?= $patient->getInfo()['vitals']['weight'] ?></span>
			</div>
		</div>
	</div>
</div>
<?php
require '../footer.php';
