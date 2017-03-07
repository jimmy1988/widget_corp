<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	////gets the subject by its id from the GET request
	$current_subject=find_subject_by_id($_GET["subject"], false);
	
	//checks that the subject id has been set
	if (!$current_subject){
		//subject ID was missing or valid or
		//subject couldnt be found in database
		redirect_to("manage_content.php");
	}
	
	//finds all pages for a specified subject id
	$pages_set=find_pages_for_subject($current_subject["id"], false);
	
	//if a subject has pages, the delete query will not remove the subject
	//error message is generated and the user is redirected back to the manage content page 
	if(mysqli_num_rows($pages_set)>0){
		$_SESSION["message"]="Can't delete a subject with pages.";
		redirect_to("manage_content.php?subject={$current_subject["id"]}");
	}
	
	//sets the current subject id
	$id=$current_subject["id"];
	//perform the query
	$query = "DELETE FROM subjects WHERE id={$id} LIMIT 1";
	//append the results
	$result = mysqli_query($connection, $query);
	
	//check the results
	if ($result && mysqli_affected_rows($connection) == 1){
		$_SESSION["message"]="Subject deleted.";
		redirect_to("manage_content.php");
	}else{
			$_SESSION["message"]="Subject deletion failed.";
			redirect_to("manage_content.php?subject={$id}");
	}
?>