<?php

use Patient\DoctorVisit;

try {
	$visit = new DoctorVisit(username: $staff->getUsername());
	$visit->connect();
	$patients_visits = $visit->loadVisit($patientid);
	print_r($patients_visits);
} catch (Exception $e) {
	echo $e->getMessage();
}
?>
<div class="container">
	<h1>Viewing Documentations for: <?= $patient->getInfo()['name'] ?></h1>
</div>