<?php
session_start();
include '../ajaxconfig.php';

if (isset($_POST["req_id"])) {
    $req_id = $_POST["req_id"];
}

$response1 = 'completed';

$response2 = 'completed';

$response3 = 'completed';
$sts_qry = $connect->query("SELECT mortgage_process, mortgage_document_pending, endorsement_process, Rc_document_pending FROM acknowlegement_documentation WHERE req_id = '$req_id'");
if ($sts_qry->rowCount() > 0) {
    while ($sts_row = $sts_qry->fetch()) {
        if ($sts_row['mortgage_process'] == '0') {
            if ($sts_row['mortgage_document_pending'] == 'YES') {
                $response3 = 'pending';
            }
        }
        if ($sts_row['endorsement_process'] == '0') {
            if ($sts_row['Rc_document_pending'] == 'YES') {
                $response3 = 'pending';
            }
        }
    }
}

$response4 = 'completed';


if ($response1 == 'completed' && $response2 == 'completed' && $response3 == 'completed' && $response4 == 'completed') {
    $response = 'true';
} else {
    $response = 'false';
}

echo $response;

// Close the database connection
$connect = null;
?>
