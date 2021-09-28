<?php

use Auth\Auth;
use Staff\Staff;

require '../init.php';

if ($_POST) {
	$username = $_POST['username'];
	$staffupdate = new Staff($username, dbClient: $db);
	$staffupdate->load();

	print_r($_POST);
	exit();
}

// if (!@$_GET['user'])
Auth::redirectOnFalse(@$_GET['user'], '/ict');

$staffData = $db->select('staff', where: "username='$_GET[user]'")[0];
// print_r($staffData);
// exit();

require '../header.php';
?>
<div class="container">
	<h1><?= "$staffData[lastname] $staffData[firstname]" ?></h1>
</div>
<div class="container py-2">
	<div class="col-md-5">
		<form action="" method="post">
			<div class="form-group">
				<label for="firstname">Firstname</label>
				<input type="text" name="firstname" id="firstname" class="form-control" required="required" value="<?= $staffData['firstname'] ?>">
			</div>
			<div class="form-group">
				<label for="lastname">Lastname</label><input type="text" name="lastname" id="lastname" class="form-control" required value="<?= $staffData['lastname'] ?>">
			</div>
			<div class="form-group">
				<label for="phone_number">Phone Number</label>
				<input type="text" name="phone_number" id="phone_number" class="form-control" required value=<?= $staffData['phone_number'] ?>>
			</div>
			<div class="form-group">
				<label for="username">Username</label><input type="text" name="username" id="username" class="form-control" required="required" value="<?= $staffData['username'] ?>" readonly>
			</div>
			<div class="form-group">
				<label for="designation">Designation</label>
				<input type="text" name="designation" id="designation" class="form-control" required="required" value="<?= $staffData['designation'] ?>">
			</div>
			<div class="form-group">
				<label for="department">Department</label>
				<select name="department" id="department" class="form-control" required="required">
					<?php
					$departments = $db->select('departments');
					foreach ($departments as $dept => $data) {
						echo "<option value='$data[short_code]'";
						if ($data['short_code'] == $staffData['department']) echo " selected ";
						echo ">$data[name]</option>";
					}
					?>
				</select>
			</div>
			<div class="form-group mt-1">
				<button type="submit" class="btn">Submit</button>
			</div>
		</form>
	</div>
</div>
<?php
require '../footer.php';
