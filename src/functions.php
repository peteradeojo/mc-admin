<?php

function flash(array $info = null)
{
	if ($info) {
		$_SESSION['flash'] = $info;
	} else {
		if (@$_SESSION['flash']) {
			$str = "<div class='container alert bg-{$_SESSION['flash']['type']} alert-dismissible'>" . $_SESSION['flash']['message'] . "<button class='btn close'>&times;</button></div>";
			unset($_SESSION['flash']);
			echo $str;
		}
	}
}

function newFlash($type, $message)
{
	$_SESSION['flash'] = [
		'type' => $type,
		'message' => $message
	];
}

function sanitizeInput($input)
{
	$input = trim($input);
	$input = stripslashes($input);
	$input = htmlspecialchars($input);
	return $input;
}


function redirect($location)
{
	header("Location: $location");
	exit;
}
