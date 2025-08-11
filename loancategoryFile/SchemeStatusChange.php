<?php 
// Also Used in Balance Report JS
include('../ajaxconfig.php');

@session_start();

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}


if (isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];

    // Fetch details only for the given scheme_id
    $result = $connect->query("UPDATE `loan_scheme` SET `status`='1',`delete_login_id`='$userid' WHERE  scheme_id = '$id'");

    if ($result) {
        $result=0;
    }else{
        $result=1;
    }
}

echo json_encode($result);

// Close the database connection
$connect = null;
?>
