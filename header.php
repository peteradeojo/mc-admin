<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/assets/css/style.css">
	<?php
	if (@$stylesheets) {
		foreach ($stylesheets as $style) {
			echo "<link href='$style' rel='stylesheet'>";
		}
	}
	?>
	<title><?= @$title ? $title : 'Admin' ?></title>
</head>

<body>
	<aside>
		<div class="container">
			<a href="/" class="head-link"><img src="/assets/maternal-and-child.png" alt="" srcset="" height="50"><span><?= strtoupper($staff->getUsername()) ?></span></a>
		</div>

		<div class='nav' id="links">
			<a href="#" class="nav-item">Link 1</a>
			<a href="#" class="nav-item">Link 2</a>
			<a href="#" class="nav-item">Link 3</a>
			<a href="/logout.php" class="nav-item">Log Out</a>
</div>
	</aside>
	<main>
		<div id="topbar">
			<?php
			if (@$staff) {
				echo $staff->getUsername();
			}
			?>
		</div>
		<?php
		for ($i = 0; $i < 200; $i += 1) {
			echo "$i<br>";
		}
		?>