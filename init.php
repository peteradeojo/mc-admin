<?php
session_start();

use Auth\Auth;
use Dotenv\Dotenv;
use Database\Database;

require 'src/autoload.php';
require 'src/functions.php';
require 'vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

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
