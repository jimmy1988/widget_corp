<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_widget_cms = "localhost";
$database_widget_cms = "widget_corp";
$username_widget_cms = "widget_cms";
$password_widget_cms = "secretpassword";
$widget_cms = mysql_pconnect($hostname_widget_cms, $username_widget_cms, $password_widget_cms) or trigger_error(mysql_error(),E_USER_ERROR); 
?>