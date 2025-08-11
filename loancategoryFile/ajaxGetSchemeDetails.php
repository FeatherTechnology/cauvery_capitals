<?php 
// Also Used in Balance Report JS
include('../ajaxconfig.php');

$scheme_details_array = array();

if (isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];

    // Fetch details only for the given scheme_id
    $result = $connect->query("SELECT * FROM loan_scheme WHERE scheme_id = '$id'");

    if ($row = $result->fetch()) {
    $scheme_details_array = $row;
    }
}

echo json_encode($scheme_details_array);

// Close the database connection
$connect = null;
?>
