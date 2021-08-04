<?php
	//server setup
	$db_host = "localhost:3306";
	$db_username = "root";
	$db_password = "";
	$db_name = "phpmember";
	//connect db
	$db_link = @new mysqli($db_host, $db_username, $db_password, $db_name);
	//error
	if ($db_link->connect_error != "") {
		echo "database connect fail！";
	}else{
		//Set character set and encoding
		$db_link->query("SET NAMES 'utf8'");
	}
	$db_link->autocommit(TRUE);
?>