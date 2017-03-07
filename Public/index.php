<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php include("../includes/functions.php"); ?>
<?php include("../includes/Layouts/header.php"); ?>
<?php find_selected_page(true); ?>
        <div id = "main">
            <div id="navigation">
				<br/>
			<a href="login.php" onclick="return confirm('Admin Area Is for Authorised Users Only, Do You Wish To Continue?');">&laquo;Admin Area</a><br/>
            	 <?php echo public_navigation($current_subject,$current_page);?>
            </div>
            <div id ="page">
                	<?php if ($current_page) { ?>
						<h2><?php echo htmlentities($current_page["menu_name"]); ?></h2>
						<?php echo nl2br(htmlentities($current_page["content"]));?>
					<?php } else {?>
						<p>Welcome!</p>
					<?php }?>
					
            </div>
        </div>
<?php include("../includes/Layouts/footer.php"); ?>