<?php
include('../ajaxconfig.php');
@session_start();

// Get taluk
$taluk = isset($_POST['talukselected']) ? $_POST['talukselected'] : '';

// Get user ID
$userid = isset($_SESSION["userid"]) ? $_SESSION["userid"] : '';

$loan_category_arr = array();
$user_area_ids = array(); // flat list of all allowed area IDs

// Get user's group IDs
$Qry = $connect->query("SELECT * FROM user WHERE status = 0 AND user_id = '" . $userid . "'");
$run = $Qry->fetch();
if ($run) {
    $user_groups = explode(',', $run['group_id']);

    foreach ($user_groups as $group_id) {
        $Qry = $connect->query("
            SELECT agma.area_id 
            FROM area_group_mapping_area agma
            JOIN area_group_mapping agm ON agm.map_id = agma.group_map_id
            WHERE agm.status = 0 AND agma.group_map_id = $group_id
        ");

        while ($row_sub = $Qry->fetch(PDO::FETCH_ASSOC)) {
            // Handle possible comma-separated area IDs in DB
            $ids = explode(',', $row_sub['area_id']);
            foreach ($ids as $id) {
                $user_area_ids[] = (int) trim($id);
            }
        }
    }
}

// Remove duplicates
$user_area_ids = array_unique($user_area_ids);

// Fetch areas by taluk and check if they are in user's allowed area IDs
$result = $connect->query("
    SELECT * FROM area_list_creation 
    WHERE taluk LIKE '%" . $taluk . "%' 
    AND status = 0 
    AND area_enable = 0
");

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $area_id = (int) $row['area_id'];
    $area_name = $row['area_name'];

    if (in_array($area_id, $user_area_ids)) {
        $loan_category_arr[] = array(
            "area_id"   => $area_id,
            "area_name" => $area_name
        );
    }
}

echo json_encode($loan_category_arr);

$connect = null;
?>
