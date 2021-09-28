<?php

namespace Auth;

class Auth
{
	static function confirmLogin()
	{
		$allowed_script = 'login.php';
		if (str_ends_with($_SERVER['SCRIPT_NAME'], $allowed_script)) return true;

		if (@$_SESSION['login'] !== true) {
			return false;
		}
		return true;
	}

	static function redirectOnFalse($value, string $location = '/login.php')
	{
		$allowed_script = 'login.php';
		if (str_ends_with($_SERVER['SCRIPT_NAME'], $allowed_script)) return true;

		if (!$value) {
			header("Location: $location");
		}
	}

	static function logout()
	{
		$_SESSION['login'] = false;
		header("Location: /");
	}

	static function authorize($userdata)
	{
		foreach ($userdata as $key => $value) {
			echo "$key: $value<br/>";
		}
	}
}
