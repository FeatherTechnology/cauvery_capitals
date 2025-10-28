<?php
session_start();

include '../ajaxconfig.php';

$userid = $_SESSION['userid'];

$fdata = $_POST['fdata'];
$hand = $_POST['hand'];
$cus_id = $_POST['cus_id'];
$cus_name = $_POST['cus_name'];

// Check if same adhar_num and hand already exist
$checkqry = $connect->query("SELECT * FROM fingerprints WHERE adhar_num = '$cus_id' AND hand = '$hand' ");

if ($checkqry->rowCount() > 0) {
    // Update only if both adhar_num and hand already exist
    $qry = $connect->query("UPDATE fingerprints SET ansi_template = '$fdata', update_user_id = '$userid', updated_date = NOW()
        WHERE adhar_num = '$cus_id' AND hand = '$hand' ");
} else {
    // Otherwise insert new record (for another finger)
    $qry = $connect->query("INSERT INTO fingerprints (adhar_num, name, hand, ansi_template, insert_user_id, created_date) 
        VALUES ('$cus_id', '$cus_name', '$hand', '$fdata', '$userid', NOW())");
}

if($qry){
    $response = "Submitted Successfully";
}else{
    $response = "Error";
}

echo json_encode($response);

// Close the database connection
$connect = null;
?>