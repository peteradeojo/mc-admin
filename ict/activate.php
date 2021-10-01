<?php

require '../init.php';

if (!$staff->canWrite() or $staff->getAccessLevel() < 5) {
	echo json_encode(['error' => true, 'message' => "You're not authorized to perform this action. Contact IT."]);
	exit();
}

$data = file_get_contents("php://input");
$user = json_decode($data);
if ($user) {
	try {
		//code...
		$db->update([
			'login' => [
				'active' => 1,
			]
		], "username='$user->username'");
		echo json_encode(['ok' => true]);
	} catch (Exception $e) {
		echo json_encode(['error' => true, 'message' => $e->getMessage()]);
	}
} else {
	echo json_encode(['message' => 'No username']);
}
