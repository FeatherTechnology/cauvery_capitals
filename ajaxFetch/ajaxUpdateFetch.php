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
    }
    $group_id = explode(',', $group_id);
    $area_list_array = []; 

    foreach ($group_id as $group) {
        $groupQry = $connect->query("SELECT area_id FROM area_group_mapping_area WHERE group_map_id = $group");

        while ($row_sub = $groupQry->fetch(PDO::FETCH_ASSOC)) {
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
    'rc.req_id',
    'rc.cus_id',
    'rc.cus_name',
    'rc.mobile1',
    'rc.req_id',
    'rc.req_id',
    'rc.req_id',
    'rc.req_id',
    'rc.req_id',
    'rc.req_id',
);

if ($userid == 1) {
    $query = "SELECT rc.req_id, rc.cus_id, rc.cus_name, rc.mobile1,rc.area ,rc.area, rc.cus_status, rc.cus_data 
FROM request_creation rc
INNER JOIN (
    SELECT cus_id, MAX(req_id) AS last_req_id 
    FROM request_creation  
    GROUP BY cus_id
) latest ON rc.cus_id = latest.cus_id AND rc.req_id = latest.last_req_id
WHERE (rc.cus_data = 'Existing' AND rc.cus_status >= 1) OR (rc.cus_data = 'New' AND rc.cus_status > 13)";

} else {
    $query = "SELECT rc.req_id, rc.cus_id, rc.cus_name, rc.mobile1,rc.area ,rc.area, rc.cus_status, rc.cus_data
FROM request_creation rc
INNER JOIN ( SELECT cus_id, MAX(req_id) AS last_req_id FROM request_creation GROUP BY cus_id) latest ON rc.cus_id = latest.cus_id AND rc.req_id = latest.last_req_id
WHERE rc.area IN ($area_list) AND ( (rc.cus_data = 'Existing' AND rc.cus_status >= 1) OR (rc.cus_data = 'New' AND rc.cus_status > 13))";
}

if (isset($_POST['search']) && $_POST['search'] != "") {

    $query .= "
        and (rc.cus_id LIKE '%" . $_POST['search'] . "%'
        OR rc.cus_name LIKE '%" . $_POST['search'] . "%'
        OR rc.mobile1 LIKE '%" . $_POST['search'] . "%' ) ";
}

// $query .= " GROUP BY rc.cus_id ";

if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
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
    $cus_id = $row['cus_id'];
    $sub_array[] = $cus_id;
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $row['mobile1'];

    $areaqry = $connect->query("SELECT CASE 
    WHEN ( SELECT COUNT(*) FROM customer_profile WHERE cus_id = $cus_id ) > 0 
    THEN ( SELECT area_name FROM area_list_creation WHERE area_id = ( SELECT area_confirm_area FROM customer_profile WHERE cus_id = $cus_id ORDER BY `id` DESC LIMIT 1 ) ) 
    ELSE ( SELECT area_name FROM area_list_creation WHERE area_id = ( SELECT `area` FROM request_creation WHERE cus_id = $cus_id ORDER BY `req_id` DESC LIMIT 1 ) ) END AS `area_name`
    ");
    $sub_array[] = $areaqry->fetch()['area_name'];
    $area= $row['area'];
    $branchqry = $connect->query("SELECT bc.branch_name FROM area_group_mapping agm JOIN branch_creation bc ON agm.branch_id = bc.branch_id join area_group_mapping_area agma where  $area = agma.area_id ");
    $sub_array[] = $branchqry->fetch()['branch_name'];

    $lineqry = $connect->query("SELECT CASE 
    WHEN ( SELECT COUNT(*) FROM customer_profile WHERE cus_id = $cus_id ) > 0 
    THEN ( SELECT alm.line_name FROM area_line_mapping alm join area_line_mapping_area alma on alma.line_map_id = alm.map_id WHERE  ( SELECT area_confirm_area FROM customer_profile WHERE cus_id = $cus_id ORDER BY `id` DESC LIMIT 1 ) = alma.area_id) 
    ELSE ( SELECT alm.line_name FROM area_line_mapping alm join area_line_mapping_area alma on alma.line_map_id = alm.map_id WHERE( SELECT area FROM request_creation WHERE cus_id = $cus_id ORDER BY `req_id` DESC LIMIT 1 )= alma.area_id )
    END AS `line_name`
    ");
    $sub_array[] = $lineqry->fetch()['line_name'];

    $grpqry = $connect->query("SELECT CASE 
    WHEN ( SELECT COUNT(*) FROM customer_profile WHERE cus_id = $cus_id ) > 0 
    THEN ( SELECT agm.group_name FROM area_group_mapping agm join area_group_mapping_area agma on agma.group_map_id = agm.map_id  WHERE  ( SELECT area_confirm_area FROM customer_profile WHERE cus_id = $cus_id ORDER BY `id` DESC LIMIT 1 ) = agma.area_id)  
    ELSE ( SELECT agm.group_name FROM area_group_mapping agm join area_group_mapping_area agma on agma.group_map_id = agm.map_id  WHERE ( SELECT area FROM request_creation WHERE cus_id = $cus_id ORDER BY `req_id` DESC LIMIT 1 ) = agma.area_id )
    END AS `group_name`
    ");
    $sub_array[] = $grpqry->fetch()['group_name'];

    if (getDocumentStatus($connect, $cus_id) == false) {
        $sub_array[] = 'Document Pending';
    } else {
        $sub_array[] = 'Document Completed';
    }

    $id          = $row['cus_id'];
    $cus_id      = $row['cus_id'];
    $action = "<a href='update&upd=$id' title='Update'>  <span class='icon-border_color' style='font-size: 12px;position: relative;top: 2px;'></span> </a>";

    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT cus_reg_id FROM customer_register";
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

?>

<?php

function getDocumentStatus($connect, $cus_id)
{

    $status = 'completed';

    $sts_qry = $connect->query("SELECT mortgage_process,mortgage_document_pending,endorsement_process,Rc_document_pending FROM acknowlegement_documentation where cus_id_doc = '$cus_id' ");

    if ($sts_qry->rowCount() > 0) {
        while ($sts_row = $sts_qry->fetch()) { //check any one of document for mortgage or endorsement is pending then response will be pending

            if ($sts_row['mortgage_process'] == '0') {
                if ($sts_row['mortgage_document_pending'] == 'YES') {
                    $status = 'pending';
                }
            }
            if ($sts_row['endorsement_process'] == '0') {
                if ($sts_row['Rc_document_pending'] == 'YES') {
                    $status = 'pending';
                }
            }
        }
    }

    if ($status == 'completed') {
        $response = true;
    } else {
        $response = false;
    }

    return $response;
}

// Close the database connection
$connect = null;
?>