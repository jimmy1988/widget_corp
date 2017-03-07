<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	//gets the admin id from the GET request
	$admin=find_admin_by_id($_GET["id"]);
	//if the admin does not exist or is not present perform a redirect
	if (!$admin){
		//admin ID was missing or valid or
		//admin couldnt be found in database
		redirect_to("manage_admins.php");
	}
	//append the id to the variable	
	$id=$admin["id"];
	//perform query
	$query = "DELETE FROM admins WHERE id={$id} LIMIT 1";
	//append the result
	$result = mysqli_query($connection, $query);
	
	//checks to see if the result was successful and the rows were successful
	if ($result && mysqli_affected_rows($connection) == 1){
		$_SESSION["message"]="Admin deleted.";
		redirect_to("manage_admins.php");
	}else{
			$_SESSION["message"]="Admin deletion failed.";
			redirect_to("manage_admins.php");
	}
?>