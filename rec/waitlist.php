<?php

require '../init.php';

$title = $staff->getUserdata()['firstname'] . ' | Waitlist';

require '../header.php';
?>
<div class="container">
	<h1>Waitlist</h1>
	<p>Patients in the waiting area</p>
</div>
<div class="container mt-2 row">
	<!-- <h1>Hello</h1> -->
	<ul class="list-group col-md-6">
		<?php
		try {
			$waitlist = $db->select('waitlist', where: "status < 2");
			$waitlist = array_map(function ($wait) {
				switch ($wait['status']) {
					case '0':
						$wait['status_text'] = 'Waiting for Vitals';
						break;
					case '1':
						$wait['status_text'] = 'Awaiting doctor';
						break;
				}
				return $wait;
			}, $waitlist);

			if (!$waitlist) {
				echo "<li class='list-item'><p>No patients in the waiting area.</p></li>";
			} else {
				foreach ($waitlist as $item => $waiter) {
					$patient = $db->select('biodata', where: "hospital_number='$waiter[hospital_number]'")[0];
					echo "<li class='list-item border mb-1 p-1 rounded-lg'><p>$patient[name]</p><p>Status: $waiter[status_text]</p></li>";
				}
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		?>
	</ul>
</div>
<?php
require '../footer.php';
