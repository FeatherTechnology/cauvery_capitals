<?php
include('../../ajaxconfig.php');
@session_start();
$user_id = $_SESSION['userid'];

$detailrecords = [];
$area_id  = $_POST['area_id'];

if ($user_id && $area_id == " ") {

    // Step 1: Get user's mapped lines
    $sql = $connect->prepare("SELECT promo_act_area_access, line_id, due_followup_lines, group_id FROM user WHERE user_id = ?");
    $sql->execute([$user_id]);
    $user = $sql->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($user['promo_act_area_access'] == 1) {
            $user_ids = explode(',', $user['group_id']);
            $table = 'area_group_mapping_area';
        } elseif ($user['promo_act_area_access'] == 2) {
            $user_ids = explode(',', $user['due_followup_lines']);
            $table = 'area_duefollowup_mapping_area';
        }

        $user_ids = array_map('trim', $user_ids);
        if (!empty($user_ids)) {
            $ids_string = implode(',', $user_ids);

            // Step 2: Fetch all relevant lines for the user
            if ($user['promo_act_area_access'] == 1) {
                 $sql2 = $connect->query("SELECT * FROM $table WHERE  group_map_id IN ($ids_string)");
            } elseif ($user['promo_act_area_access'] == 2) {
                $sql2 = $connect->query("SELECT * FROM $table WHERE  map_id IN ($ids_string)");
            }

           

            while ($row = $sql2->fetch(PDO::FETCH_ASSOC)) {

                // Step 3: Fetch area names from area_list_creation
                if (!empty($row['area_id'])) {
                    $area_ids_str = $row['area_id'];
                    $sql_area = $connect->query("SELECT area_id, area_name FROM area_list_creation WHERE area_id IN ($area_ids_str)");

                    while ($area = $sql_area->fetch(PDO::FETCH_ASSOC)) {
                        $detailrecords[] = [
                            'area_id' => $area['area_id'],
                            'area_name' => $area['area_name']
                        ];
                    }
                }
            }
        }
    }
}

echo json_encode($detailrecords);
$connect = null;
