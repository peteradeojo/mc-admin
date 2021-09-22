<?php

namespace Staff;

use Exception;
use Serializable;

class Staff implements Serializable
{
	private $username;
	private $password;
	private $userdata;
	private \Database\Database $dbClient;

	function __construct($username, $password, $dbClient)
	{
		$this->username = $username;
		$this->password = sha1($password);
		$this->dbClient = $dbClient;
	}

	function setDBCLient($dbClient)
	{
		$this->dbClient = $dbClient;
	}

	function getUsername()
	{
		return $this->username;
	}

	function authenticate()
	{
		try {
			$value = $this->dbClient->select('login', where: "username='$this->username' AND password='$this->password' and active=1");
			if ($value) {
				$staffdata = $this->dbClient->select('staff', where: "username='$this->username'");
				if (!$staffdata) {
					throw new Exception('Staff data could not be retrieved');
				}
				$userdata = array_merge($value, $staffdata);
				$_SESSION['login'] = true;
				$this->userdata = $userdata;
				$this->loggedin = true;
				return true;
			} else {
				$_SESSION['staff'] = null;
				$_SESSION['login'] = !true;
				$this->loggedin = !true;
				return false;
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	function logout()
	{
		$this->loggedin = false;
	}

	function serialize()
	{
		return serialize([
			'username' => $this->username,
			'password' => $this->password,
			'readwrite' => @$this->readwrite,
			'accesslevel' => @$this->accesslevel,
			'loggedin' => @$this->loggedin,
			'userdata' => @$this->userdata
		]);
	}

	function unserialize($data)
	{
		$data = unserialize($data);
		$this->username = $data['username'];
		$this->password = $data['password'];
		$this->readwrite = $data['userdata']['readwrite'];
		$this->accesslevel = $data['userdata']['accesslevel'];
		$this->loggedin = $data['loggedin'];
		$this->userdata = $data['userdata'];
	}

	function getUserdata()
	{
		return $this->userdata;
	}

	function getWorkspace()
	{
		return $this->userdata['department'];
	}

	function restrictWorkspace()
	{
		$allowed_space = str_replace('/', DIRECTORY_SEPARATOR, '/' . $this->getWorkspace());
		$allowed_space = str_replace('/', DIRECTORY_SEPARATOR, $allowed_space);

		$test_script_name = str_replace('/', DIRECTORY_SEPARATOR, $_SERVER['SCRIPT_NAME']);
		$test_script_name = str_replace('\\', DIRECTORY_SEPARATOR, $test_script_name);
		if (!str_starts_with($test_script_name, $allowed_space)) {
			header("Location: $allowed_space");
		}
	}

	function getLinks()
	{
		switch ($this->getWorkspace()) {
			case 'ict':
				return [
					'Patients' => '/ict/patients.php',
					'Staff' => '/ict/staff.php',
				];
				break;
			case 'rec':
				return [
					'Patients' => '/rec/patients.php',
					'Waitlist' => '/rec/waitlist.php'
				];
				break;
			case 'nur':
				return [
					'Waitlist' => '/nur/waitlist.php',
					'Admissions' => '/nur/admissions.php',
					'Report' => '/nur/report.php'
				];
				break;
			case 'doc':
				return [
					// 'Waiting' => '/doc/waitlist.php',
					'Reviews' => '/doc/reviews.php',
					'Admissions' => '/doc/admissions.php'
				];
		}
	}
}
