<?php

require '../init.php';

$data = file_get_contents("php://input");
$user = json_decode($data);
if ($user) {
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
