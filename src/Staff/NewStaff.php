<?php

namespace Staff;

use Exception;
use Database\Database;

class NewStaff
{
	private $firstname;
	private $lastname;
	private $department;
	private $designation;
	private $password;
	private $username;
	private $phone;
	private \Database\Database $dbClient;

	function __construct($data, Database $dbClient)
	{
		$this->firstname = $data['firstname'];
		$this->lastname = $data['lastname'];
		$this->designation = $data['designation'];
		$this->department = $data['department'];
		$this->password = sha1($data['password']);
		$this->username = $data['username'];
		$this->phone = $data['phone_number'];

		$this->dbClient = $dbClient;
	}

	function save()
	{
		try {
			$this->dbClient->insert([
				'staff' => [
					'firstname' => $this->firstname,
					'lastname' => $this->lastname,
					'username' => $this->username,
					'designation' => $this->designation,
					'department' => $this->department,
					'phone_number' => $this->phone,
				],
				'login' => [
					'username' => $this->username,
					'password' => $this->password,
				]
			]);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	function getUsername() {
		return $this->username;
	}
}
