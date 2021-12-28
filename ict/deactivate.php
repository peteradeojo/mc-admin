<?php

use Logger\Logger;

require '../init.php';

if (!$staff->canWrite() or $staff->getAccessLevel() < 5) {
	echo json_encode(['error' => true, 'message' => "You're not authorized to perform this action. Contact IT."]);
	Logger::message("Attmpt. user deactivation. insufficient auth level");
	exit();
}

$data = file_get_contents("php://input");
$user = json_decode($data);
if ($user) {
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
		], where: "username='$user->username'");
		Logger::message("Deactivated user " . $user->username);
		echo json_encode(['ok' => true]);
	} catch (Exception $e) {
		Logger::message($e->getMessage(), mode: 'error');
		echo json_encode(['error' => true, 'message' => $e->getMessage()]);
	}
} else {
	echo json_encode(['message' => 'No username']);
}
