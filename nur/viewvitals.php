<?php

use Patient\Patient;

$patient = new Patient($patientID);

try {
	$patient->loadVitals();
	$vitals = $patient->getVitals();
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
		<div class="col-sm-6 col-md-4">
			<div class="action-card p-1">
				<i class="fa fa-2x fa-thermometer"></i>
				<span class="label">Temperature</span>
				<span class="count"><?= $vitals['temp'] ?></span>
			</div>
		</div>
		<div class="col-sm-6 col-md-4">
			<div class="action-card p-1">
				<i class="fa fa-2x fa-syringe"></i>
				<span class="label">B/P</span>
				<span class="count"><?= $vitals['bp'] ?></span>
			</div>
		</div>
		<div class="col-sm-6 col-md-4">
			<div class="action-card p-1">
				<i class="fa fa-2x fa-heartbeat"></i>
				<span class="label">Pulse</span>
				<span class="count"><?= $vitals['pulse'] ?></span>
			</div>
		</div>
		<div class="col-sm-6 col-md-4">
			<div class="action-card p-1">
				<i class="fa fa-2x fa-lungs"></i>
				<span class="label">Respiration</span>
				<span class="count"><?= $vitals['resp'] ?></span>
			</div>
		</div>
		<div class="col-sm-6 col-md-4">
			<div class="action-card p-1">
				<i class="fa fa-weight fa-2x"></i>
				<span class="label">Weight</span>
				<span class="count"><?= $vitals['weight'] ?></span>
			</div>
		</div>
	</div>
</div>
<?php
require '../footer.php';
