<?php

spl_autoload_register(function ($path) {
	$root = str_replace("/", DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT'] . "/src/" . "$path.php");
	// echo $path;
	require $root;
});
