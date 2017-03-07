<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php
	//confirms that the user is logged in, as only an administrator can view this page
	confirm_logged_in(); 
?>

<?php 
	//confirms that this is part of the admin section and security must be heavily implemented
	$layout_context="admin"; 

?>
<?php include("../includes/Layouts/header.php"); ?>
<?php find_selected_page(); ?>
        <div id = "main">
            <div id="navigation">
            	 <?php 
					//displays the navigation on the page as to how it is displayed in the database
					echo navigation($current_subject,$current_page);
				?>
            </div>
            <div id ="page">
            	<?php echo message();?>
				<?php $errors = errors(); ?>
				<?php echo form_errors($errors); ?>
                	<h2>Create Subject</h2>
                    <form action="create_subject.php" method="post">
                    	<p>Subject Name: <input type="text" name="menu_name" value=""/></p>
                        <p>Position: 
                            <select name="position">
                                <?php
									$subject_set=find_all_subjects(false);
									$subject_count=mysqli_num_rows($subject_set);
									for ($count=1;$count<=$subject_count+1;$count++){
										echo "<option value=\"{$count}\">{$count}</option>";
									}
                                ?>
                            </select>
                        </p>
                        <p> Visible:
                        	<input type="radio" name="visible" value="0"/>No &nbsp;
                            <input type="radio" name="visible" value="1"/>Yes
                        </p>
                        <input type="submit" name = "submit" value="Create Subject"/>
                    </form>
                    <br/>
                    <a href="manage_content.php">Cancel</a>
            </div>
        </div>
<?php include("../includes/Layouts/footer.php"); ?>