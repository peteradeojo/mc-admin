<?php

require '../init.php';

$title = 'Patients';
require '../header.php';
?>
<div class="container">
	<div class="row">
		<div class="col-md-6">
			<h1>Patients</h1>
		</div>
		<div class="col-md-6" style="text-align: right;">
			<a href="/rec/newpatient.php" class="btn btn-primary">Add Patient</a>
		</div>
	</div>
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
