<?php
include '../ajaxconfig.php';

$cus_id = $_POST['cus_id'] ?? '';
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$area = $_POST['area'] ?? '';
$mobile = $_POST['mobile'] ?? '';
$loan_id = $_POST['loan_id'] ?? '';
$fingerprint_person_id = $_POST['fingerprint_person_id'] ?? '';
$cus_id = (!empty($cus_id)) ? $cus_id : $fingerprint_person_id;

$sql = '';
$fam_sql = '';

if ($cus_id != '') {
    $sql = "SELECT cus_id from customer_register WHERE cus_id LIKE '%$cus_id%' ";
    $fam_sql = "SELECT id from verification_family_info WHERE relation_aadhar LIKE '%$cus_id%' ";
} else if ($first_name != '') {
    $sql = "SELECT cus_id from customer_register WHERE first_name LIKE '%$first_name%' ";
    $fam_sql = "SELECT id from verification_family_info WHERE first_name LIKE '%$first_name%' ";
} else if ($last_name != '') {
    $sql = "SELECT cus_id from customer_register WHERE last_name LIKE '%$last_name%' ";
    $fam_sql = "SELECT id from verification_family_info WHERE last_name LIKE '%$last_name%' ";
} else if ($mobile != '') {
    $sql = "SELECT COALESCE(cr.cus_id, rc.cus_id) AS cus_id FROM request_creation rc LEFT JOIN customer_register cr 
       ON cr.req_ref_id = rc.req_id WHERE cr.mobile1 LIKE '%$mobile%' OR cr.mobile2 LIKE '%$mobile%' OR rc.mobile1 LIKE '%$mobile%' OR rc.mobile2 LIKE '%$mobile%' LIMIT 1";
} else if ($area != '') {
    $sql = "SELECT DISTINCT cr.cus_id
FROM customer_register cr
LEFT JOIN request_creation rc ON rc.cus_id = cr.cus_id
JOIN area_list_creation ac
    ON (
        -- customer register confirmed or fallback
        (cr.area_confirm_area IS NOT NULL AND cr.area_confirm_area != '' AND ac.area_id = cr.area_confirm_area)
        OR
        ((cr.area_confirm_area IS NULL OR cr.area_confirm_area = '') AND ac.area_id = cr.area)
        -- OR request creation area
        OR (ac.area_id = rc.area)
    )
WHERE ac.area_name LIKE '%$area%';
";
}else if ($loan_id != '') {
    $sql = "SELECT cus_id from in_issue where loan_id = '$loan_id' ";
}

$runSql = $connect->query($sql);
// if ($runSql->rowCount() > 0) {
//     while ($row = $runSql->fetch())
//         $cus_id_fetched[] = $row['cus_id'];
// } else {
//     $cus_id_fetched = [];
// }

// if (!empty($cus_id_fetched)) {
//     foreach ($cus_id_fetched as $cus_id) {
//         $sql = $connect->query("SELECT rc.req_id,cr.cus_id,rc.cus_status From request_creation rc left join customer_register cr on cr.req_ref_id = rc.req_id where cr.cus_id = $cus_id ORDER BY rc.req_id DESC LIMIT 1 ");
//         $row = $sql->fetch();
//         $req_id[] = $row['req_id'];
//         $cus_status[] = $row['cus_status'];
//     }
// }
$i = 1;
// $x = 0;
$data = array();
// if (!empty($req_id)) {
//     foreach ($req_id as $req) {
//         if ($cus_status[$x] == '0' || $cus_status[$x] == '1' || $cus_status[$x] == '4' || $cus_status[$x] == '5' || $cus_status[$x] == '8' || $cus_status[$x] == '9') {
//             $req_sql = $connect->query("SELECT cr.cus_id,cr.customer_name as cus_name ,ac.area_name,bc.branch_name,alm.line_name,agm.group_name,cr.mobile1,cr.mobile2 
//                         From request_creation req
//                         left join customer_register cr on cr.req_ref_id = req.req_id
//                         LEFT JOIN area_list_creation ac ON cr.area_confirm_area = ac.area_id
//                         JOIN area_line_mapping_area alma ON alma.area_id = ac.area_id
//                         JOIN area_line_mapping alm ON alm.map_id = alma.line_map_id
//                         JOIN area_group_mapping_area agma ON agma.area_id = ac.area_id
//                         JOIN area_group_mapping agm ON agm.map_id = agma.group_map_id
//                         LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id 
//                         where req.req_id = $req ");
//         } else {
//             $req_sql = $connect->query("SELECT cr.cus_id,cr.customer_name as cus_name,ac.area_name,bc.branch_name,alm.line_name,agm.group_name,cr.mobile1,cr.mobile2 
//                     FROM customer_register cr
//                     LEFT JOIN area_list_creation ac ON cr.area_confirm_area = ac.area_id 
//                     JOIN area_line_mapping_area alma ON alma.area_id = ac.area_id
//                     JOIN area_line_mapping alm ON alm.map_id = alma.line_map_id
//                     JOIN area_group_mapping_area agma ON agma.area_id = ac.area_id
//                     JOIN area_group_mapping agm ON agm.map_id = agma.group_map_id
//                     LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id 
//                     WHERE cr.req_ref_id = $req  ");
//         }

