<?php //1. Open a connection to the database

	//define constants to connect to the database
	define("DB_SERVER", "localhost");//database server to use
	define("DB_USER", "widget_cms");// username to access the database
	define("DB_PASS","secretpassword"); //the password to access the database
	define("DB_NAME","widget_corp"); //the name of the database
	//establishes a connection to the database
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME); //try to establish connection
	//Test Connection, if failed, return error message
	if (mysqli_connect_errno()){
		die("Database connection failed:" ."(".mysqli_connect_errno().")");
	}
?>