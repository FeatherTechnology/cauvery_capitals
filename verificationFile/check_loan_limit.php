<?php
require '../ajaxconfig.php';

if (isset($_POST['loan_category'])) {
    $loan_category = $_POST['loan_category'];
}

$limit = array();
$loanLimit = $connect->query("SELECT loan_limit FROM `loan_calculation` WHERE loan_category = '" . strip_tags($loan_category) . "' ");
$cnt = $loanLimit->rowCount();
if ($cnt > 0) {
    while ($amnt = $loanLimit->fetch()) {
        $limit[] = $amnt['loan_limit'];
    }
}
echo json_encode($limit);

// Close the database connection
$connect = null;