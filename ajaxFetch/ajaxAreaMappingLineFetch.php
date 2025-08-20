<?php
include('../ajaxconfig.php');
@session_start();

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

$column = array(
    'alm.map_id',
    'alm.line_name',
    'c.company_name',
    'b.branch_name',
    'alm.map_id',
    'alm.map_id',
    'alm.status',
    'alm.status'
);

$query = "SELECT 
    alm.map_id,
    alm.status,
    alm.line_name,
    c.company_name,
    b.branch_name,
    GROUP_CONCAT(alc.area_name ORDER BY alc.area_id SEPARATOR ', ') AS area_names
FROM area_line_mapping alm
JOIN company_creation c ON c.company_id = alm.company_id
JOIN branch_creation b ON b.branch_id = alm.branch_id
JOIN area_line_mapping_area alma ON alma.line_map_id = alm.map_id
JOIN area_list_creation alc ON alc.area_id = alma.area_id
WHERE alc.status = 0 ";

if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = $_POST['search'];
    $query .= "AND (alm.line_name LIKE '%" . $search . "%'
            OR c.company_name LIKE '%" . $search . "%'
            OR b.branch_name LIKE '%" . $search . "%'
            OR alc.area_name LIKE '%" . $search . "%') ";
}

$query .= "GROUP BY alm.map_id, alm.line_name, c.company_name, b.branch_name ";

if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
}

$query1 = '';
if ($_POST['length'] != -1) {
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

// First query: count filtered rows
$statement = $connect->prepare($query);
$statement->execute();
$number_filter_row = $statement->rowCount();
$statement->closeCursor(); // ✅ Close before next query

// Second query: fetch data with pagination
$statement = $connect->prepare($query . $query1);
$statement->execute();
$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {
    $sub_array = array();
    $sub_array[] = $sno++;
    $sub_array[] = $row['line_name'];
    $sub_array[] = $row["company_name"];
    $sub_array[] = $row["branch_name"];
    $sub_array[] = $row["area_names"];

    if ($row['status'] == 1) {
        $sub_array[] = "<span class='kt-badge kt-badge--danger kt-badge--inline kt-badge--pill'>Inactive</span>";
    } else {
        $sub_array[] = "<span class='kt-badge kt-badge--success kt-badge--inline kt-badge--pill'>Active</span>";
    }

    $id = $row['map_id'];
    $action = "<a href='area_mapping&upd=$id&type=line' title='Edit details'><span class='icon-border_color'></span></a>&nbsp;&nbsp; 
               <a href='area_mapping&del=$id&type=line' title='Delete details' class='delete_area_mapping'><span class='icon-trash-2'></span></a>";

    $sub_array[] = $action;
    $data[] = $sub_array;
}

function count_all_data($connect)
{
    $query = "SELECT * FROM area_line_mapping";
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
