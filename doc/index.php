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
				<span class="count"><?= $db->select('visits', 'count(review) as num', where: "review=1 and (review_status != 1 or review_status IS NULL)")[0]['num'] ?></span>
			</a>
			<a href="/doc/admissions.php" class="action-card p-1 mt-1">
				<i class="fa fa-2x fa-bed"></i>
				<span class="label">Admissions</span>
				<span class="count"><?= $db->select('admissions', "count(id) as num", where: "date_discharged is null")[0]['num'] ?></span>
			</a>
		</div>
		<div class="col-md-8">
			<h2>Waiting Area</h2>
			<ul class="list-group">
				<?php
				try {
					$waitlist = $db->select('waitlist', orderby: "status DESC", where: "status < 2 and status > 0");
					if ($waitlist) {
						foreach ($waitlist as $item => $waiter) {
							$patient = $db->select('biodata', where: "hospital_number='$waiter[hospital_number]'")[0];
							echo "<li class='list-item d-flex justify-content-space-between p-1'>
							<span>$patient[name]</span>";
							switch ($waiter['status']) {
								case '1':
									echo "<a href='/doc/attend.php?patient=$waiter[hospital_number]&action=attend' class='btn'>Attend</a>";
									break;
								case '2':
									echo "<a href='/doc/attend.php?patient=$waiter[hospital_number]&action=view' class='btn'>View</a>";
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
