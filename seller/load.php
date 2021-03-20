<?php

	session_name("SELLER");
	session_start();
	
	define("ABSPATH", dirname(__FILE__));
	require_once "autoload.php";

	$database = new database();
	$pdo = $database->connect();

	$product = new product($pdo);
	$catalog = new catalog($pdo);
	


?>
