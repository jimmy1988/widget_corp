<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>

<?php
	//sets username to blank or an empty field
	$username="";
	//checks to see if the POST request was sent
	if(isset($_POST['submit'])){
		
		
		//validate the form
		$required_fields=array("username","password");
		validate_presences($required_fields);
		
		//check for errors
		if(empty($errors)){
			
			//Attempt Login
			$username=$_POST["username"];
			$password=$_POST["password"];
			$found_admin=attempt_login($username, $password);
			
			//if admin is a success then complete the log in
			if ($found_admin){
				//Success
				//Mark user as logged in
				$_SESSION["admin_id"]=$found_admin["id"];
				$_SESSION["username"]=$found_admin["username"];
				redirect_to("admin.php");
			}else{
				//failure
				$_SESSION["message"]="Username/Password not found.";
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
                	<h2>Login</h2>
                    <form action="login.php" method="post">
                    	<p>Username:
							<input type="text" name="username" value="<?php echo htmlentities($username); ?>" />
						</p>
						<p>Password:
							<input type="password" name="password" value="" />
						</p>
							<input type="submit" name="submit" value="Login" />
                    </form>
            </div>
        </div>
<?php include("../includes/Layouts/footer.php"); ?>