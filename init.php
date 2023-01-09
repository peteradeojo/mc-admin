<?php
session_start();

use Auth\Auth;
use Database\Builder;
use Dotenv\Dotenv;
use Database\Database;
use Database\Migration;
use Database\Query;
use Logger\Logger;

require 'vendor/autoload.php';
require 'src/autoload.php';
require 'src/functions.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// (function () {
// 	Migration::createTableIfNotExists('antenatal_sessions', function (Builder $table) {
// 		$table->id();
// 		$table->integer('patient_id');
// 		$table->foreign('patient_id', 'biodata');

// 		$table->date('flmp', false);
// 		$table->date('edd', false);

// 		$table->string('delivery_status', 30, false);
// 		$table->timestamps();
// 	});

// 	Migration::createTableIfNotExists('anc_session_personal_history', function (Builder $table) {
// 		$table->id();
// 		$table->integer('anc_session_id');
// 		$table->foreign('anc_session_id', 'antenatal_sessions');

// 		$table->boolean('chest_disease', false)->default(false);
// 		$table->boolean('kidney_disease', false)->default(false);
// 		$table->boolean('blood_transfusion', false)->default(false);
// 		$table->boolean('operation_no_cs', false)->default(false);
// 		$table->string('others', 255, false)->default('');
// 		$table->timestamps();
// 	});

// 	Migration::createTableIfNotExists('anc_session_family_history', function (Builder $table) {
// 		$table->id();
// 		$table->integer('anc_session_id');
// 		$table->foreign('anc_session_id', 'antenatal_sessions');

// 		$table->boolean('multiple_births', false)->default(false);
// 		$table->string('multiple_births_relation', 30, false);
// 		$table->boolean('hypertension', false)->default(false);
// 		$table->string('hypertension_relation', 30, false);
// 		$table->boolean('tuberculosis', false)->default(false);
// 		$table->string('tuberculosis_relation', 30, false);
// 		$table->boolean('heart_diseases', false)->default(false);
// 		$table->string('heart_disease_relation', 30, false);
// 		$table->string('others', 100, false);
// 		$table->string('others_relations', 100, false);
// 		$table->timestamps();
// 	});

// 	Migration::createTableIfNotExists('anc_session_objective_history', function (Builder $table) {
// 		$table->id();
// 		$table->integer('anc_session_id');
// 		$table->foreign('anc_session_id', 'antenatal_sessions');

// 		$table->integer('gravidity');
// 		$table->integer('parity');
// 		$table->timestamps();
// 	});

// 	Migration::createTableIfNotExists('anc_session_pregnancy_history', function (Builder $table) {
// 		$table->id();
// 		$table->integer('anc_session_id');
// 		$table->foreign('anc_session_id', 'antenatal_sessions');

// 		$table->date('date_of_delivery', false);
// 		$table->string('delivery_type', 30, false);
// 		$table->string('delivery_place', 30, false);
// 		$table->string('duration_of_pregnancy', 20, false);
// 		$table->integer('living_status', false)->default(1);
// 		$table->string('plp', 20, false);
// 		$table->boolean('gender', false)->default(true);

// 		$table->timestamps();
// 	});

// 	Migration::createTableIfNotExists('anc_session_current_history', function (Builder $table) {
// 		$table->id();
// 		$table->integer('anc_session_id');
// 		$table->foreign('anc_session_id', 'antenatal_sessions');

// 		$table->decimal('weight', 5, 2, false);
// 		$table->decimal('height', 5, 2, false);
// 		$table->string('bp', 9, false);

// 		$table->string('vaginal_bleeding', 20, false);
// 		$table->string('vaginal_discharge', 20, false);
// 		$table->string('urinary_symptoms', 20, false);
// 		$table->string('leg_swelling', 20, false);
// 		$table->string('other_symptoms', 60, false);

// 		$table->timestamps();
// 	});
// });

// (function () {
// 	Migration::alterTable('antenatal_sessions', function (Builder $table) {
// 		$table->modify('delivery_status', 'int not null default 0');
// 	});
// });

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
