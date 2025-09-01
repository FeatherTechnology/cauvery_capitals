<?php

include('../ajaxconfig.php');

@session_start();
 $userid = $_SESSION["userid"];


    $scheme_id = $_POST['scheme_id'] ?? '';

    $scheme_name      = $_POST['add_scheme_name'] ?? '';
    $short_name       = $_POST['scheme_short'] ?? '';
    $due_method       = $_POST['scheme_due_method'] ?? '';
    $profit_method    = $_POST['profit_methods'] ?? '';
    $total_due        = $_POST['total_due'] ?? '';
    $advance_type      = $_POST['advance_type'] ?? '';               
    $advance_due      = $_POST['advance_due'] ?? '';               
    $due_period       = $_POST['due_period'] ?? '';
    $intreset_type    = $_POST['intreset_type'] ?? '';
    $intreset_min     = $_POST['intreset_min'] ?? '';
    $intreset_max     = $_POST['intreset_max'] ?? '';
    $doc_charge_type  = $_POST['doc_charge_type'] ?? '';
    $doc_charge_min   = $_POST['doc_charge_min'] ?? '';
    $doc_charge_max   = $_POST['doc_charge_max'] ?? '';
    $proc_fee_type    = $_POST['proc_fee_type'] ?? '';
    $proc_fee_min     = $_POST['proc_fee_min'] ?? '';
    $proc_fee_max     = $_POST['proc_fee_max'] ?? '';
    $overdue          = $_POST['overdue'] ?? '';
if($scheme_id !=''){
$updateQry = " UPDATE loan_scheme SET scheme_name = '$scheme_name',  short_name = '$short_name', due_method = '$due_method', profit_method = '$profit_method',advance_type ='$advance_type', total_due = '$total_due', advance_due = '$advance_due',  due_period = '$due_period', intreset_type = '$intreset_type', intreset_min = '$intreset_min', intreset_max = '$intreset_max', doc_charge_type = '$doc_charge_type', doc_charge_min = '$doc_charge_min',  doc_charge_max = '$doc_charge_max', proc_fee_type = '$proc_fee_type', proc_fee_min = '$proc_fee_min', proc_fee_max = '$proc_fee_max',overdue = '$overdue',update_login_id = '$userid', updated_date = CURRENT_TIMESTAMP(),status = 0 WHERE scheme_id = '$scheme_id'";
// echo $updateQry ;die;
if( $connect->query($updateQry)){
    $response = 1;
}
}
else{
$query = "INSERT INTO loan_scheme ( scheme_name, short_name, due_method, profit_method,`advance_type`, total_due, advance_due, due_period,intreset_type, intreset_min, intreset_max,doc_charge_type, doc_charge_min, doc_charge_max,proc_fee_type, proc_fee_min, proc_fee_max,overdue, status, insert_login_id, created_date
    ) VALUES ('$scheme_name', '$short_name', '$due_method', '$profit_method','$advance_type','$total_due', '$advance_due', '$due_period','$intreset_type', '$intreset_min', '$intreset_max','$doc_charge_type', '$doc_charge_min', '$doc_charge_max','$proc_fee_type', '$proc_fee_min', '$proc_fee_max','$overdue', '0', '$userid', NOW())";
// echo $query ;die;
 if ($connect->query($query)) {
        $response = 2;
    }
}
    

   


echo json_encode($response);
?>
