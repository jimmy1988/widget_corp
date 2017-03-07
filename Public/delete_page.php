<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	//gets the current page id and appends it to the variable
	$current_page=find_page_by_id($_GET["page"],false);
	//if no current page exists then perform redirect, if exists then proceed
	if (!$current_page){
		//subject ID was missing or valid or
		//subject couldn't be found in database
		redirect_to("manage_content.php");
	}
	
	/* $pages_set=find_pages_for_subject($current_subject["id"],false);
	if(mysqli_num_rows($pages_set)>0){
		$_SESSION["message"]="Can't delete a subject with pages.";
		redirect_to("manage_content.php?subject={$current_subject["id"]}");
	}
	 */
	 //aquires the page id from the get request
	$id=$current_page["id"];
	//executes the query
	$query = "DELETE FROM pages WHERE id={$id} LIMIT 1";
	//append the results of the query
	$result = mysqli_query($connection, $query);
	
	//checks that the query was executed successfully and that only one row was effected
	if ($result && mysqli_affected_rows($connection) == 1){
		//echoes a successful message to user followed by a redirect
		$_SESSION["message"]="Page deleted.";
		redirect_to("manage_content.php");
	}else{
			//echoes a failed message and redirects back to the previous page
			$_SESSION["message"]="Page deletion failed.";
			redirect_to("manage_content.php?page={$id}");
	}
?>