<?php

function flash(array $info = null)
{
	if ($info) {
		$_SESSION['flash'] = $info;
	} else {
		if (@$_SESSION['flash']) {
			$str = "<div class='alert bg-{$_SESSION['flash']['type']}'>{$_SESSION['flash']['message']}</div>";
			unset($_SESSION['flash']);
			return $str;
		}
	}
}
