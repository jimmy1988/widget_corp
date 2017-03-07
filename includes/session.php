<?php
	//start the session
	session_start();
	
	//outputs a message to the interface
	function message(){
		//if the session message variable exists display the message
		if(isset($_SESSION["message"])){ 
			$output= "<div class=\"message\">";
			$output.=htmlentities($_SESSION["message"]);
			$output.= "</div>";
			
			//once all messages have been placed in output, we free the variable
			$_SESSION["message"]=null;
			//return the output
			return $output;
		}
	}
	//display all errors that have been produced (if any)
	function errors(){
		//checks to see if the errors property in the session has been set
		if (isset($_SESSION["errors"])){
			//transfers all errors to the variable
			$errors=($_SESSION["errors"]);
			
			//clear message after use
			$_SESSION["errors"]=null;
			
			//return the errors
			return $errors;
		}
	}
?>