<?php

require '../init.php';

$people = $db->select('staff');

for ($i = 0; $i < count($people); $i += 1) {
	$dept = $db->select('departments', where: "short_code='{$people[$i]['department']}'")[0]['name'];
	$active = $db->select('login', rows: 'active', where: "username='{$people[$i]['username']}'")[0]['active'];
	// echo $dept;
	$people[$i]['name'] = "{$people[$i]['lastname']} {$people[$i]['firstname']}";
	$people[$i]['department'] = $dept;
	$people[$i]['active'] = $active;
}

echo json_encode($people);
