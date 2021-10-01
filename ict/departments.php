<?php

require '../init.php';

$title = "Departments";
require '../header.php';
?>
<div class="container">
	<h1>Departments</h1>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-7"></div>
		<div class="col-md-5">
			Departments
			<ul class="list-group" id="department-list">
				<?php
				$departments = $db->select('departments');
				foreach ($departments as $dept) {
					echo "<li class='list-group-item'>
						<span class='name'>$dept[name]</span>
						<span class='label'>$dept[short_code]</span>
					</li>";
				}
				?>
			</ul>
		</div>
	</div>
</div>
<?php
$scripts = ['/ict/js/departments.js'];
require '../footer.php';
