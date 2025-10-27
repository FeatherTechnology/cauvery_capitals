<?php 
include('../ajaxconfig.php');

$famList_arr = array();

$adhaarno = $_POST['adhaarno'];
$cusId = $_POST['cusId'];
$cus = $_POST['cus'];

if($cus == '1'){
    $result = $connect->query("SELECT fp.hand, fp.ansi_template FROM fingerprints fp WHERE fp.adhar_num = '$cusId' ");

    $famList_arr['relation'] = 'Customer';
    $famList_arr['fingers'] = array();

    while($row = $result->fetch()){
        $famList_arr['fingers'][] = array(
            'fpTemplate' => $row['ansi_template'],
            'hand' => $row['hand']
        );
    }

}else{
    $result = $connect->query("SELECT fam.relationship, fp.hand, fp.ansi_template 
        FROM verification_family_info fam 
        LEFT JOIN fingerprints fp ON fam.relation_aadhar = fp.adhar_num 
        WHERE fam.relation_aadhar='$adhaarno' ");

    $famList_arr['fingers'] = array();
    while($row = $result->fetch()){
        $famList_arr['relation'] = $row['relationship'];
        $famList_arr['fingers'][] = array(
            'fpTemplate' => $row['ansi_template'],
            'hand' => $row['hand']
        );
    }
}

echo json_encode($famList_arr);
?>
