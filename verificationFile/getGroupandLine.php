<?php
include('../ajaxconfig.php');
if (isset($_POST['area_id'])) {
    $area = $_POST['area_id'];
}

$records = array();

$result = $connect->query("SELECT agm.group_name FROM `area_group_mapping_area`agma join area_group_mapping agm on agm.map_id =agma.group_map_id  where agm.status=0 and agma.area_id = $area");
$row = $result->fetch();
$records['group_name'] = $row['group_name'];

$result = $connect->query("SELECT alm.line_name FROM `area_line_mapping_area` alma join area_line_mapping alm on alm.map_id =alma.line_map_id  where alm.status=0 and alma.area_id = $area  ");
$row = $result->fetch();
$records['line_name'] = $row['line_name'];

$result = $connect->query("SELECT afn.duefollowup_name FROM `area_duefollowup_mapping` afn join area_duefollowup_mapping_area afna on afn.map_id =afna.map_id  where afn.status=0 and afna.area_id = $area  ");
$row = $result->fetch();
$records['duefollowup_name'] = $row['duefollowup_name'];

echo json_encode($records);

// Close the database connection
$connect = null;