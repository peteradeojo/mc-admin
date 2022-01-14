<?php

use Patient\Admission;
use Patient\DoctorVisit;
use Logger\Logger;

require '../init.php';

$_POST['review'] = @$_POST['review'] == 'on';
$_POST['admitted'] = @$_POST['admitted'] == 'on';

try {
	$visit = new DoctorVisit(data: $_POST, username: $staff->getUsername());
	$visit->connect();
	$admission_id = $visit->save();
	Logger::message("attended to patient $_POST[hospital_number]");
	if ($admission_id) {
		$admission = new Admission(data: $_POST, username: $staff->getUsername(), admission_id: $admission_id);
		$admission->connect();
		$admission->save();
		Logger::message("Admitted patient $_POST[hospital_number]. ID: {$admission->getAdmissionId()}");
	}
	header("Location: /");
} catch (Exception | Error $e) {
	echo $e->getMessage();
}
