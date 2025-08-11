<?php
include('../ajaxconfig.php');
@session_start();

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

$column = array(
    'ls.scheme_id',
    'ls.scheme_name',
    'lcc.loan_category_creation_name',
    'ls.due_method',
    'ls.scheme_id',
    'ls.scheme_id',
    'ls.status'
);
$query = "SELECT ls.* FROM loan_scheme ls   ";

if (isset($_POST['scheme_id'])) {
    $scheme_ids = $_POST['scheme_id'];

    // If empty array or empty string, return empty result
    if (empty($scheme_ids)) {
        echo json_encode([
            'draw' => intval($_POST['draw']),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => []
        ]);
        exit;
    }

    // Normalize string to array if needed
    if (!is_array($scheme_ids)) {
        $scheme_ids = explode(',', $scheme_ids);
    }

    // Clean up values
    $scheme_ids = array_map('intval', $scheme_ids);
    $scheme_ids_str = implode(',', $scheme_ids);

    // Add to query
    if (str_contains($query, 'WHERE 1')) {
        $query .= " AND ls.scheme_id IN ($scheme_ids_str)";
    } else {
        $query .= " WHERE ls.scheme_id IN ($scheme_ids_str)";
    }
}

if (isset($_POST['search']) && $_POST['search'] != "") {

    $query .= "
            and (ls.scheme_name LIKE '%" . $_POST['search'] . "%'
            OR lcc.loan_category_creation_name LIKE '%" . $_POST['search'] . "%'
            OR ls.due_method LIKE '%" . $_POST['search'] . "%'
            OR ls.intrest_rate LIKE '%" . $_POST['search'] . "%'
            OR ls.due_period LIKE '%" . $_POST['search'] . "%' ) ";
}

if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ';
}

$query1 = '';

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

    if ($sno != "") {
        $sub_array[] = $sno;
    }
    $sub_array[] = $row["scheme_name"];
    $sub_array[] = $row["short_name"];
    //for Due method
    if ($row["due_method"] == 'monthly') {
        $sub_array[] = 'Monthly';
    } elseif ($row["due_method"] == 'weekly') {
        $sub_array[] = 'Weekly';
    } elseif ($row["due_method"] == 'daily') {
        $sub_array[] = 'Daily';
    }

 $sub_array[] = $row['profit_method'];
 $sub_array[] = $row['total_due'];
 $sub_array[] = $row['advance_due'];
 $sub_array[] = $row['due_period'];
    $sub_array[] = $row["intreset_type"];
    $sub_array[] = $row["intreset_min"];
    $sub_array[] = $row["intreset_max"];
    $sub_array[] = $row["doc_charge_type"];
    $sub_array[] = $row["doc_charge_min"];
    $sub_array[] = $row["doc_charge_max"];
    $sub_array[] = $row["proc_fee_type"];
    $sub_array[] = $row["proc_fee_min"];
    $sub_array[] = $row["proc_fee_max"];
    $sub_array[] = $row["overdue"];

    $status      = $row['status'];
    if ($status == 1) {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill'>Inactive</span></span>";
    } else {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--success kt-badge--inline kt-badge--pill'>Active</span></span>";
    }
    $id   = $row['scheme_id'];

    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT * FROM loan_scheme";
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

