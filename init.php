<?php
session_start();

use Auth\Auth;
use Database\Builder;
use Dotenv\Dotenv;
use Database\Database;
use Database\Migration;
use Database\Query;
use Logger\Logger;

require 'src/autoload.php';
require 'vendor/autoload.php';
require 'src/functions.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

function runMigration()
{

	Migration::createTableIfNotExists('departments', function (Builder $table) {
		$table->id();
		$table->string('name', 40);
		$table->string('code', 3);
		$table->timestamps();
	});

	Migration::createTableIfNotExists('staff', function (Builder $table) {
		$table->id();
		$table->string('firstname', 20);
		$table->string('lastname', 20);
		$table->string('username');
		$table->string('phone_number', 16, false);
		$table->string('official_address', 50, false);
		$table->string('designation', 30);
		$table->integer('department', false);
		$table->foreign('department', 'departments');
		$table->timestamps();
	});

	Migration::createTableIfNotExists('login', function (Builder $table) {
		$table->id();
		$table->integer('staff_id');
		$table->foreign('staff_id', 'staff', 'CASCADE', 'RESTRICT');
		$table->string('password', 60);
		$table->boolean('readwrite')->default(false);
		$table->integer('accesslevel')->default(1);
		$table->boolean('active')->default(true);
		$table->timestamps();
	});

	Migration::createTableIfNotExists('categories', function (Builder $table) {
		$table->id();
		$table->string('name', 20);
		$table->string('short_code', 4);
		$table->unique('name');
		$table->timestamps();
	});

	Migration::createTableIfNotExists('patients', function (Builder $table) {
		$table->id();
		$table->string('hospital_number', 20);
		$table->string('name', 60);
		$table->integer('gender', false)->default(0);
		$table->integer('category_id', false)->default(1);
		$table->foreign('category_id', 'categories');
		$table->timestamps();
	});

	Migration::createTableIfNotExists('biodata', function (Builder $table) {
		$table->id();
		$table->integer('patient_id');
		$table->foreign('patient_id', 'patients', 'CASCADE', 'RESTRICT');
		$table->string('address', 50, false);
		$table->string('email_address', 50, false);
		$table->string('phone_number', 16, false);
		$table->string('occupation', 30, false);
		$table->string('marital_status', 10, false);
		$table->string('religion', 10, false);
	});

	Migration::createTableIfNotExists('vitals', function (Builder $table) {
		$table->id();
		$table->integer('patient_id');
		$table->foreign('patient_id', 'patients', 'CASCADE', 'RESTRICT');
		$table->integer('staff_id');
		$table->foreign('staff_id', 'staff', 'CASCADE', 'RESTRICT');
		$table->string('temp', 10, false);
		$table->integer('pulse', 10, false);
		$table->string('resp', 10, false);
		$table->string('bp', 10, false);
		$table->decimal('weight', 3, 2, false);
		$table->timestamps();
	});

	Migration::createTableIfNotExists('statuses', function (Builder $table) {
		$table->id();
		$table->string('title', 20);
		$table->unique('title');
		$table->timestamps();
	});

	Migration::createTableIfNotExists('waitlist', function (Builder $table) {
		$table->id();
		$table->integer('patient_id');
		$table->foreign('patient_id', 'patients', 'CASCADE', 'RESTRICT');
		$table->integer('staff_id');
		$table->foreign('staff_id', 'staff', 'CASCADE', 'RESTRICT');
		$table->integer('vital_id', false);
		$table->foreign('vital_id', 'vitals', 'CASCADE', 'RESTRICT');
		$table->integer('status_id')->default(1);
		$table->foreign('status_id', 'statuses');
		$table->timestamps();
	});
}

function alterVisitsTable()
{
	Migration::alterTable('visits', function (Builder $table) {
		$table->modify('admission_id', 'varchar(50)');
		$table->modify('lab_tests_id', 'varchar(50)');
		$table->modify('investigations_id', 'varchar(50)');
		$table->modify('review_id', 'varchar(50)');
	});
}

alterVisitsTable();

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
