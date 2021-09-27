<?php

require '../init.php';

$title = 'Reviews';

// $stylesheets = ['assets/css/reviews.css'];
require '../header.php';
?>
<div class="container">
	<h1>Reviews</h1>

	<div class="pt-2 row">
		<div class="col-md-6">
			<p>Pending reviews</p>
			<ul class="py-2 list-group">
				<?php
				// $reviews = $db->select('visits', where: "review = 1 OR review_date IS NOT NULL;");
				try {
					$review_data = $db->join(tables: ['visits as vis', 'biodata as bd', 'admissions as ad'], join_types: [
						[
							'type' => 'inner',
							'on' => 'vis.hospital_number = bd.hospital_number'
						],
						[
							'type' => 'left',
							'on' => 'ad.hospital_number = bd.hospital_number'
						]
					], where: "vis.review = 1 or vis.review_date is not null");
				} catch (Exception $e) {
					echo $e->getMessage();
				}
				foreach ($review_data as $review) {
					$review['review_date'] = @$review['review_date'] ? $review['review_date'] : 'Undecided';
					switch ($review['admitted']) {
						case 1:
							$review['admitted'] = 'Yes';
							break;
						default:
							$review['admitted'] = 'No';
							break;
					}
					echo "<li class='list-group-item border p-1 rounded-lg'>
						<div>
							<p><b>$review[name]</b></p>
							<p>Date for review: $review[review_date]</p>
							<p>Admitted: $review[admitted] </p>
							<p>Complaints: $review[complaints]</p>
						</div>
					</li>";
				}
				?>
			</ul>
			<div class="container">
				<?= print_r($review_data, true) ?>
			</div>
		</div>
	</div>
</div>
<?php
require '../footer.php';
