<?php

use Patient\Admission;
use Patient\DoctorVisit;

require '../init.php';

$_POST['review'] = @$_POST['review'] == 'on';
$_POST['admitted'] = @$_POST['admitted'] == 'on';

try {
	$visit = new DoctorVisit(data: $_POST, username: $staff->getUsername());
	$visit->connect();
	$admission_id = $visit->save();
	if ($_POST['admitted']) {
		$admission = new Admission(data: $_POST, username: $staff->getUsername(), admission_id: $admission_id);
		$admission->connect();
		$admission->save();
	}
	header("Location: /");
} catch (Exception | Error $e) {
	echo $e->getMessage();
}
