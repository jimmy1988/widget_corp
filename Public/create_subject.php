<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	//checks to see if a POST request has been sent, if not a GET request or no request was sent and therefore it is an invalid request
	if(isset($_POST['submit'])){
		
		//Process the form
		//prepare all strings for use
		$menu_name=mysql_prep($_POST["menu_name"]);
		$position=(int)$_POST["position"];
		$visible =(int)$_POST["visible"];
		
		//validate the form
		$required_fields=array("menu_name","position","visible");
		validate_presences($required_fields);
		
		$fields_with_max_lengths=array($menu_name=>30);
		validate_max_lengths($fields_with_max_lengths);
		
		//check for errors and display them(if any)
		if(!empty($errors)){
			$_SESSION["errors"]=$errors;
			redirect_to("new_subject.php");
		}
		
		//Perform Query against the database
		
		$query="INSERT INTO subjects (";
		$query.=" menu_name, position, visible";
		$query.=") VALUES (";
		$query.=" '{$menu_name}',{$position},{$visible}";
		$query.=")";
		
		//return the result
		$result=mysqli_query($connection,$query);
		
		//checks to see if the query was executed successfully
		if ($result){
			$_SESSION["message"]="Subject created.";
			redirect_to("manage_content.php");
		}else{
			$_SESSION["message"]="Subject creation failed.";
			redirect_to("new_subject.php");
		}
	}else{
		//This is probably a GET request
		redirect_to("new_subject.php");
	}
?>

<?php
	//close the connection, if this is set
	if (isset($connection)){ mysqli_close($connection);}
?>