<?php

require '../init.php';

$people = $db->select('staff');
if ($people) {
	if (!@$people[0]) {
		$people = [$people];
	}
}

for ($i = 0; $i < count($people); $i += 1) {
	$dept = $db->select('departments', where: "short_code='{$people[$i]['department']}'")['name'];
	$active = $db->select('login', rows: 'active', where: "username='{$people[$i]['username']}'")['active'];
	// echo $dept;
	$people[$i]['name'] = "{$people[$i]['lastname']} {$people[$i]['firstname']}";
	$people[$i]['department'] = $dept;
	$people[$i]['active'] = $active;
}

echo json_encode($people);
