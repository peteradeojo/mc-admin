<?php

require '../init.php';

$patients = $db->select('biodata');

echo json_encode($patients);