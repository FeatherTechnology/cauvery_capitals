<?php
require '../ajaxconfig.php';

$id = $_POST['id'];

$famEditRes = array();
$famInfo = $connect->query("SELECT * FROM `verification_family_info` WHERE id='$id' ");
$fam = $famInfo->fetch();

$famEditRes['id'] = $fam['id'];
$famEditRes['first_name'] = $fam['first_name'];
$famEditRes['last_name'] = $fam['last_name'];
$famEditRes['relation'] = $fam['relationship'];
$famEditRes['remark'] = $fam['other_remark'];
$famEditRes['address'] = $fam['other_address'];
$famEditRes['age'] = $fam['relation_age'];
$famEditRes['aadhar'] = $fam['relation_aadhar'];
$famEditRes['mobileno'] = $fam['relation_Mobile'];
$famEditRes['occ'] = $fam['relation_Occupation'];
$famEditRes['income'] = $fam['relation_Income'];
$famEditRes['bg'] = $fam['relation_Blood'];

echo json_encode($famEditRes);

// Close the database connection
$connect = null;