<?php

require '../init.php';

$title = 'Waitlist';
require '../header.php';
?>
<div class="container">
	<h1>Waitlist</h1>
</div>
<div class="container mt-2">
	<table id="waitlist">
		<thead>
			<tr>
				<th>Hospital Number</th>
				<th>Name</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php
			try {
				$waitlist = $db->select('waitlist');
				if ($waitlist) {
					if (@!$waitlist[0]) {
						$waitlist = [$waitlist];
					}
				}
				foreach ($waitlist as $item => $waiter) {
					$patient = $db->select('biodata', where: "hospital_number='$waiter[hospital_number]'");
					echo "<tr>
						<td>$waiter[hospital_number]</td>
						<td>$patient[name]</td>";

					if ($waiter['status'] == 0) {
						echo "<td><a href='/nur/vitals.php?patient=$waiter[hospital_number]&action=take' class='btn'>Take Vitals</a></td>";
					} elseif ($waiter['status'] == 1) {
						echo "<td>
							<button class='btn'>Send to Doctor</button>
							<a href='/nur/vitals.php?patient=$waiter[hospital_number]&action=view'>View Vitals</a>
						</td>";
						echo "<td></td>";
					}
					echo "</tr>";
				}
				// print_r($waitlist);
			} catch (Exception $e) {
				echo $e->getMessage();
			}
			?>
		</tbody>
	</table>
</div>
<?php
$scripts = ['/nur/js/waitlist.js'];
require '../footer.php';
