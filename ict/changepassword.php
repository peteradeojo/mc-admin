<?php

use Auth\Auth;
use Staff\Staff;

require '../init.php';

if ($_POST) {
	if (@!$_POST['username']) {
		flash(['type' => 'danger', 'message' => 'No username provided. A username is required']);
		header("Location: /ict/staff.php");
	}

	if (!$staff->canWrite()) {
		flash(['message' => 'You are not allowed to update this record. Contact IT', 'type' => 'danger']);
	} else {
		if ($staff->getAccessLevel() < 4) {
			$username = $_POST['username'];
			try {
				//code...
				$staffUpdate = new Staff($username, $db, $_POST['password']);
				$staffUpdate->update(data: $_POST, info: 'login');
				flash(['message' => 'Password Changed', 'type' => 'success']);
			} catch (Exception $e) {
				flash(['message' => $e->getMessage(), 'type' => 'danger']);
			}
		} else {
			flash(['message' => 'You do not have the required permission to update this record. Contact IT', 'type' => 'danger']);
		}
	}
	header("Location: /ict/staff.php");

	exit();
}

Auth::redirectOnFalse(@$_GET['user'], '/ict');

$username = $_GET['user'];

$title = 'Change Password';
require '../header.php';
?>
<div class="container">
	<h3>Change Password</h3>
</div>
<div class="container py-3">
	<form action="" method="post" class="row">
		<div class="form-group col-md-4">
			<label>Username</label>
			<input type="text" name="username" id="username" class="form-control" readonly="readonly" required="required" value="<?= $username ?>">
		</div>
		<div class="form-group col-md-4">
			<label for="password">Password</label>
			<input type="password" name="password" id="password" required="required" class="form-control">
		</div>
		<div class="form-group col-12">
			<button type="submit" class="btn">Submit</button>
		</div>
	</form>
</div>
<?php
require '../footer.php';
