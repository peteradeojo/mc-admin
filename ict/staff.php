<?php

require '../init.php';
$title = 'Staff';
require '../header.php';
?>
<div class="container">
	<h1 class="mb-4">Staff</h1>
	<div class="py-3">
		<a href="/ict/newstaff.php" class="btn btn-primary">Add Staff</a>
	</div>
	<table id="staff-table" class="datatable">
		<thead>
			<tr>
				<th></th>
				<th>Name</th>
				<th>Office</th>
				<th>Department</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<?php
$scripts = ['/ict/js/staff.js'];
require '../footer.php';
