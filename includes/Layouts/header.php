<?php
	if (!isset($layout_context)){
		$layout_context="public";
	}
?>

<!doctype html>
<html>
    <head>
    <meta charset="utf-8">
    <title>Widget Corp <?php if($layout_context == "admin") {echo "Admin"; }?></title>
    <link type="text/css" media="all" href="css/public.css" rel="stylesheet"/>
    </head>

    <body>
    	<div id ="header">
        	<h1>Widget Corp <?php if($layout_context == "admin") {echo "Admin"; }?></h1>
        </div>