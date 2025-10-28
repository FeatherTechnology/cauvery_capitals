<?php
include('../ajaxconfig.php');

$id = $_POST['id'] ?? '';
$family = $_POST['family'] ?? 'false';
$records = array();
$records['fingers'] = array();

if ($family == 'true') {
    $qry = $connect->query("
        SELECT fp.hand, fp.ansi_template
        FROM verification_family_info fam
        JOIN fingerprints fp ON fam.relation_aadhar = fp.adhar_num
        WHERE fam.id = '$id' AND fp.ansi_template != ''
    ");
    while ($row = $qry->fetch()) {
        $records['fingers'][] = array(
            "fpTemplate" => $row['ansi_template'],
            "hand" => $row['hand']
        );
    }
} else {
    $qry = $connect->query("
        SELECT hand, ansi_template
        FROM fingerprints
        WHERE adhar_num = '$id' AND ansi_template != ''
    ");
    while ($row = $qry->fetch()) {
        $records['fingers'][] = array(
            "fpTemplate" => $row['ansi_template'],
            "hand" => $row['hand']
        );
    }
}

echo json_encode($records);
$connect = null;
?>
