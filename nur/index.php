<?php

require '../init.php';

$title = 'Dashboard';
require '../header.php';
?>
<div class="container">
	<h1>Hello</h1>
</div>
<div class="container mt-4">
	<div class="row">
		<div class="col-sm-4 col-md-3">
			<a href="/nur/waitlist.php" class="action-card p-1">
				<i class="fa fa-list fa-2x"></i>
				<span class="label">Waitlist</span>
				<span class="count"><?= $db->select('waitlist', 'count(id) as num', "status=0")[0]['num'] ?></span>
			</a>
		</div>
		<div class="col-sm-4 col-md-3">
			<a href="/nur/admissions.php" class="action-card p-1">
				<i class="fa fa-bed fa-2x"></i>
				<span class="label">Admissions</span>
				<span class="count">0</span>
			</a>
		</div>
		<div class="col-sm-8 col-md-6">
			<a href="/nur/report.php" class="action-card p-1">
				<i class="fa fa-pen fa-2x"></i>
				<span class="label">Submit Report</span>
			</a>
		</div>
	</div>
</div>
<?php
require '../footer.php';
