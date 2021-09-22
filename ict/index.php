<?php
require '../init.php';

$title = 'Dashboard';
require '../header.php';
?>
<div class="container">
	<h1>Hello</h1>
	<!-- <p><?= "{$staff->getWorkspace()}" ?></p> -->
	<div class="row">
		<div class="col-sm-4 col-md-3">
			<a class="action-card" href="/ict/patients.php">
				<i class="fas fa-stethoscope fa-2x"></i>
				<span class="label">Patients</span>
				<span class='count'><?= $db->select('biodata', rows: "count(id) as num")['num'] ?></span>
			</a>
		</div>
		<div class="col-sm-4 col-md-3">
			<a class="action-card" href="/ict/staff.php">
				<i class="fa fa-2x fa-user"></i>
				<span class="label">Staff</span>
				<span class='count'><?= $db->select('staff', rows: "count(id) as num")['num'] ?></span>
			</a>
		</div>
		<div class="col-sm-4 col-md-3">
			<a href="/ict/backup.php" class="action-card">
				<i class="fa fa-save fa-2x"></i>
				<span class="label">Backup</span>
			</a>
		</div>
	</div>
</div>

<?php
require '../footer.php';
