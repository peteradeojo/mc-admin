<?php

require '../init.php';

$patients = $db->select('biodata') or [];

for ($i = 0; $i < count($patients); $i += 1) {
	$patients[$i]['category'] = ucfirst($patients[$i]['category']);
	$patients[$i]['gender'] = $patients[$i]['gender'] ? 'M' : 'F';
}
echo json_encode($patients);
