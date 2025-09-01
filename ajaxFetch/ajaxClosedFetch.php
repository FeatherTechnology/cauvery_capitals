<?php
@session_start();
include('..\ajaxconfig.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}
if ($userid != 1) {

    $userQry = $connect->query("SELECT * FROM USER WHERE user_id = $userid ");
    while ($rowuser = $userQry->fetch()) {
        $group_id = $rowuser['group_id'];
        $line_id = $rowuser['line_id'];
    }

    $line_id = explode(',', $line_id);
     $area_list_array = []; 
    foreach ($line_id as $line) {
        $lineQry = $connect->query("SELECT area_id FROM area_line_mapping_area where line_map_id = $line ");
        while ($row_sub = $lineQry->fetch(PDO::FETCH_ASSOC)) {
            $area_list_array[] = $row_sub['area_id']; 
        }
    }
    $area_ids = [];
    foreach ($area_list_array as $subarray) {
        $area_ids = array_merge($area_ids, explode(',', $subarray));
    }

    $area_ids = array_unique($area_ids);
    $area_list = implode(',', $area_ids);
}

$column = array(
    'cp.id',
    'cp.cus_id',
    'cp.cus_name',
    'ac.area_name',
    'bc.branch_name',
    'al.line_name',
    'cp.mobile1',
    'cp.id'
);

if ($userid == 1) {
    $query = 'SELECT cp.cus_id as cp_cus_id,cp.cus_name,ac.area_name, al.line_name, bc.branch_name,cp.mobile1, ii.cus_id as ii_cus_id, ii.req_id 
    FROM acknowlegement_customer_profile cp 
    JOIN in_issue ii ON cp.cus_id = ii.cus_id
    JOIN area_list_creation ac ON cp.area_confirm_area = ac.area_id
   JOIN area_line_mapping_area alma ON alma.area_id = ac.area_id
    JOIN area_line_mapping al ON al.map_id = alma.line_map_id
    JOIN branch_creation bc ON al.branch_id = bc.branch_id
    where ii.status = 0 and ii.cus_status = 20 '; // Only Issued and all lines not relying on sub area
} else {
    $query = "SELECT cp.cus_id as cp_cus_id,cp.cus_name,ac.area_name, al.line_name, bc.branch_name,cp.mobile1, ii.cus_id as ii_cus_id, ii.req_id 
    FROM acknowlegement_customer_profile cp 
    JOIN in_issue ii ON cp.cus_id = ii.cus_id
    JOIN area_list_creation ac ON cp.area_confirm_area = ac.area_id
    JOIN area_line_mapping_area alma ON alma.area_id = ac.area_id
    JOIN area_line_mapping al ON al.map_id = alma.line_map_id
    JOIN branch_creation bc ON al.branch_id = bc.branch_id
    where ii.status = 0 and ii.cus_status = 20 and cp.area_confirm_area IN ($area_list) "; //show only issued customers within the same lines of user. 
}


if (isset($_POST['search']) && $_POST['search'] != "") {

    $query .= " AND(cp.cus_id LIKE '%" . $_POST['search'] . "%'
            OR cp.cus_name LIKE '%" . $_POST['search'] . "%'
            OR ac.area_name LIKE '%" . $_POST['search'] . "%'
            OR bc.branch_name LIKE '%" . $_POST['search'] . "%'
            OR al.line_name LIKE '%" . $_POST['search'] . "%'
            OR cp.mobile1 LIKE '%" . $_POST['search'] . "%') ";
}
$query .= " GROUP BY ii.cus_id ";
$query .= " ORDER BY cp.updated_date ASC ";
$query1 = '';
// echo $query;
if ($_POST['length'] != -1) {
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $connect->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {
    $sub_array   = array();

    $sub_array[] = $sno;

    $sub_array[] = $row['cp_cus_id'];
    $sub_array[] = $row['cus_name'];

    $sub_array[] = $row['area_name'];
    $sub_array[] = $row["branch_name"];
    $sub_array[] = $row['line_name'];

    $sub_array[] = $row['mobile1'];

    $cus_id = $row['cp_cus_id'];
    $id          = $row['req_id'];
    // When in_issue and closed status count is equal then move to noc button will be shown. //if multiple request completed the collection means then complete closed for one time only so we check whether the request closed submit or not.. Move to Noc button wil not be show until all closed status submit.
    $ii_cus_id          = $row['ii_cus_id'];

    $ii_count = $connect->query("SELECT id FROM `in_issue` WHERE `cus_status` = '20' && `cus_id`='" . $ii_cus_id . "' ");
    $ii_cnt = $ii_count->rowCount();
    $closed_sts_count = $connect->query("SELECT id FROM `closed_status` WHERE `cus_sts` ='20' && `cus_id`='" . $ii_cus_id . "'");
    $close_cnt = $closed_sts_count->rowCount();

    // if($ii_cnt == $close_cnt){// if all request present in closed loan list are closed, then only it will allow to move that customer to closed
    if ($close_cnt > 0) { //if any one of the request got closed then that can be moved to noc straight
        $action = "<button class='btn btn-outline-secondary Move_to_noc' data-value='$ii_cus_id' data-id='$id'><span class = 'icon-arrow_forward'></span></button>";
    } else {
        $action = "<a href='closed&upd=$id&cusidupd=$cus_id' title='Edit details' ><button class='btn btn-success' style='background-color:#0c70ab;'>Close </button></a>";
    }



    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT cp.cus_id as cp_cus_id,cp.cus_name,cp.area_confirm_area,cp.area_line,cp.mobile1, ii.cus_id as ii_cus_id, ii.req_id FROM 
    acknowlegement_customer_profile cp JOIN in_issue ii ON cp.cus_id = ii.cus_id
    where ii.status = 0 and ii.cus_status = 20 GROUP BY ii.cus_id ";
    $statement = $connect->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

// Close the database connection
$connect = null;