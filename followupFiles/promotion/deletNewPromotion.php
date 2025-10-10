<?php
include("../../ajaxconfig.php");

// Get the ID from POST
$id = isset($_POST['id']) ? $_POST['id'] : '';

if($id != '') {
        $qry = $connect->query("DELETE FROM `new_cus_promo` WHERE id ='$id' ");
}
if($qry){
    $response = "Deleted Succesfully";
}else{
    $response = "Error While Deleting";
}

echo $response;

?>