if ($runSql->rowCount() > 0) {
    while ($row = $runSql->fetch()) {
        $req_sql = $connect->query("SELECT cr.cus_id,cr.first_name as cus_name,ac.area_name,bc.branch_name,alm.line_name,agm.group_name,cr.mobile1,cr.mobile2 
                    FROM customer_register cr 
                    LEFT JOIN area_list_creation ac ON cr.area_confirm_area = ac.area_id 
                    JOIN area_line_mapping_area alma ON alma.area_id = ac.area_id
                    JOIN area_line_mapping alm ON alm.map_id = alma.line_map_id
                    JOIN area_group_mapping_area agma ON agma.area_id = ac.area_id
                    JOIN area_group_mapping agm ON agm.map_id = agma.group_map_id
                    LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id 
                    WHERE cr.cus_id = '" . $row['cus_id'] . "'");

        while ($req_row = $req_sql->fetch()) {
            $sub_array = array();
            $sub_array['sno'] = $i++;
            $sub_array['cus_id'] = $req_row['cus_id'];
            $sub_array['cus_name'] = $req_row['cus_name'];
            $sub_array['area'] = $req_row['area_name'];
            $sub_array['branch'] = $req_row['branch_name'];
            $sub_array['line'] = $req_row['line_name'];
            $sub_array['group'] = $req_row['group_name'];
            $sub_array['mobile1'] = $req_row['mobile1'];
            $sub_array['mobile2'] = $req_row['mobile2'];
            $action = '<input type="button" class="view_cust btn btn-primary" value="View" data-toggle="modal" data-target="#customerStatusModal" data-cusid=' . $req_row['cus_id'] . '>';
            $sub_array['action'] = $action;

            $data['customer_data'][] = $sub_array;
        }
    }
}


//for family data fetching
if ($fam_sql != '') {

    $runSql = $connect->query($fam_sql);
    $fam_id_arr = [];
    if ($runSql->rowCount() > 0) {
        while ($row = $runSql->fetch()) {
            $fam_id_arr[] = $row['id'];
        }
    }

    if (!empty($fam_id_arr)) {
        $i = 1;
        foreach ($fam_id_arr as $id) {
            $qry = $connect->query("SELECT fam.cus_id,cr.first_name, CONCAT(fam.first_name, ' ', fam.last_name) AS famname,fam.relationship,fam.relation_aadhar,fam.relation_Mobile FROM verification_family_info fam JOIN customer_register cr ON fam.cus_id = cr.cus_id WHERE fam.id = '$id' ");
            while ($row = $qry->fetch()) {
                $sub_array = array();
                $sub_array['sno'] = $i++;
                $sub_array['name'] = $row['famname'];
                $sub_array['relationship'] = $row['relationship'];
                $sub_array['adhaar'] = $row['relation_aadhar'];
                $sub_array['mobile'] = $row['relation_Mobile'];
                $sub_array['under_cus'] = $row['first_name'];
                $sub_array['under_cus_id'] = $row['cus_id'];

                $data['family_data'][] = $sub_array;
            }
        }
    }
}


echo json_encode($data);

// Close the database connection
$connect = null;