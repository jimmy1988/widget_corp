<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php find_selected_page(); ?>

<?php 
	//unlike new_page.php, we don't need a subject_id to be sent
	//We already have it stored in pages.subject_id
	
	//we still need to test whether the current page was set though
	if(!$current_page){
		//subject ID was missing or invalid or
		//subject couldnt be found in the database
		redirect_to("manage_content.php");
	}
?>
			
<?php
	//checks that the request that was sent was a post request
	if(isset($_POST['submit'])){
		
		//Process the form
		$id=$current_subject["id"];
		$menu_name=mysql_prep($_POST["menu_name"]);
		$position=(int)$_POST["position"];
		$visible =(int)$_POST["visible"];
		$content=mysql_prep($_POST["content"]);
			
		//validate the form
		$required_fields=array("menu_name","position","visible", "content");
		validate_presences($required_fields);
		
		//validate that all fields
		$fields_with_max_lengths=array($menu_name=>30);
		validate_max_lengths($fields_with_max_lengths);
		
		//checks to see if there are any errors in the form, if not then proceed with the query
		if(empty($errors)){
			//Perform Query
			
			$query ="UPDATE pages SET ";
			$query.="menu_name = '{$menu_name}', ";
			$query.="position={$position}, ";
			$query.="visible={$visible}, ";
			$query.="content='{$content}' ";
			$query.="WHERE id = {$id} ";
			$query.= "LIMIT 1";
			//append the results to a variable
			$result=mysqli_query($connection,$query);
			
			//checks to see if the update was successful
			if ($result && mysqli_affected_rows($connection) >=0){
				$_SESSION["message"]="Page updated.";
				redirect_to("manage_content.php?page={$id}");
			}else{
				$message="Page update failed.";
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
				<?php echo message(); ?>
				<?php echo form_errors($errors); ?>
				
                	<h2>Edit Page: <?php echo htmlentities($current_page["menu_name"]); ?></h2>
                    <form action="edit_page.php?page=<?php echo urlencode($current_page["id"]); ?>" method="post">
                    	<p>Menu Name: 
							<input type="text" name="menu_name" value="<?php echo htmlentities($current_page["menu_name"]); ?>"/>
						</p>
                        <p>Position: 
                            <select name="position">
                                <?php
									$page_set=find_pages_for_subject($current_page["subject_id"],false);
									$page_count=mysqli_num_rows($page_set);
									for ($count=1;$count<=$page_count;$count++){
										echo "<option value=\"{$count}\"";
										if ($current_page["position"] == $count){
											echo " selected";
										}
										echo ">{$count}</option>";
									}
                                ?>
                            </select>
                        </p>
                        <p> Visible:
                        	<input type="radio" name="visible" value="0"<?php if ($current_page["visible"]==0){ echo "checked"; }?>/>No &nbsp;
                            <input type="radio" name="visible" value="1"<?php if ($current_page["visible"]==1){ echo "checked"; }?>/>Yes
                        </p>
						<p>Content: <br/>
							<textarea name="content" rows="20" cols="80"><?php echo htmlentities($current_page["content"]);?></textarea>
						</p
                        <input type="submit" name = "submit" value="Edit Page"/>
                    </form>
                    <br/>
                    <a href="manage_content.php?page=<?php echo urlencode($current_page["id"]); ?>">Cancel</a>
					&nbsp;
					&nbsp;
					<a href="delete_page.php?page=<?php echo urlencode($current_page["id"]); ?> " onClick="return confirm('Are You Sure?');">Delete Page</a>
            </div>
        </div>
<?php include("../includes/Layouts/footer.php"); ?>