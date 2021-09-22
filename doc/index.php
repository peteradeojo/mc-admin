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
		<div class="col-md-4">
			<a href="/doc/reviews.php" class="action-card p-1">
				<i class="fa fa-2x fa-book"></i>
				<span class="label">Reviews</span>
			</a>
			<a href="/doc/admissions.php" class="action-card p-1 mt-1">
				<i class="fa fa-2x fa-bed"></i>
				<span class="label">Admissions</span>
			</a>
		</div>
		<div class="col-md-8">
			<h2>Waiting Area</h2>
			<ul class="list-group">
				<?php
				try {
					$waitlist = $db->select('waitlist', where: "status > 0");
					if ($waitlist) {
						if (@!$waitlist[0]) {
							$waitlist = [$waitlist];
						}
						foreach ($waitlist as $item => $waiter) {
							$patient = $db->select('biodata', where: "hospital_number='$waiter[hospital_number]'");
							echo "<li class='list-item d-flex justify-content-space-between p-1'>
							<span>$patient[name]</span>";
							switch ($waiter['status']) {
								case '1':
									echo "<a href='/doc/attend.php?patient=$waiter[hospital_number]&action=attend' class='btn'>Attend</a>";
									break;
							}
							echo "</li>";
						}
					} else {
						echo "<li class='list-item'>No patients in the waiting area</li>";
					}
				} catch (Exception $e) {
					echo $e->getMessage();
				}
				?>
			</ul>
		</div>
	</div>
</div>
<?php
require '../footer.php';
