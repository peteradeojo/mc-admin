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


try {
	$db = new Database();
	$db->connect();
	$staff = @unserialize($_SESSION['staff']);
} catch (Exception $e) {
	echo $e->getMessage();
}
