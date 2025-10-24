<?php

include('../../ajaxconfig.php');
include("./promotionListClass.php");


if (isset($_POST['cus_id'])) {
    $cus_id = preg_replace('/\D/', '', $_POST['cus_id']);
}
if (isset($_POST['first_name_search'])) {
    $first_name_search = $_POST['first_name_search'];
}
if (isset($_POST['last_name_search'])) {
    $last_name_search = $_POST['last_name_search'];
}
if (isset($_POST['cus_mob'])) {
    $cus_mob = $_POST['cus_mob'];
}

$Obj = new promotionListClass($connect);
$response['cusPromotionType'] = 'New Promotion';

if ($cus_id != '') {
    $sql = $connect->query("SELECT a.cus_id,a.first_name,a.last_name,a.mobile1,b.area_name FROM customer_register a JOIN area_list_creation b ON a.area = b.area_id  WHERE a.cus_id = '$cus_id' ");

    if ($sql->rowCount()) {
        $row = $sql->fetch();
        foreach ($row as $key => $value) {
            $response[$key] = $value;
        }
        $response['cusPromotionType'] = $Obj->getCustomerPromotionType($connect, $response['cus_id']);
    }
} else if ($first_name_search != '') {
    $sql = $connect->query("SELECT a.cus_id,a.first_name,a.last_name,a.mobile1,b.area_name FROM customer_register a JOIN area_list_creation b ON a.area = b.area_id  WHERE a.first_name = '$first_name_search' ");

    if ($sql->rowCount()) {
        $row = $sql->fetch();
        foreach ($row as $key => $value) {
            $response[$key] = $value;
        }
        $response['cusPromotionType'] = $Obj->getCustomerPromotionType($connect, $response['cus_id']);
    }
} else if ($last_name_search != '') {
    $sql = $connect->query("SELECT a.cus_id,a.first_name,a.last_name,a.mobile1,b.area_name FROM customer_register a JOIN area_list_creation b ON a.area = b.area_id  WHERE a.last_name = '$last_name_search' ");

    if ($sql->rowCount()) {
        $row = $sql->fetch();
        foreach ($row as $key => $value) {
            $response[$key] = $value;
        }
        $response['cusPromotionType'] = $Obj->getCustomerPromotionType($connect, $response['cus_id']);
    }
} else if ($cus_mob != '') {
    $sql = $connect->query("SELECT a.cus_id,a.first_name,a.last_name,a.mobile1,b.area_name FROM customer_register a JOIN area_list_creation b ON a.area = b.area_id  WHERE a.mobile1 = '$cus_mob' ");

    if ($sql->rowCount()) {
        $row = $sql->fetch();
        foreach ($row as $key => $value) {
            $response[$key] = $value;
        }
        $response['cusPromotionType'] = $Obj->getCustomerPromotionType($connect, $response['cus_id']);
    }
}

if ($sql->rowCount()) {
    $response['status'] = 'Records Found';
} else {
    $response['status'] = 'No Records Found';
}


echo json_encode($response);

// Close the database connection
$connect = null;