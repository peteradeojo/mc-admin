<?php

require '../init.php';

$title = "Patients";
require '../header.php';
?>
<div class="container">
	<h1>Patients</h1>
</div>
<div class="container">
	<table id="patients-table">
		<thead>
			<th></th>
			<th>Name</th>
			<th>Card Number</th>
			<th>Category</th>
		</thead>
		<tbody></tbody>
	</table>
</div>
<?php
$scripts = ['/ict/js/patients.js'];
require '../footer.php';
