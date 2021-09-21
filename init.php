<?php
session_start();

use Auth\Auth;
use Dotenv\Dotenv;
use Database\Database;

require 'src/autoload.php';
require 'vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

Auth::redirectOnFalse(Auth::confirmLogin(), '/login.php');
// Auth::confirmLogin();


try {
	$db = new Database();
	if (@!$_SESSION['staff']) {
		Auth::redirectOnFalse(false, '/login.php');
	} else {
		$staff = unserialize($_SESSION['staff']);
		$staff->setDBCLient($db);
		Auth::redirectOnFalse($staff->loggedin, '/login.php');
	}
	$db->connect();
} catch (Exception $e) {
	echo $e->getMessage();
}
