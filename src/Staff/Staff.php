<?php

namespace Staff;

use Exception;
use Serializable;

class Staff implements Serializable
{
	private $username;
	private $password;
	private \Database\Database $dbClient;
	private $userdata;

	function __construct($username, $dbClient, $password = null)
	{
		$this->username = $username;
		$this->dbClient = $dbClient;

		if ($password) {
			$this->password = sha1($password);
		}
	}

	function load($data = null)
	{
		try {
			switch ($data) {
				case 'login':
					$data = $this->dbClient->select('login', where: "username='$this->username'")[0];
					break;
				default:
					$data = $this->dbClient->select('staff', where: "username='$this->username'")[0];
			}
			$this->data = $data;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	function update($data, $info = null)
	{

		switch ($info) {
			case 'login':
				try {
					$this->dbClient->update([
						'login' => [
							'password' => $this->password
						],
					], where: "username = '$this->username'");
				} catch (Exception $e) {
					throw new Exception($e->getMessage());
				}
				break;
			default:
				$this->data['firstname'] = @$data['firstname'] ? $data['firstname'] : $this->data['firstname'];
				$this->data['lastname'] = @$data['lastname'] ? $data['lastname'] : $this->data['lastname'];
				$this->data['designation'] = @$data['designation'] ? $data['designation'] : $this->data['designation'];
				$this->data['department'] = @$data['department'] ? $data['department'] : $this->data['department'];
				$this->data['phone_number'] = @$data['phone_number'] ? $data['phone_number'] : $this->data['phone_number'];

				try {
					$this->dbClient->update([
						'staff' => [
							'firstname' => $this->data['firstname'],
							'lastname' => $this->data['lastname'],
							'designation' => $this->data['designation'],
							'department' => $this->data['department'],
							'phone_number' => $this->data['phone_number']
						]
					], where: "id='{$this->data['id']}'");
				} catch (Exception $e) {
					print_r($e);
					throw new Exception($e->getMessage());
				}
		}
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
			$value = $this->dbClient->select('login', where: "username='$this->username' AND password='$this->password' and active=1")[0];
			if ($value) {
				$staffdata = $this->dbClient->select('staff', where: "username='$this->username'")[0];
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

	function getAccessLevel()
	{
		return $this->getUserdata()['access_level'];
	}

	function canWrite()
	{
		return $this->getUserdata()['write_access'] == 1;
	}

	// Get nav links for each staff department
	function getLinks()
	{
		switch ($this->getWorkspace()) {
			case 'ict':
				return [
					'Patients' => '/ict/patients.php',
					'Staff' => '/ict/staff.php',
					'Departments' => '/ict/departments.php',
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
			case 'phm':
				return [
					'Prescriptions' => '/phm/prescriptions.php',
				];
			case 'lab':
				return [
					'Pending Tests' => '/lab/pending_tests.php',
				];
		}
	}
}
