<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php 
	//confirms that a user is logged in
	confirm_logged_in(); 
?>

<?php
	//check to see if a POST request was sent
	if(isset($_POST['submit'])){
		
		
		//validate the form
		$required_fields=array("username","password");
		validate_presences($required_fields);
		
		//check the fields for lengths
		$fields_with_max_lengths=array("username"=>30);
		validate_max_lengths($fields_with_max_lengths);
		
		//checks to see if there were errors or not
		if(empty($errors)){
			
			//prepares the username and escapes all string that could be potentially harmfiul to the SQL query
			$username=mysql_prep($_POST["username"]);
			//encrypts the password and stores it in the database
			$hashed_password=password_encrypt($_POST["password"]);
			
			//Perform Query
			$query ="INSERT INTO admins (";
			$query.=" username, hashed_password";
			$query.=") VALUES (";
			$query.=" '{$username}', '{$hashed_password}'";
			$query.=")";
			//append the results to a variable
			$result=mysqli_query($connection,$query);
			
			//confirms that the query was a success
			if ($result){
				//success
				$_SESSION["message"]="Admin Created.";
				redirect_to("manage_admins.php");
			}else{
				//failure
				$message="Admin creation failed.";
			}
		}
	}else{
		//This is probably a GET request
		
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
                	<h2>Create Admin</h2>
                    <form action="new_admin.php" method="post">
                    	<p>Username:
							<input type="text" name="username" value="" />
						</p>
						<p>Password:
							<input type="password" name="password" value="" />
						</p>
							<input type="submit" name="submit" value="Create Admin" />
                    </form>
                    <br/>
                    <a href="manage_admins.php">Cancel</a>
            </div>
        </div>
<?php include("../includes/Layouts/footer.php"); ?>