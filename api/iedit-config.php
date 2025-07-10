<?php
date_default_timezone_set('Asia/Kolkata');

$host = "mysql8010.site4now.net";
$db_user = "aab6ce_cltdemo";
$db_pass = "demo@123";
$dbname = "db_aab6ce_cltdemo";

$mysqli = mysqli_connect($host, $db_user, $db_pass, $dbname) or die("Error in database connection" . mysqli_connect_error());
mysqli_set_charset($mysqli, "utf8");
$timeZoneQry = "set time_zone = '+5:30' ";
$mysqli->query($timeZoneQry);
