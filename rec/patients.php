<?php

require '../init.php';

$title = 'Patients';
require '../header.php';
?>
<div class="container">
	<h1>Patients</h1>
</div>
<div class="container">
	<a href="newpatient.php" class="btn btn-primary">New Patient</a>
</div>
<div class="container mt-4">
	<table id="patients-table">
		<thead>
			<tr>
				<th></th>
				<th>Card Number</th>
				<th>Name</th>
				<th>Category</th>
				<th>Gender</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div>
<?php
$scripts = ['/rec/js/patients.js'];
require '../footer.php';
