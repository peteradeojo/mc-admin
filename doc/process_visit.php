<?php

use Patient\DoctorVisit;

require '../init.php';

$_POST['review'] = @$_POST['review'] == 'on';
$_POST['admitted'] = @$_POST['admitted'] == 'on';

try {
	$visit = new DoctorVisit(data: $_POST, username: $staff->getUsername());
	$visit->connect();
	$visit->save();
	header("Location: /");
} catch (Exception | Error $e) {
	echo $e->getMessage();
}
