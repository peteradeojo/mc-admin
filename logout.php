<?php

use Auth\Auth;
use Logger\Logger;

require 'init.php';

print_r($staff);
Logger::message("Logged out");
$staff->logout();
Auth::logOut();

session_destroy();
