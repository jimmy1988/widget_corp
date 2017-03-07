<?php
	/*
	*/
?>
<?php
	//confirms whether the query was a success or failure
	function confirm_query($result_set){
		//Test if there was a query error
		if(!$result_set){
			//kills the database connection if there was a failure
			//if no error occured the query continues
			die("Database Query Failed");
		}
	}
	
	//takes all errors as an array and outputs them to the user as html output
	function form_errors($errors=array()){
		//output for the errors starts out blank and fills in if there are errors
		$output="";
		//tests to see if errors are not empty, if there are errors they are displayed to the user
		if(!empty($errors)){
			//start of div
			$output ="<div class = \"error\">";
			//error message
			$output.="Please fix the following errors:";
			//start the list of errors
			$output.="<ul>";
			foreach($errors as $key => $error){
				$output.="<li>";
				$output.=htmlentities($error);
				$output.="</li>";
			}
			//end of list
			$output.="</ul>";
			//end of div
			$output.="</div>";
		}
		//returns the output as a string
		return $output;
	}
	
	//returns all subject from the database
	//by default public is set to true by default, this ensures that no invisible subjects are displayed to the user
	//only an admin user can view all visible and invisible subjects
	function find_all_subjects($public=true){
		//imports the connection from the global scope
		global $connection;
		//start of query
		$query = "SELECT * FROM subjects ";
		//if public is true then add an extra expression to the query
		if ($public){
			$query.="WHERE visible = 1 ";
		}
		//orders all subject in ascending order
		$query.= "ORDER BY position ASC";
		$subject_set = mysqli_query($connection,$query);
		
		//confirms that the query was successful, taking in the query as a parameter
		confirm_query($subject_set);
		//returns the result
		return $subject_set;
	}
	
	//finds all pages that relate to a particular subject
	function find_pages_for_subject($subject_id, $public=true){
		//imports the connection from the global scope
		global $connection;
		
		//uses real escape string to prevent against any sql injection attacks and makes the subject id a safe string to use in the query
		$safe_subject_id=mysqli_real_escape_string($connection,$subject_id);
		//start of query
		$query = "SELECT * FROM pages ";
		$query.= "WHERE subject_id = {$safe_subject_id} ";
		//add extra expression if we in the public area
		if ($public){
			$query.= "AND visible = 1 ";
		}
		//sort the results in ascending order
		$query.= "ORDER BY position ASC";
		//stores the results as a variable
		$page_set = mysqli_query($connection,$query);
		//confirms that the query was a success			
		confirm_query($page_set);
		//returns the results
		return $page_set;
	}
	//navigation takes 2 arguments
	//-the currently current subject array or null
	//-the currently current page array or null
	//this is the admin navigation
	function navigation($subject_array, $page_array){
		//start of the navigation
		$output = "<ul class = \"subjects\">";
		
		//find all subjects in the database
		$subject_set=find_all_subjects(false);
		//while there are subjects in the associative array get the pages for each individual subject
		while($subject=mysqli_fetch_assoc($subject_set)){
		//output each row 
			$output.= "<li";
			
			//if the subject array and the subject id is equal to the subject arrays id then mark it as selected, if not then do nothing
			if($subject_array && $subject["id"]==$subject_array["id"]){
				$output.= " class=\"selected\"";
			}
			$output.= ">";
			//outputs the links and constructs the URLS for the subjects
        	$output.= "<a href=\"manage_content.php?subject=";
			$output.= urlencode($subject["id"]);
            $output.= "\">"; 
			//outputs the individual subject name
            $output.= htmlentities($subject["menu_name"]);
			//close of the anchor tag
			$output.= "</a>"; 
			
			//gets all pages for a specific subject
			$page_set=find_pages_for_subject($subject["id"], false);
			$output.= "<ul class = \"pages\">";
             while($page=mysqli_fetch_assoc($page_set)){
				//output each row       			
				$output.= "<li";
				if($page_array && $page["id"]==$page_array["id"]){
					$output.= " class =\"selected\"";
				}
				$output.= ">"; 
                $output.= "<a href=\"manage_content.php?page=";
				$output.= urlencode($page["id"]);
				$output.="\">";
				$output.= htmlentities($page["menu_name"]);
				$output.="</a></li>";
              }
              //4. Release the data, frees the result
			  mysqli_free_result($page_set);
               $output.="</ul></li>";
            }
            //4. Release the data, free the result
			mysqli_free_result($subject_set);
			$output.= "</ul>";
			//output the data
			return $output;
	}
	
	//outputs the navigation in the public area
	function public_navigation($subject_array, $page_array){
		//start the output string
		$output = "<ul class = \"subjects\">";
		//find all subjects in the database
		$subject_set=find_all_subjects();
		while($subject=mysqli_fetch_assoc($subject_set)){
		//output each row 
			$output.= "<li";
			//find the currently selcted subject(if any)
			if($subject_array && $subject["id"]==$subject_array["id"]){
				$output.= " class=\"selected\"";
			}
			$output.= ">"; 
        	$output.= "<a href=\"index.php?subject=";
			$output.= urlencode($subject["id"]);
            $output.= "\">"; 
            $output.= htmlentities($subject["menu_name"]);
			$output.= "</a>"; 
			
			//creates the accordian effect in the navigation menu, opening up all items in the currently selected subject
			if (($subject_array["id"]==$subject["id"]) || ($page_array["subject_id"]==$subject["id"])){
				
				//finds all pages to do with the selected subject and displays them in the navigation page
				$page_set=find_pages_for_subject($subject["id"]);
				$output.= "<ul class = \"pages\">";
				 while($page=mysqli_fetch_assoc($page_set)){
					//output each row       			
					$output.= "<li";
					//finds the currently selected page
					if($page_array && $page["id"]==$page_array["id"]){
						$output.= " class =\"selected\"";
					}
					$output.= ">"; 
					$output.= "<a href=\"index.php?page=";
					$output.= urlencode($page["id"]);
					$output.="\">";
					$output.= htmlentities($page["menu_name"]);
					$output.="</a></li>";
				  }
				  //4. Release the data
				  mysqli_free_result($page_set);
				   $output.="</ul></li>";
			}
        }
            //4. Release the data
			mysqli_free_result($subject_set);
			$output.= "</ul>";
			return $output;
	}
	
	//finds a particular subject by its ID
	function find_subject_by_id($subject_id, $public=true){
		//imports the connection from the global scope
		global $connection;
		
		//makes the subject ID safe from SQL injection
		$safe_subject_id=mysqli_real_escape_string($connection,$subject_id);
		//start of query
		$query = "SELECT * FROM subjects ";
		$query .= "WHERE id = {$safe_subject_id} ";
		//add a visiblity expression for the public area
		if ($public){
			$query .="AND visible = 1 ";
		}
		$query .= "LIMIT 1";
		//queries the database and retrieves a result
		$subject_set = mysqli_query($connection,$query);
		
		//confirms that the query worked
		confirm_query($subject_set);
		//places the result into an associative array and outputs each individual subject
		//this is done while the array has items, if a subjects exists it returns the subject, if not it returns the null
		if($subject=mysqli_fetch_assoc($subject_set)){
			return $subject;
		}else{
			return null;
		}
		
	}
	
	//finds each page by its individual id
	function find_page_by_id($page_id, $public=true){
		//imports the connection from the global scope
		global $connection;
		
		//prevents against sql inject attempts
		$safe_page_id=mysqli_real_escape_string($connection,$page_id);
		//start query
		$query = "SELECT * FROM pages ";
		$query .= "WHERE id = {$safe_page_id} ";
		//adds extra expression if in the public area
		if ($public){
			$query .="AND visible = 1 ";
		}
		$query .= "LIMIT 1";
		//queries the database
		$page_set = mysqli_query($connection,$query);
		
		//confirms that the query was successful
		confirm_query($page_set);
		//places the results of the query into an associative array and echos them back
		if($page=mysqli_fetch_assoc($page_set)){
			return $page;
		}else{
			return null;
		}
		
	}
	
	//gets the default page for the subject
	function find_default_page_for_subject($subject_id){
		//find all pages for the subject and store them in an array
		$page_set=find_pages_for_subject($subject_id);
		//returns the first page from the pages associative array
		if($first_page=mysqli_fetch_assoc($page_set)){
			return $first_page;
		}else{
			return null;
		}
	}
	
	//finds and displays a selected page
	//Public is set to false by default as this is the lowest privelege available in the system
	//The public variable can be changed to true if it is passed in as a parameter
	function find_selected_page($public=false){
		//imports the current subject and page from the global scope
		global $current_subject;
		global $current_page;
		
		//checks to see if the GET request for subject is set
		if(isset($_GET["subject"])){
			//finds the current subject by its id through the get request
			//sets the layout context of public to false, to specify this is for the admin area
			$current_subject =find_subject_by_id($_GET["subject"], $public);
			//checks to see if the current subject is selected and are in the public area, if so then we grab all pages for that particular subject
			if($current_subject && $public){
				$current_page=find_default_page_for_subject($current_subject["id"]);
			}else{
				//if not we return null
				$current_page=null;
			}
		//checks if the GET request for the page is set	
		}elseif(isset($_GET["page"])){
			//gets the current page by its id through the id specified in the GET request
			//the layout context of public to false, this is to indicate that this is for the admin area
			$current_page =find_page_by_id($_GET["page"],$public);
			$current_subject =null;
		//if neither are set then either its a POST request that cannot be recognised or a request has not been sent
		}else{
			//sets both current page and subject to empty
			$current_subject =null;
			$current_page=null;
		}
	}
	//redirects to a selected page
	function redirect_to($new_location){
		//specifies where to redirect to
		header("Location:". $new_location);
		//exit the script
		exit;
	}
	
	//prepares the string ready to be inserted into the database by escaping all characters that could commit harmful consequences to the database
	//takes in a string as a parameter
	function mysql_prep($string){
		//import the connection from the global scope
		global $connection;
		$escaped_string=mysqli_real_escape_string($connection,$string);
		return $escaped_string;
	}
	//finds all admin users registered in the database and returns them as a result
	function find_all_admins(){
		//import the connection from the global scope
		global $connection;
		
		//start of the query
		$query  = "SELECT * ";
		$query .= "FROM admins ";
		$query .= "ORDER BY username ASC";
		$admin_set=mysqli_query($connection, $query);
		//confirms that the query was a success
		confirm_query($admin_set);
		//returns the result
		return $admin_set;
	}
	
	//finds an admin user by their ID
	function find_admin_by_id($admin_id){
		//imports the connection from the global
		global $connection;
		//makes the incoming id safe to use in a query
		$safe_admin_id=mysqli_real_escape_string($connection, $admin_id);
		//start of query
		$query  = "SELECT * ";
		$query .= "FROM admins ";
		$query .= "WHERE id = {$safe_admin_id} ";
		$query .= "LIMIT 1";
		//appends the result of the query to a variable
		$admin_set = mysqli_query($connection, $query);
		//confirms that the query was a success
		confirm_query($admin_set);
		//returns all admin users to the application
		if ($admin = mysqli_fetch_assoc($admin_set)){
			return $admin;
		}else{
			return null;
		}
	}
	
	//function to encrypt all passwords 
	function password_encrypt($password){
		$hash_format = "$2y$10$"; //Tells PHP to use Blowfish with a cost of 10
		$salt_length = 22; //Specifies the length to encrypt to, Blowfish salts should be 22 characters or more
		//Generate a salt string of length 22 and pass it into the variable
		$salt = generate_salt($salt_length);
		//appends the has format and salt string to the variable
		$format_and_salt = $hash_format.$salt;
		//encrypts the password
		$hash = crypt($password, $format_and_salt);
		//returns the encrypted password
		return $hash;
	}
	
	function generate_salt($length){
		//this entire function makes sure that the salt is at the right length
		//and base 64 encoded
	
		//Not 100% unique, not 100% random, but good enough for a salt
		//MD5 returns 32 characters
		
		//uses the md5 hashing algorithm to generate a unique id using the mt_rand() function, true tells the algorithm to be longer to be more secure
		$unique_random_string = md5(uniqid(mt_rand(),true));
		
		//Valid characters for a salt are [a-z, A-Z, 0-9./]
		//Encodes the salt to base 64 encode
		$base64_string=base64_encode($unique_random_string);
		
		//But not '+' which has to be base64 encoding
		$modified_base64_string=str_replace('+',".", $base64_string);
		
		//Truncate string to the correct length
		$salt=substr($modified_base64_string,0, $length);
		
		//returns the salt string
		return $salt;
	}
	
	//checks that all passwords match, this happens encrypting the incoming password and checking it against the existing one
	function password_check($password, $existing_hash){
		//existing hash contains format and salt at start
		$hash = crypt($password,$existing_hash);
		//checks the encrypted password attempt against the one that is encrypted in the database
		if ($hash===$existing_hash){
			//password matches
			return true;
		}else{
			//password does not match
			return false;
		}
	}
	
	//attempts user login to the system
	function attempt_login($username,$password){
		//gets the username from the database
		$admin=find_admin_by_username($username);
		
		if ($admin){
			//found admin, now check password
			if (password_check($password,$admin["hashed_password"])){
				return $admin;
			}
		}else{
			//admin not found
			return false;
		}
	}
	//finds an admin by username
	//takes in the admin id as a parameter
	function find_admin_by_username($admin_id){
		//imports the connection from the global scope
		global $connection;
		
		//escapes all string that could be harmful to the database
		$safe_username=mysqli_real_escape_string($connection, $admin_id);
		
		//start query
		$query  = "SELECT * ";
		$query .= "FROM admins ";
		$query .= "WHERE username = '{$safe_username}' ";
		$query .= "LIMIT 1";
		
		//place the results of the query into a variable to test
		$admin_set = mysqli_query($connection, $query);
		//confirms that the query was a success
		confirm_query($admin_set);
		//returns all admins from the query
		if ($admin = mysqli_fetch_assoc($admin_set)){
			return $admin;
		}else{
			return null;
		}
	}
	//checks if a user is logged in
	function logged_in(){
		return isset($_SESSION['admin_id']);
	}
	//confirms that a user is logged in
	//if no user is logged in then we redirect to the  login page
	function confirm_logged_in(){
		if(!logged_in()){
			redirect_to("login.php");
		}
	}
?>