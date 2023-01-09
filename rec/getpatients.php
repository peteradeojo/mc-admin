<?php

require '../init.php';

// $patients = $db->select('biodata') or [];
$patients = $db->join(['biodata as bio', 'antenatal_sessions as ancs'], [
	[
		'type' => 'left',
		'on' => 'bio.id = ancs.patient_id'
	],
], table_rows: "bio.*, ancs.flmp, ancs.edd, ancs.delivery_status, ancs.id as anc_id", limit: null);

$patients = array_map(function ($patient) {
	$patient['category'] = ucfirst($patient['category']);
	$patient['gender'] = $patient['gender'] ? 'M' : 'F';;
	return $patient;
}, $patients);

echo json_encode($patients);
