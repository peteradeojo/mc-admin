<?php

require '../init.php';

$title = 'Dashboard';
require '../header.php';
?>
<div class="container">
	<h1>Hello</h1>
</div>
<div class="container py-2">
	<div class="row row-md-reverse">
		<div class="col-md-6">
			<a href="/doc/waitlist.php" class="action-card">
				<i class="fa fa-2x fa-user"></i>
				<span class="label">Waiting</span>
				<span class="count"><?= $db->select('waitlist', 'count(id) as num', where: "status > 0")['num'] ?></span>
			</a>
		</div>
		<div class="col-md-6">
			<h2>Waiting Area</h2>
		</div>
	</div>
</div>
<?php
require '../footer.php';
