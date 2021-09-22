<?php

require '../init.php';

$title = 'Dashboard';
require '../header.php';
?>
<div class="container">
	<h1>Hello</h1>
	<div class="row">
		<div class="col-sm-4 col-md-3">
			<a href="/rec/patients.php" class="action-card">
				<i class="fa fa-user fa-2x"></i>
				<span class="label">Patients</span>
				<span class="count"><?= $db->select('biodata', 'count(id) as num')['num'] ?></span>
			</a>
		</div>
		<div class="col-sm-4 col-md-3">
			<a href="/rec/waitlist.php" class="action-card">
				<i class="fa fa-clipboard-check fa-2x"></i>
				<span class="label">Waitlist</span>
				<span class="count"><?= $db->select('waitlist', 'count(id) as num')['num'] ?></span>
			</a>
		</div>
	</div>
</div>
<?php
require '../footer.php';
