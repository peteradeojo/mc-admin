<?php

require '../init.php';

// $prescriptions = $db->select('visits', rows: "prescriptions", where: "1");
$prescriptions = $db->join(
	[
		'biodata as bd', 'visits as vis'
	],
	[
		['type' => 'right', 'on' => 'vis.hospital_number = bd.hospital_number'],
	],
	where: "vis.prescriptions LIKE '%\"status\": 0%'",
	table_rows: "name, vis.id"
);

$title = 'Pending Prescriptions';
require '../header.php';
?>
<div class="container">
	<h1>Pending Prescriptions</h1>
</div>
<div class="container">
	<ul class="list-group">
		<?php
		foreach ($prescriptions as $prescript => $data) {
			// $json_data = json_decode($data['prescriptions']);
			echo "<li class='list-item'><a href='#!' class='show-prescription modal-open' data-target='#prescription-modal' data-id='$data[id]'>$data[name]</a></li>";
		}
		?>
	</ul>
</div>

<div id="prescription-modal" class="modal">
	<div class="modal-content p-2">
		<span class="close btn" data-dismiss='#prescription-modal'>&times;</span>
		<div class="modal-header pb-1">
			<h2>Prescription</h2>
		</div>
		<div class="modal-body py-1">
			Help
		</div>
		<!-- <div class="modal-footer"></div> -->
	</div>
</div>
<?php
$scripts = ['/phm/js/prescriptions.js'];
require '../footer.php';
