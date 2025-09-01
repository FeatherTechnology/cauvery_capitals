<?php 
include('../ajaxconfig.php');
$map='group';
if (isset($_POST['map'])) {
    $map = $_POST['map'];
}

$selectQry = "SELECT alc.area_name , alc.area_id FROM area_list_creation alc join area_creation ac on ac.area_name_id = alc.area_id WHERE ac.status= 0 and alc.area_enable =0 ";
$res = $connect->query($selectQry) or die("Error in Get All Records");
$detailrecords = array();$j=0;
$area_id = array();

if ($res->rowCount()>0)
{
    while($row = $res->fetchObject()){
        $area_id      = $row->area_id;
        $detailrecords[$j]['area_id']      = $row->area_id;
        $detailrecords[$j]['area_name']    = $row->area_name;

        if($map == 'line'){
            
            $runQry = $connect->query("SELECT * From area_line_mapping_area where area_id = $area_id ");
            
            if($runQry->rowCount()>0){
                $detailrecords[$j]['disabled'] = true;
            }else{
                $detailrecords[$j]['disabled'] = false;
            }

        }else if($map == 'group'){
            $runQry = $connect->query("SELECT * From area_group_mapping_area where area_id = $area_id ");
            if($runQry->rowCount()>0){
                $detailrecords[$j]['disabled'] = true;
            }else{
                $detailrecords[$j]['disabled'] = false;
            }
        }else if($map == 'duefollowup'){
            $runQry = $connect->query("SELECT * From area_duefollowup_mapping_area where area_id = $area_id ");
            if($runQry->rowCount()>0){
                $detailrecords[$j]['disabled'] = true;
            }else{
                $detailrecords[$j]['disabled'] = false;
            }
        }
                
        $j++;
    }
 
}

echo json_encode($detailrecords);

// Close the database connection
$connect = null;
?>