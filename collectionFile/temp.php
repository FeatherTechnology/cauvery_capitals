<?php
// getLoanDetails.php for CLI execution

include 'C:/xampp/htdocs/marudham_capitals/ajaxconfig.php';
include 'C:\xampp\htdocs\marudham_capitals\collectionFile\getLoanDetailsClass.php';
// include 'getLoanDetailsClass.php';

// Accept req_id via command line
if (!isset($argv[1])) {
    echo "Error: req_id not passed.\n";
    exit(1);
}

$req_id = $argv[1];

$obj = new GetLoanDetails($connect, $req_id, date('Y-m-d'), 'Collection');

// Output result
echo "Processed req_id: $req_id\n";
echo json_encode($obj->response) . "\n";
