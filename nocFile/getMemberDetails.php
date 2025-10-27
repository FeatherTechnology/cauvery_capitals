<?php
include('../ajaxconfig.php');

$req_id = $_POST['req_id'] ?? '';
$noc_member = $_POST['noc_member'] ?? '';

$records = array();

if ($noc_member == 2) { // Guarantor
    $qry = $connect->query("SELECT cp.guarentor_name, CONCAT(fam.first_name, ' ', fam.last_name) AS famname,
        fp.hand, fp.ansi_template
        FROM acknowlegement_customer_profile cp 
        LEFT JOIN verification_family_info fam ON fam.id = cp.guarentor_name
        LEFT JOIN fingerprints fp ON fam.relation_aadhar = fp.adhar_num
        WHERE cp.req_id='$req_id' AND fp.ansi_template != ''
    ");

    $records['guarentor_id'] = '';
    $records['guarentor_name'] = '';
    $records['fingers'] = array();

    while ($row = $qry->fetch()) {
        $records['guarentor_id'] = $row['guarentor_name'];
        $records['guarentor_name'] = $row['famname'];

        if (!empty($row['ansi_template'])) {
            $records['fingers'][] = array(
                "fpTemplate" => $row['ansi_template'],
                "hand" => $row['hand']
            );
        }
    }

} else if ($noc_member == 3) { // Family member list
    $qry = $connect->query("
        SELECT id, CONCAT(first_name, ' ', last_name) AS famname
        FROM verification_family_info
        WHERE req_id = '$req_id'
    ");
    $i = 0;
    while ($row = $qry->fetch()) {
        $records['fam_id'][$i] = $row['id'];
        $records['fam_name'][$i] = $row['famname'];
        $i++;
    }
}

echo json_encode($records);
$connect = null;
?>
