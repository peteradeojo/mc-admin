<?php
session_start();

use Auth\Auth;
use Database\Builder;
use Dotenv\Dotenv;
use Database\Database;
use Database\Migration;
use Logger\Logger;

require_once 'vendor/autoload.php';
require_once 'src/functions.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (@$_ENV['maintenance'] == 1) {
	echo "Under maintenance. Try again later";
	exit();
}

(function () {
	Migration::alterTable('visits', function (Builder $table) {
		$table->datetime('created_at')->default('current_timestamp')->add();
		$table->datetime('updated_at')->default('current_timestamp on update current_timestamp')->add();
	});
});

Auth::redirectOnFalse(Auth::confirmLogin());
try {
	$db = new Database();
	$db->connect();

	// require 'migrations.php';
	$staff = @unserialize($_SESSION['staff']);
	if (!@$staff->loggedin) {
		Auth::redirectOnFalse(false);
	} else {
		$staff->setDBCLient($db);
		$staff->authenticate() or header("Location: /logout.php");
		Logger::$staffName = $staff->getUsername();
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
