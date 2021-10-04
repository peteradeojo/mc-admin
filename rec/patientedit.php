<?php

require '../init.php';

if (@!$_GET['id']) {
	flash(['message' => 'No patient ID provided', 'type' => 'danger']);
	header("Location: /rec");
}

$patient = $db->join(['biodata as bd', 'insurance_data as id'], [
	['type' => 'left', 'on' => 'bd.hospital_number=id.hospital_number']
], where: "bd.hospital_number='{$_GET['id']}'")[0];

$title = 'Edit Patient';
require '../header.php';
?>
<div class="container">
	<h1>Edit Patient</h1>
</div>
<div class="container">
	<form action="" method="post">
		<fieldset class="row py-2 px-1">
			<legend>
				<h2>Biodata</h2>
			</legend>
			<div class="form-group editable-display col-md-6">
				<label for="name">Name</label>
				<input type="text" name="name" id="name" required="required" class="form-control" value="<?= $patient['name'] ?>">
				<div class="content" id='name-display'>
					<span class="label"><?= $patient['name'] ?></span>
					<a href="#!" onclick="editDetail('#name', '#name-display')">Edit</a>
				</div>
			</div>
			<div class="form-group editable-display col-md-6">
				<label for="birthdate">Date of Birth</label>
				<input type="date" name="birthdate" id="birthdate" class="form-control" value="<?= $patient['birthdate'] ?>">
				<div class="content" id='birthdate-display'>
					<div class="span"><?= $patient['birthdate'] ?></div>
					<a href="#!" onclick="editDetail('#birthdate', '#birthdate-display')">Edit</a>
				</div>
			</div>
			<div class="form-group editable-display col-md-6">
				<label for="gender">Sex</label>
				<select name="gender" id="gender" class="form-control">
					<option value="1" <?= $patient['gender'] == 1 ? 'selected' : '' ?>>Male</option>
					<option value="0" <?= $patient['gender'] == 0 ? 'selected' : '' ?>>Female</option>
				</select>
				<div class="content" id="sex-display">
					<span class="label"><?= $patient['gender'] == 0 ? 'Female' : 'Male' ?></span>
					<a href="#!" onclick="editDetail('#gender', '#sex-display')">Edit</a>
				</div>
			</div>

			<div class="form-group editable-display col-md-6">
				<label for="phone">Phone Number</label>
				<input type="text" name="phone_number" id="phone" class="form-control" value="<?= $patient['phone_number'] ?>">
				<div class="content" id="phone-display"><span class="label"><?= $patient['phone_number'] ?></span>
					<a href="#!" onclick="editDetail('#phone', '#phone-display')">Edit</a>
				</div>
			</div>

			<div class="form-group editable-display col-md-6">
				<label for="email">E-mail</label>
				<input type="email" name="email_address" id="email" class="form-control" value="<?= $patient['email_address'] ?>">
				<div class="content" id="email-display"><span class="label"><?= $patient['email_address'] ?></span>
					<a href="#!" onclick="editDetail('#email', '#email-display')">Edit</a>
				</div>
			</div>

			<div class="form-group editable-display col-md-6">
				<label for="state">State of Origin</label>
				<input type="text" name="state_of_origin" id="state" class="form-control" value="<?= $patient['state_of_origin'] ?>">
				<div class="content" id="state-display"><span class="label"><?= $patient['state_of_origin'] ?></span><a href="#!" onclick="editDetail('#state', '#state-display')">Edit</a></div>
			</div>
			<div class="form-group editable-display col-md-6">
				<label for="tribe">Tribe</label>
				<input type="text" name="tribe" id="tribe" class="form-control" value="<?= $patient['tribe'] ?>">
				<div class="content" id="tribe-display"><span class="label"><?= $patient['tribe'] ?></span><a href="#!" onclick="editDetail('#tribe', '#tribe-display')">Edit</a></div>
			</div>

			<div class="form-group editable-display col-md-6">
				<label for="occupation">Occupation</label>
				<input type="text" name="occupation" id="occupation" class="form-control" value="<?= $patient['occupation'] ?>">
				<div class="content" id="occupation-display"><span class="label"><?= $patient['occupation'] ?></span><a href="#!" onclick="editDetail('#occupation', '#occupation-display')">Edit</a></div>
			</div>

			<div class="form-group editable-display col-md-6">
				<label for="religion">Religion</label>
				<select name="religion" id="religion" class="form-control">
					<option value="christianity" <?= $patient['religion'] == 'christianity' ? 'selected' : '' ?>>Christianity</option>
					<option value="islam" <?= ($patient['religion'] == 'islam' or $patient['religion'] == 'Muslim') ? 'selected' : '' ?>>Islam</option>
					<option value="other" <?= $patient['religion'] == 'other' ? 'selected' : '' ?>>Other</option>
				</select>
				<div class="content" id="religion-display"><span class="label"><?= $patient['religion'] ?></span><a href="#!" onclick="editDetail('#religion', '#religion-display')">Edit</a></div>
			</div>
			<div class="form-group editable-display col-md-6">
				<label for="marital_status">Marital Status</label>
				<select name="marital_status" id="marital_status" class="form-control">
					<option value="Single" <?= $patient['marital_status'] == 'Single' ? 'selected' : '' ?>>Single</option>
					<option value="Married" <?= $patient['marital_status'] == 'Married' ? 'selected' : '' ?>>Married</option>
					<option value="Widowed" <?= $patient['marital_status'] == 'Widowed' ? 'selected' : '' ?>>Widowed</option>
					<option value="Divorced" <?= $patient['marital_status'] == 'Divorced' ? 'selected' : '' ?>>Divorced</option>
				</select>
				<div class="content" id="marital_status-display"><span class="label"><?= $patient['marital_status'] ?></span><a href="#!" onclick="editDetail('#marital_status', '#marital_status-display')">Edit</a></div>
			</div>

			<div class="form-group editable-display col-12">
				<label for="address">Address</label>
				<input type="text" name="address" id="address" class="form-control" value="<?= $patient['address'] ?>">
				<div class="content" id="address-display"><span class="label"><?= $patient['address'] ?></span><a href="#!" onclick="editDetail('#address', '#address-display')">Edit</a></div>
			</div>
		</fieldset>

		<fieldset class="row mt-2 py-2 px-1">
			<legend>
				<h2>Insurance Details</h2>
			</legend>
			<div class="form-group editable-display col-md-6">
				<label for="hmo_name">Insurance Company Name</label>
				<input type="text" name="hmo_name" id="hmo_name" class="form-control" value="<?= $patient['hmo_name'] ?>">
				<div class="content"><span class="label"><?= $patient['hmo_name'] ?></span><a href="#!">Edit</a></div>
			</div>
		</fieldset>

		<div class="form-group col-12">
			<button type="submit" class="btn">Save</button>
		</div>

	</form>
</div>
<?php
// $scripts = ['/js/edit.js'];
require '../footer.php';
