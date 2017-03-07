<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php 
	//confirms that a user is logged in
	confirm_logged_in();
?>
<?php 
	//find the currently selected page that you are on
	find_selected_page();
?>

<?php 
	//Can't add a new page unless we have a subject as a parent
	//verify that we have a subject
	if(!$current_subject){
		//subject ID was missing or invalid or
		//subject couldnt be found in the database
		redirect_to("manage_content.php");
	}
?>
<?php
	//checks to see if a POST request has been sent
	if(isset($_POST['submit'])){
		
		
		//validate the form
		$required_fields=array("menu_name","position","visible","content");
		validate_presences($required_fields);
		
		//checks the form for valid lengths
		$fields_with_max_lengths=array($menu_name=>30);
		validate_max_lengths($fields_with_max_lengths);
		
		//checks that there are no errors
		if(empty($errors)){
		
		//Process the form
			$subject_id=$current_subject["id"];
			$menu_name=mysql_prep($_POST["menu_name"]);
			$position=(int)$_POST["position"];
			$visible =(int)$_POST["visible"];
			//be sure to escape the content
			$content=mysql_prep($_POST["content"]);
			
			//Perform Query
			
			$query ="INSERT INTO pages (";
			$query.=" subject_id, menu_name, position, visible, content";
			$query.=") VALUES (";
			$query.=" {$subject_id}, '{$menu_name}', {$position}, {$visible}, '{$content}'";
			$query.=")";
			//appends the result to the variable
			$result=mysqli_query($connection,$query);
			
			//checks to see if the query was a success
			if ($result){
				//success
				$_SESSION["message"]="Page Created.";
				redirect_to("manage_content.php?subject="urlencode($current_subject["id"]));
			}else{
				//failure
				$message="Page creation failed.";
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
            	 <?php echo navigation($current_subject,$current_page);?>
            </div>
            <div id ="page">
            	<?php echo message();?>
				<?php echo form_errors($errors); ?>
                	<h2>Create Page</h2>
                    <form action="new_page.php?subject=<?php echo urlencode($current_subject["id"]); ?>" method="post">
                    	<p>Menu Name: 
							<input type="text" name="menu_name" value=""/>
						</p>
                        <p>Position: 
                            <select name="position">
                                <?php
									$page_set=find_pages_for_subject($current_subject["id"],false);
									$page_count=mysqli_num_rows($page_set);
									for ($count=1;$count<=$page_count;$count++){
										echo "<option value=\"{$count}\"></option>";
									}
                                ?>
                            </select>
                        </p>
                        <p> Visible:
                        	<input type="radio" name="visible" value="0"/>No &nbsp;
                            <input type="radio" name="visible" value="1"/>Yes
                        </p>
						<p>Content:<br/>
							<textarea name="content" rows="20" cols="80"></textarea>
						</p>
                        <input type="submit" name = "submit" value="Create Subject"/>
                    </form>
                    <br/>
                    <a href="manage_content.php?subject=<?php echo urlencode($current_subject["id"]); ?>">Cancel</a>
            </div>
        </div>
<?php include("../includes/Layouts/footer.php"); ?>