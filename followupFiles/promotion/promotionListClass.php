<?php
session_start();
class promotionListClass
{
    public $area_list = ''; // keep as string, not array

    public function __construct($connect)
    {
        $userid = $_SESSION["userid"];
        if ($userid != 1) {

            $userQry = $connect->query("SELECT group_id ,due_followup_lines, promo_act_area_access FROM USER WHERE user_id = $userid ");
            $rowuser = $userQry->fetch();
            $group_id = $rowuser['group_id'];
            $due_followup_lines = $rowuser['due_followup_lines'];
            $promo_act_area_access = $rowuser['promo_act_area_access'];

            $group_id = explode(',', $group_id);
            $due_followup_lines = explode(',', $due_followup_lines);
            $area_list_array = [];

            if ($promo_act_area_access == 1) {

                foreach ($group_id as $group) {
                    $groupQry = $connect->query("SELECT area_id FROM area_group_mapping_area WHERE group_map_id = $group");

                    while ($row_sub = $groupQry->fetch(PDO::FETCH_ASSOC)) {
                        $area_list_array[] = $row_sub['area_id'];
                    }
                }
                $area_ids = [];
                foreach ($area_list_array as $subarray) {
                    $area_ids = array_merge($area_ids, explode(',', $subarray));
                }

                $area_ids = array_unique($area_ids);
                $this->area_list = implode(',', $area_ids);

            } else if ($promo_act_area_access == 2) {

                foreach ($due_followup_lines as $due_foll_lines) {
                    $groupQry = $connect->query("SELECT adma.area_id 
                        FROM area_duefollowup_mapping_area adma 
                        JOIN area_duefollowup_mapping adm ON adm.map_id = adma.map_id 
                        WHERE adm.map_id = $due_foll_lines");

                    while ($row_sub = $groupQry->fetch(PDO::FETCH_ASSOC)) {
                        $area_list_array[] = $row_sub['area_id'];
                    }
                }
                $area_ids = [];
                foreach ($area_list_array as $subarray) {
                    $area_ids = array_merge($area_ids, explode(',', $subarray));
                }

                $area_ids = array_unique($area_ids);
                $this->area_list = implode(',', $area_ids);
            }
        }
    }

    function getdetails($connect, $type)
    {
        $arr = array();

        if ($type == 'existing') {
            //only closed customers who dont have any loans in current.

            $sql = $connect->query("SELECT cs.cus_id,cs.consider_level,cs.updated_date 
                FROM closed_status cs 
                JOIN acknowlegement_customer_profile cp ON cs.req_id = cp.req_id 
                WHERE cs.cus_sts >= '20' 
                  AND cp.area_confirm_area IN (" . $this->area_list . ") 
                  AND cs.closed_sts = 1 ");

            while ($row = $sql->fetch()) {

                $last_closed_date = date('Y-m-d', strtotime($row['updated_date']));

                $check_req = $connect->query("SELECT req_id 
                    FROM request_creation 
                    WHERE (cus_status NOT between 4 and 9) 
                      AND cus_status < 20 
                      AND cus_id = '" . $row['cus_id'] . "' 
                    ORDER By req_id DESC LIMIT 1 ");

                if ($check_req->rowCount() == 0) {
                    $arr[] = array(
                        'cus_id' => $row['cus_id'],
                        'sub_status' => $row['consider_level'],
                        'last_updated_date' => $last_closed_date
                    );
                }
            }
        } else {

            $sql = $connect->query("SELECT req.* 
                FROM request_creation req 
                WHERE (req.cus_status >= 4 AND req.cus_status <= 9) 
                  AND (req.area IN (" . $this->area_list . ") 
                       OR (SELECT area_confirm_area FROM customer_profile WHERE req_id = req.req_id) IN (" . $this->area_list . ")) 
                GROUP BY req.cus_id");

            while ($row = $sql->fetch()) {

                $last_updated_date = date('Y-m-d', strtotime($row['updated_date']));

                $check_req = $connect->query("SELECT req_id 
                    FROM request_creation 
                    WHERE (cus_status NOT between 4 and 9) 
                      AND cus_status < 20 
                      AND cus_id = '" . $row['cus_id'] . "' 
                    ORDER By req_id DESC LIMIT 1 ");

                if ($check_req->rowCount() == 0) {
                    $arr[] = array(
                        'cus_id' => $row['cus_id'],
                        'sub_status' => $row['cus_status'],
                        'last_updated_date' => $last_updated_date
                    );
                }
            }
        }
        return $arr;
    }

    function getCustomerPromotionType($connect, $cus_id)
    {
        $response = 'Loan Progress';

        $sql = $connect->query("SELECT cs.cus_id,cs.consider_level,cs.updated_date 
            FROM closed_status cs 
            JOIN acknowlegement_customer_profile cp ON cs.req_id = cp.req_id 
            WHERE cs.cus_sts >= '20' 
              AND cs.cus_id = '$cus_id' ");

        while ($row = $sql->fetch()) {

            $check_req = $connect->query("SELECT req_id 
                FROM request_creation 
                WHERE (cus_status NOT between 4 and 9) 
                  AND cus_status < 20 
                  AND cus_id = '" . $row['cus_id'] . "' 
                ORDER By req_id DESC LIMIT 1 ");

            if ($check_req->rowCount() == 0) {
                $response = 'Existing';
            }
        }

        $sql = $connect->query("SELECT req.* 
            FROM request_creation req 
            WHERE (req.cus_status >= 4 AND req.cus_status <= 9) 
              AND req.cus_id = '$cus_id' ");

        while ($row = $sql->fetch()) {

            $check_req = $connect->query("SELECT req_id 
                FROM request_creation 
                WHERE (cus_status NOT between 4 and 9) 
                  AND cus_status < 20 
                  AND cus_id = '" . $row['cus_id'] . "' 
                ORDER By req_id DESC LIMIT 1 ");

            if ($check_req->rowCount() == 0) {
                $response = 'Repromotion';
            }
        }
        return $response;
    }
}
