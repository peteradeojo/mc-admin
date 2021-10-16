<?php
session_start();

use Auth\Auth;
use Dotenv\Dotenv;
use Database\Database;

require 'src/autoload.php';
require 'vendor/autoload.php';
require 'src/functions.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (@$_ENV['maintenance'] == 1) {
	echo "Under maintenance. Try again later";
	exit();
}

Auth::redirectOnFalse(Auth::confirmLogin());

try {
	$db = new Database();
	$db->connect();
	$staff = @unserialize($_SESSION['staff']);
	if (!@$staff->loggedin) {
		Auth::redirectOnFalse(false);
	} else {
		$staff->setDBCLient($db);
		$staff->authenticate();
		$staff->restrictWorkspace();
	}
} catch (Exception $e) {
	echo $e->getMessage();
}

if (!str_ends_with($_SERVER['SCRIPT_NAME'], 'logout.php') and !str_ends_with($_SERVER['SCRIPT_NAME'], 'login.php')) {
	if (!$staff->getUserdata()['read_access']) {
		echo "You're not authorized to view this resource. Please contact the IT administrator. <a href='/logout.php'>Log Out</a>";
		exit();
	}
}
