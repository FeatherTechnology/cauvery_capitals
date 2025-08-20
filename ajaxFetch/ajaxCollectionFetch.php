<?php
session_start();
include('..\ajaxconfig.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}
if ($userid != 1) {

    $userQry = $connect->query("SELECT role, group_id, line_id, ag_id FROM USER WHERE user_id = $userid ");
    while ($rowuser = $userQry->fetch()) {
        $role = $rowuser['role'];
        $group_id = $rowuser['group_id'];
        $line_id = $rowuser['line_id'];
        $ag_id = $rowuser['ag_id'];
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
    'alc.area_name',
    'cp.id',
    'alm.line_name',
    'cp.mobile1',
    'cp.id'
);

//$cus_sts = implode(',', $_POST['Customer_status']);

if ($userid == 1) {
    $query = "SELECT cp.cus_id AS cp_cus_id, cp.cus_name, alc.area_name, alm.line_name AS area_line, cp.mobile1, b.branch_name, cp.req_id 
    FROM acknowlegement_customer_profile cp 
    JOIN in_issue ii ON cp.cus_id = ii.cus_id 
    JOIN customer_status cs ON cp.req_id = cs.req_id 
    JOIN area_list_creation alc ON cp.area_confirm_area = alc.area_id
    JOIN area_line_mapping_area alma ON alma.area_id = alc.area_id
    JOIN area_line_mapping alm ON alm.map_id = alma.line_map_id
    JOIN branch_creation b ON b.branch_id = alm.branch_id
    WHERE ii.status = 0 AND (ii.cus_status >= 14 AND ii.cus_status <= 17)"; // Only Issued and all lines not relying on sub area// 14 and 17 means collection entries, 17 removed from issue list

} else {

    if ($role != '2') {
        //show only issued customers within the same lines of user. // 14 and 17 means collection entries, 17 removed from issue list
        $query = "SELECT cp.cus_id AS cp_cus_id, cp.cus_name, alc.area_name, alm.line_name AS area_line, cp.mobile1, b.branch_name, cp.req_id 
        FROM acknowlegement_customer_profile cp 
        JOIN in_issue ii ON cp.cus_id = ii.cus_id 
        JOIN customer_status cs ON cp.req_id = cs.req_id 
        JOIN area_list_creation alc ON cp.area_confirm_area = alc.area_id
        JOIN area_line_mapping_area alma ON alma.area_id = alc.area_id
        JOIN area_line_mapping alm ON alm.map_id = alma.line_map_id
        JOIN branch_creation b ON b.branch_id = alm.branch_id
        left JOIN request_creation rc ON ii.req_id = rc.req_id 
        WHERE ii.status = 0 AND (ii.cus_status >= 14 AND ii.cus_status <= 17) AND cp.area_confirm_area IN ($area_list) ";
    } else { // if agent then check the possibilities
        $query = "SELECT cp.cus_id AS cp_cus_id, cp.cus_name, alc.area_name, alm.line_name AS area_line, cp.mobile1, b.branch_name, cp.req_id 
        FROM acknowlegement_customer_profile cp 
        JOIN in_issue ii ON cp.cus_id = ii.cus_id 
        JOIN request_creation rc ON ii.req_id = rc.req_id 
        JOIN customer_status cs ON cp.req_id = cs.req_id 
        JOIN area_list_creation alc ON cp.area_confirm_area = alc.area_id
        JOIN area_line_mapping_area alma ON alma.area_id = alc.area_id
        JOIN area_line_mapping alm ON alm.map_id = alma.line_map_id
        JOIN branch_creation b ON b.branch_id = alm.branch_id
        WHERE ii.status = 0 AND (ii.cus_status >= 14 AND ii.cus_status <= 17) AND (rc.user_type = 'Agent' OR (rc.agent_id != '' OR rc.agent_id != null)  OR rc.insert_login_id = '$userid' ) AND cp.area_confirm_area IN ($area_list) and rc.agent_id = $ag_id "; // 14 and 17 means collection entries, 17 removed from issue list

    }
}

if ($_POST["CustomerStatus"]!='') {
    $cus_sts = $_POST["CustomerStatus"];
    $query .= " AND cs.sub_status ='$cus_sts' ";
}

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {

        $query .= " AND (cp.cus_id LIKE '" . $_POST['search'] . "%'
            OR cp.cus_name LIKE '%" . $_POST['search'] . "%' 
            OR alc.area_name LIKE '%" . $_POST['search'] . "%'
            OR alm.line_name LIKE '%" . $_POST['search'] . "%' 
            OR cp.mobile1 LIKE '%" . $_POST['search'] . "%' ) ";
    }
}

if ($userid == 1 || $role != '2') {
    $query .= " GROUP BY ii.cus_id ";
}

if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} else {
    $query .= ' ';
}

$query1 = '';

if ($_POST['length'] != -1) {
    $query1 = ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
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
    $sub_array[] = $row['branch_name'];

    $sub_array[] = $row['area_line'];
    $sub_array[] = $row['mobile1'];

    $cus_id = $row['cp_cus_id'];
    $id     = $row['req_id'];
if($_POST["CustomerStatus"]!=''){
    $action = "<a href='collection&upd=$id&cusidupd=$cus_id&duestatus=due_nill' title='Edit details' ><button class='btn btn-success' style='background-color:#0c70ab;'>View</button></a>";
}else{
 $action = "<a href='collection&upd=$id&cusidupd=$cus_id' title='Edit details' ><button class='btn btn-success' style='background-color:#0c70ab;'>View</button></a>";
}

    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query = "SELECT cp.cus_id AS cp_cus_id FROM 
    acknowlegement_customer_profile cp JOIN in_issue ii ON cp.cus_id = ii.cus_id
    where ii.status = 0 and (ii.cus_status >= 14 and ii.cus_status <= 17) GROUP BY ii.cus_id ";
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