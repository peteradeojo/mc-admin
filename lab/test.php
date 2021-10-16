<?php

use Auth\Auth;
use Lab\Laboratory;
use Lab\Test;

require '../init.php';

Auth::redirectOnFalse($_GET['id'], '/lab');

$testid = $_GET['id'];

$test = new Test($testid);
$test->connect();

$test_data = $test->load_test();

$title = $test_data['name'];

require '../header.php';
?>
<div class="container">
  <h1><?= $test_data['name'] ?></h1>
</div>
<?php
require '../footer.php';
