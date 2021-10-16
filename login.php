<?php

use Database\Database;
use Staff\Staff;

require 'init.php';

if ($_POST) {
	try {
		//code...
		$db = new Database();
		$db->connect();
		$staff = new Staff($_POST['username'], password: $_POST['password'], dbClient: $db);
		if ($staff->authenticate()) {
			$_SESSION['staff'] = serialize($staff);
			header("Location: /");
		} else {
			echo "No auth";
		}
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	exit();
}

$title = 'Admin | Login';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-sclae=1.0">
	<!-- <link rel="stylesheet" href="/assets/css/style.css"> -->
	<link rel="stylesheet" href="/assets/css/login.css">
	<title>Admin | Login</title>
</head>

<body>
	<?= flash() ?>
	<main>
		<div class="container">
			<img src="/assets/maternal-and-child.png" alt="">

			<form action="" method="post">
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" name="username" id="username" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" class="form-control" required>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</main>

</body>

</html>