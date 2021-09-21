<?php

namespace Auth;

use Database\Database;

class Auth
{
	static function confirmLogin()
	{
		$allowed_script = 'login.php';
		if (str_ends_with($_SERVER['SCRIPT_NAME'], $allowed_script)) return true;

		if (@$_SESSION['login']['status'] !== true) {
			return false;
		}
		return true;
	}

	static function redirectOnFalse(bool $value, string $location)
	{
		if (!$value) {
			header("Location: $location");
		}
	}

	static function logOut()
	{
		$_SESSION['login']['status'] = false;
		header("Location: /");
	}
}
