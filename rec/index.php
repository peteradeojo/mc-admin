<?php

require '../init.php';

$title = 'Dashboard';
require '../header.php';
?>
<div class="container">
	<h1>Hello</h1>
	<div class="row">
		<div class="col-md-4">
			<a href="/rec/patients.php" class="action-card">
				<i class="fa fa-user fa-2x"></i>
				<span class="label">Patients</span>
				<span class="count"><?= $db->select('biodata', 'count(id) as num')['num'] ?></span>
			</a>
		</div>
	</div>
</div>
<?php
require '../footer.php';
