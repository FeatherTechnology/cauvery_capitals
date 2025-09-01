<?php
include('../ajaxconfig.php');
if (isset($_POST['cus_id'])) {
    $cus_id = $_POST['cus_id'];
}
// $cus_id='100010001000';
$records = array();

$result = $connect->query("SELECT * FROM customer_register where cus_id = '" . strip_tags($cus_id) . "' ");
if ($result->rowCount() > 0) {
    $row = $result->fetch();

    $records['cus_name'] = $row['customer_name'];
    $records['dob'] = $row['dob'];
    $records['age'] = $row['age'];
    $records['gender'] = $row['gender'];
    $records['state'] = $row['state'];
    $records['district'] = $row['district'];
    $records['taluk'] = $row['taluk'];
    $records['area'] = $row['area'];
    $records['address'] = $row['address'];
    $records['mobile1'] = $row['mobile1'];
    $records['mobile2'] = $row['mobile2'];
    $records['father_name'] = $row['father_name'];
    $records['mother_name'] = $row['mother_name'];
    $records['marital'] = $row['marital'];
    $records['spouse'] = $row['spouse'];
    $records['occupation_type'] = $row['occupation_type'];
    $records['occupation'] = $row['occupation'];
    $records['loan_limit'] = $row['loan_limit'];
    $records['pic'] = $row['pic'];

    $records['message'] = "Existing";

    $Area = $records['area'];
    $grpList = $connect->query("SELECT agm.`group_name` FROM `area_group_mapping` agm  join area_group_mapping_area agma on agma.group_map_id = agm. map_id  WHERE agma.`area_id` = $Area ");
    if ($grpList->rowCount() > 0) {
        $grprow = $grpList->fetch();
        $records['grp_name'] = $grprow['group_name'];
    }
    $lineList = $connect->query("SELECT alm.`line_name` FROM `area_line_mapping` alm join `area_line_mapping_area` alma on alma.line_map_id = alm.map_id  WHERE alma.`area_id`= $Area");
    if ($lineList->rowCount() > 0) {
        $linerow = $lineList->fetch();
        $records['line_name'] = $linerow['line_name'];
    }

    $area = $records['area'];
    $area_list = $connect->query("SELECT area_name FROM area_list_creation where area_id = '" . $area . "' and status=0 and area_enable = 0");
    if ($area_list->rowCount() > 0) {
        $arearow = $area_list->fetch();
        $records['area_name'] = $arearow['area_name'];
    }

} else {
    $records['message'] = "New";
}

echo json_encode($records);

// Close the database connection
$connect = null;