<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php 
	//gets and sets the admin id
	$admin=find_admin_by_id($_GET["id"]);
	//makes sure that the admin id was set properly
	if(!$admin){
		//admin ID was missing or invalid or
		//admin couldnt be found in the database
		redirect_to("manage_admins.php");
	}
?>
<?php
	//checks that a POST request was sent, if no request or a GET request was sent then nothing happens
	if(isset($_POST['submit'])){
		
		
		//validate the form
		$required_fields=array("username","password");
		validate_presences($required_fields);
		
		//check for lengths
		$fields_with_max_lengths=array("username"=>30);
		validate_max_lengths($fields_with_max_lengths);
		
		//checks that there are no errors
		if(empty($errors)){
			//Perform Create
		
		
			//Process the form
			$id=$admin["id"];
			$username=mysql_prep($_POST["username"]);
			$hashed_password=password_encrypt($_POST["password"]);
			
			//Perform Query
			$query ="UPDATE admins SET ";
			$query.="username='{$username}', ";
			$query.="hashed_password='{$hashed_password}' ";
			$query.="WHERE id = {$id} ";
			$query.="LIMIT 1";
			//appends the results to a result variable
			$result=mysqli_query($connection,$query);
			
			//checks that the query was successful and that the admin was created
			if ($result && mysqli_affected_rows($connection)==1){
				//admin creation successful
				$_SESSION["message"]="Admin Created.";
				redirect_to("manage_admins.php");
			}else{
				//failure
				$message="Admin creation failed.";
			}
		}
	}else{
		//This is probably a GET request
		//do nothing
		
	}
?>
<?php $layout_context="admin"; ?>
<?php include("../includes/Layouts/header.php"); ?>

        <div id = "main">
            <div id="navigation">
            	&nbsp;
            </div>
            <div id ="page">
            	<?php echo message();?>
				<?php echo form_errors($errors); ?>
                	<h2>Edit Admin: <?php echo htmlentities($admin["username"]); ?></h2>
                    <form action="edit_admin.php?id=<?php echo urlencode($admin["id"]); ?>" method="post">
                    	<p>Username: 
							<input type="text" name="username" value="<?php echo htmlentities($admin["username"]); ?>"/>
						</p>
                        <p>Password:
                            <input type="password" name="password" value=""/>
                        </p>
                        <input type="submit" name = "submit" value="Edit Admin"/>
                    </form>
                    <br/>
                    <a href="manage_admins.php">Cancel</a>
            </div>
        </div>
<?php include("../includes/Layouts/footer.php"); ?>