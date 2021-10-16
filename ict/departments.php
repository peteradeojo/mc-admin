<?php

require '../init.php';

$title = "Departments";
require '../header.php';
?>
<div class="container">
	<h1>Departments</h1>
</div>
<div class="container">
	<button class="btn modal-open" data-target="#department-modal">Open</button>
	<div class="row">
		<div class="col-md-5">
			<h3>Departments</h3>
			<ul class="list-group" id="department-list">
				<?php
				$departments = $db->select('departments');
				foreach ($departments as $dept) {
					echo "<li class='list-item d-flex justify-content-space-between'>
						<span class='name'>$dept[name]</span>
						<span class='label'>$dept[short_code]</span>
					</li>";
				}
				?>
			</ul>
		</div>
	</div>
</div>

<div id="department-modal" class="modal">
	<div class="modal-content p-2">
		<div class="modal-header pb-1">
			<h1>Create Department</h1>
		</div>
		<div class="modal-body py-1">
			<form action="/ict/newdept.php" method="post">
				<div class="form-group">
					<label for="name">Department Name</label>
					<input type="text" name="name" id="name" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="code">Code</label>
					<input type="text" name="code" id="code" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" class="form-control" required>
				</div>
				<div class="form-group mt-1">
					<button class="btn">Create</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php
$scripts = ['/ict/js/departments.js'];
require '../footer.php';
