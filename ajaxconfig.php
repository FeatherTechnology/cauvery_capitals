<?php
date_default_timezone_set('Asia/Kolkata');
$timeZoneQry = "SET time_zone = '+5:30' ";

$host = "mysql8010.site4now.net";
$dbname = "db_aab6ce_cltdemo";
$db_user = "aab6ce_cltdemo";
$db_pass = "demo@123";

try {
    $connect = new PDO("mysql:host=$host;dbname=$dbname", $db_user, $db_pass);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connect->exec($timeZoneQry);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

