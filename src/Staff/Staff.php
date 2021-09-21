<?php

namespace Staff;

use Exception;
use Serializable;

class Staff implements Serializable
{
	private $username;
	private $password;
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
			$value = $this->dbClient->select('login', where: "username='$this->username' AND password='$this->password'");
			// print_r($value);
			if ($value) {
				$_SESSION['login']['status'] = true;
				$this->loggedin = true;
				// serialize($this);
				return true;
			} else {
				$_SESSION['staff'] = null;
				$_SESSION['login']['status'] = !true;
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
			'loggedin' => @$this->loggedin
		]);
	}

	function unserialize($data)
	{
		$data = unserialize($data);
		$this->username = $data['username'];
		$this->password = $data['password'];
		$this->readwrite = $data['readwrite'];
		$this->accesslevel = $data['accesslevel'];
		$this->loggedin = $data['loggedin'];
	}
}
