<?php

use Auth\Auth;

require 'init.php';

print_r($staff);
$staff->logout();
Auth::logOut();