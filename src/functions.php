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
