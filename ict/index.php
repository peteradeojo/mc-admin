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
			<a class="action-card p-1" href="/ict/patients.php">
				<i class="fas fa-stethoscope fa-2x"></i>
				<span class="label">Patients</span>
				<span class='count'><?= $db->select('biodata', rows: "count(id) as num")[0]['num'] ?></span>
			</a>
		</div>
		<div class="col-sm-4 col-md-3">
			<a class="action-card p-1" href="/ict/staff.php">
				<i class="fa fa-2x fa-user"></i>
				<span class="label">Staff</span>
				<span class='count'><?= $db->select('staff', rows: "count(id) as num")[0]['num'] ?></span>
			</a>
		</div>
		<div class="col-sm-4 col-md-3">
			<a class="action-card p-1" href="/ict/departments.php">
				<i class="fa fa-2x fa-table"></i>
				<span class="label">Departments</span>
				<span class='count'><?= $db->select('departments', rows: "count(id) as num")[0]['num'] ?></span>
			</a>
		</div>
		<div class="col-sm-4 col-md-3">
			<a href="/ict/backup.php" class="action-card p-1">
				<i class="fa fa-save fa-2x"></i>
				<span class="label">Backup</span>
			</a>
		</div>
	</div>
</div>

<?php
require '../footer.php';
