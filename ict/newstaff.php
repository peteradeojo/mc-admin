<?php

use Staff\NewStaff;

require '../init.php';

if ($_POST) {
	try {
		$newstaff = new NewStaff($_POST, $db);
		$newstaff->save();
	} catch (Exception $e) {
		// $_SESSION
		flash(['message' => $e->getMessage(), 'type' => 'danger']);
	}
	header("Location: /ict/staff.php");
}

require '../header.php';
?>

<div class="container">
	<h1>New Staff</h1>

	<div class="mt-4">
		<form action="" method="post">
			<div class="form-group">
				<label for="firstname">Firstname</label>
				<input type="text" name="firstname" id="firstname" class="form-control" required="required">
			</div>
			<div class="form-group">
				<label for="lastname">Lastname</label><input type="text" name="lastname" id="lastname" class="form-control" required>
			</div>
			<div class="form-group">
				<label for="phone_number">Phone Number</label>
				<input type="text" name="phone_number" id="phone_number" class="form-control" required>
			</div>
			<div class="form-group">
				<label for="username">Username</label><input type="text" name="username" id="username" class="form-control" required="required">
			</div>
			<div class="form-group">
				<label for="password">Create Password</label><input type="password" name="password" id="password" class="form-control" required="required">
			</div>
			<div class="form-group">
				<label for="designation">Designation</label>
				<input type="text" name="designation" id="designation" class="form-control" required="required">
			</div>
			<div class="form-group">
				<label for="department">Department</label>
				<select name="department" id="department" class="form-control" required="required">
					<?php
					$departments = $db->select('departments');
					foreach ($departments as $dept => $data) {
						echo "<option value='$data[short_code]'>$data[name]</option>";
					}
					?>
				</select>
			</div>
			<div class="form-group">
				<button type="submit" class="btn">Submit</button>
			</div>
		</form>
	</div>
</div>