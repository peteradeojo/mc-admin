<?php

require '../init.php';

if (@!$_GET['id']) {
	flash(['message' => 'No patient ID provided', 'type' => 'danger']);
	header("Location: /rec");
}

$patient = $db->join(['biodata as bd', 'insurance_data as id'], [
	['type' => 'left', 'on' => 'bd.hospital_number=id.hospital_number']
], where: "bd.hospital_number='{$_GET['id']}'");
print_r($patient);

$title = 'Edit Patient';
require '../header.php';
?>
<div class="container">
	<h1>Edit Patient</h1>
</div>
<div class="container">
	<form action="" method="post">
		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" name="name" id="name" required="required" class="form-control" value="">
		</div>
	</form>
</div>
<?php
require '../footer.php';
