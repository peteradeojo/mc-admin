<?php

require '../init.php';

$data = file_get_contents("php://input");
$user = json_decode($data);
if ($user) {
	// echo $staff->getUsername();
	// exit();
	if ($user->username == $staff->getUsername()) {
		echo json_encode(['message' => 'Cannot deactivate your own account']);
		exit();
	}
	try {
		//code...
		$db->update([
			'login' => [
				'active' => 0,
			]
		], "username='$user->username'");
		echo json_encode(['ok' => true]);
	} catch (Exception $e) {
		echo json_encode(['error' => true, 'message' => $e->getMessage()]);
	}
} else {
	echo json_encode(['message' => 'No username']);
}
