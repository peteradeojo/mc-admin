<?php

require '../init.php';

$title = 'Pharmacy';
require '../header.php';
?>
<div class="container">
	<h1>Pharmacy</h1>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-4">
			<a href="/phm/prescriptions.php" class="action-card p-1">
				<i class="fa fa-capsules fa-2x"></i>
				<span class="label">Prescriptions</span>
				<span class="count"><?= $db->select('visits', 'count(id) as num', where: "prescriptions NOT LIKE '[]'")[0]['num']; ?></span>
			</a>
		</div>
		<div class="col-md-4">
			<a href="/phm/admissions.php" class="action-card p-1">
				<i class="fa fa-2x fa-bed"></i>
				<span class="label">Admissions</span>
				<div class="count"><?= $db->select('admissions', "count(id) as num", where: "date_discharged IS NULL")[0]['num']; ?></div>
			</a>
		</div>
		<div class="col-md-4"></div>
	</div>
</div>
<?php
require '../footer.php';
