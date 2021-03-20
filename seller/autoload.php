<?php

	if ( !defined("ABSPATH") ) exit();
	
	date_default_timezone_set("Asia/Jakarta");
	
	require_once "config.php";

	function autoload($class) {
	    include "classes/$class.php";
	}
	spl_autoload_register("autoload");
