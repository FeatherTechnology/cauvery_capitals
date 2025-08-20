<?php 
include('../ajaxconfig.php');

$detailrecords = array();

if (isset($_POST['lineid']) && isset($_POST['loanCatId']) && isset($_POST['branchid'])) {
    $lineid = $_POST['lineid'];
    $loan_cat_area_id = $_POST['loanCatId'];
    $branchid = $_POST['branchid'];

    $qry = $connect->query("SELECT `area_id` FROM `area_duefollowup_mapping` WHERE `loan_category_id` = $loan_cat_area_id AND `branch_id` = $branchid ");
    $excludeAreaIds = [];
    if($qry->rowCount() > 0){
        while($duerow = $qry->fetchObject()){
            $excludeAreaIds = array_merge($excludeAreaIds, explode(',', $duerow->area_id));
        }
    }
    $selectQry = "SELECT alma.area_id FROM area_line_mapping_area alma join area_list_creation alc on alc.area_id = alma.area_id WHERE alc.area_enable =0 and  FIND_IN_SET(alma.line_map_id, ?)";
    $stmt = $connect->prepare($selectQry);
    $stmt->execute([$lineid]);
    $j = 0;
    $areaStmt = $connect->prepare("SELECT area_id, area_name FROM area_list_creation WHERE status = 0 AND area_id = ?");


    if ($stmt->rowCount() > 0) {
       while ($row = $stmt->fetchObject()) {

        // Split comma-separated area IDs
        $areaIds = explode(',', $row->area_id);

        foreach ($areaIds as $area_id) {
            $area_id = trim($area_id); // remove spaces just in case

                $areaStmt = $connect->prepare("SELECT area_id, area_name FROM area_list_creation WHERE status = 0 AND area_id = ? ");
                $areaStmt->execute([$area_id]);

                if ($areaRow = $areaStmt->fetchObject()) {
                    $detailrecords[$j]['area_id']   = $areaRow->area_id;
                    $detailrecords[$j]['area_name'] = $areaRow->area_name;

                    if(in_array($areaRow->area_id, $excludeAreaIds)){
                        $detailrecords[$j]['disabled'] = true;
                    }else{
                        $detailrecords[$j]['disabled'] = false;
                    }

                    $j++;
                }
            }
        }
    }
//     if ($stmt->rowCount() > 0) {
//     while ($row = $stmt->fetchObject()) {

//         // Use the area_id from first query
//         $areaStmt->execute([$row->area_id]);

//         if ($areaRow = $areaStmt->fetchObject()) {
//             $detailrecords[$j]['area_id']   = $areaRow->area_id;
//             $detailrecords[$j]['area_name'] = $areaRow->area_name;

//             // Check if this area should be disabled
//             $detailrecords[$j]['disabled'] = in_array($areaRow->area_id, $excludeAreaIds);

//             $j++;
//         }
//     }
// }
}

echo json_encode($detailrecords);

// Close the connection
$connect = null;
?>