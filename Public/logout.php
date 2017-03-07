<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php
	/* //v1:simple logout
	//session_start();
	$_SESSION['admin_id']=null;
	$_SESSION['username']=null;
	redirect_to("login.php"); */
	
	//v2: destroy session
	//assumes nothing else in session to keep
	
	//start the session
	session_start();
	$_SESSION=array();//clears all values from the session
	
	//checks to see if the cookie fpor the session name exists
	if (isset($_COOKIE[session_name()])){
		//if it still exists, set cookie to nothing and expire it
		setcookie(session_name(),'',time()-4200,'/');
	}
	
	//destroys session
	session_destroy();
	//redirects page
	redirect_to("login.php");
	
?>