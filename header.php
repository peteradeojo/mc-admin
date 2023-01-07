<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css" integrity="sha512-1k7mWiTNoyx2XtmI96o+hdjP8nn0f3Z2N4oF/9ZZRgijyV4omsKOXEnqL1gKQNPy2MTSP9rIEWGcH/CInulptA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
	<aside class="closed no-print">
		<div class="container" id="sideTopBar">
			<button class="btn" onclick="closeSideNav()">&times;</button>
		</div>
		<div class="container">
			<a href="/<?= $staff->getWorkspace() ?>" class="head-link">
				<img src="/assets/maternal-and-child.png" alt="" srcset="" height="50">
				<div style="text-align: center;">
					<p><?= strtoupper($staff->getUserdata()['firstname']) ?></p>
					<p style="font-size: .84em;"><u><?= $staff->getUserdata()['designation'] ?></u></p>
				</div>
			</a>
		</div>

		<div class='nav' id="links">
			<?php
			$links = $staff->getLinks();
			if ($links) {
				foreach ($links as $link => $value) {
					# code...
					echo "<a href='$value' class='nav-item'>$link</a>";
				}
			}
			?>
			<a href="/logout.php" class="nav-item">Log Out</a>
		</div>
	</aside>
	<main>
		<div id="topbar">
			<button class="btn" onclick="openSideNav()"><i class="fa fa-bars"></i></button>
		</div>
		<?= flash() ?>