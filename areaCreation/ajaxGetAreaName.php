<?php
include('../ajaxconfig.php');
$taluk = '';
if (isset($_POST['talukselected'])) {
    $taluk = $_POST['talukselected'];
}

$loan_category_arr = array();

$result = $connect->query("SELECT * FROM area_list_creation where taluk= '" . $taluk . "' and status=0");

while ($row = $result->fetch()) {
    $area_id = $row['area_id'];
    $area_name = $row['area_name'];

    $checkQry = $connect->query("SELECT * FROM area_creation where status=0 and FIND_IN_SET($area_id,area_name_id)");
    if ($checkQry->rowCount() > 0) {
        $disabled = true;
    } else {
        $disabled = false;
    }

    $loan_category_arr[] = array("area_id" => $area_id, "area_name" => $area_name, "disabled" => $disabled);
}

echo json_encode($loan_category_arr);

// Close the database connection
$connect = null;
