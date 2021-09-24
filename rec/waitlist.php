<?php

require '../init.php';

$title = 'Waitlist';
require '../header.php';
?>
<div class="container">
	<h1>Waitlist</h1>
	<p>Patients in the waiting area</p>
</div>
<div class="container mt-2">
	<!-- <h1>Hello</h1> -->
	<ul class="list-group">
		<?php
		try {
			$waitlist = $db->select('waitlist');
			foreach ($waitlist as $item => $waiter) {
				$patient = $db->select('biodata', where: "hospital_number='$waiter[hospital_number]'")[0];
				echo "<li class='list-item'><p>$patient[name]</p><p>Status: $waiter[status]</p></li>";
			}
			// print_r($waitlist);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		?>
	</ul>
</div>
<?php
require '../footer.php';
