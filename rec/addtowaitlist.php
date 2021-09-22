<?php

require '../init.php';

$json = file_get_contents("php://input");
$data = json_decode($json);

$patientid = @$data->patientid;
if ($patientid) {
	try {
		// $confirm = $db->select('waitlist', where: "hospital_number=''")
		$patient = $db->select('biodata', 'name, hospital_number', where: "id='$patientid'");
		$confirm = $db->select('waitlist', where: "hospital_number='$patient[hospital_number]' AND status < 4");
		if (!$confirm) {
			$db->insert(['waitlist' => [
				'hospital_number' => $patient['hospital_number'],
				'checkedby' => $staff->getUsername()
			]]);
			echo json_encode(['ok' => true]);
		} else {
			echo json_encode(['message' => "$patient[name] is already waiting. Please attend to them"]);
			exit();
		}
	} catch (Exception $e) {
		echo json_encode(['message' => $e->getMessage()]);
	}
}
