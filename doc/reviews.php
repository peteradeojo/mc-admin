<?php

require '../init.php';

$title = 'Reviews';
require '../header.php';
?>
<div class="container">
	<h1>Reviews</h1>

	<div class="pt-2">
		<p>Pending reviews</p>
		<ul class="py-2">
			<?php
				$reviews = $db->select('visits', );
			?>
		</ul>
	</div>
</div>
<?php
require '../footer.php';
