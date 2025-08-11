<?php
session_start();
class promotionListClass
{
    public $area_list = array();
    public function __construct($connect)
    {
        $userid = $_SESSION["userid"];
        if ($userid != 1) {

            $userQry = $connect->query("SELECT group_id FROM USER WHERE user_id = $userid ");
            $rowuser = $userQry->fetch();
                $group_id = $rowuser['group_id'];
            
            $group_id = explode(',', $group_id);
            $area_list = array();
            foreach ($group_id as $group) {
                $groupQry = $connect->query("SELECT area_id FROM area_group_mapping where map_id = $group ");
                $row_sub = $groupQry->fetch();
                 if ($row_sub !== false) {
                $area_list[] = $row_sub['area_id'];
                 }
            }
            $area_ids = array();
            foreach ($area_list as $subarray) {
                $area_ids = array_merge($area_ids, explode(',', $subarray));
            }
            $this->area_list = implode(',', $area_ids);
        }
    }
    function getdetails($connect, $type)
    {
        $arr = array();

        if ($type == 'existing') {
            //only closed customers who dont have any loans in current.

            $sql = $connect->query("SELECT cs.cus_id,cs.consider_level,cs.updated_date FROM closed_status cs JOIN acknowlegement_customer_profile cp ON cs.req_id = cp.req_id WHERE cs.cus_sts >= '20' and cp.area_confirm_area IN ($this->area_list) and cs.closed_sts = 1 ");

            while ($row = $sql->fetch()) {

                $last_closed_date = date('Y-m-d', strtotime($row['updated_date']));

                $check_req = $connect->query("SELECT req_id from request_creation where (cus_status NOT between 4 and 9) and cus_status < 20 and cus_id = '" . $row['cus_id'] . "' ORDER By req_id DESC LIMIT 1 ");
                if ($check_req->rowCount() == 0) {
                    $arr[] = array('cus_id' => $row['cus_id'], 'sub_status' => $row['consider_level'], 'last_updated_date' => $last_closed_date);
                }
            }
        } else {

            $sql = $connect->query("SELECT req.* FROM request_creation req WHERE (req.cus_status >= 4 AND req.cus_status <= 9) AND (req.area IN ( " . $this->area_list . " ) or  (select area_confirm_area from customer_profile where req_id = req.req_id) IN ( " . $this->area_list . " ) ) Group By req.cus_id");
            while ($row = $sql->fetch()) {

                $last_updated_date = date('Y-m-d', strtotime($row['updated_date']));
                $last_closed_date = '';

                $check_req = $connect->query("SELECT req_id from request_creation where (cus_status NOT between 4 and 9) and cus_status < 20 and cus_id = '" . $row['cus_id'] . "' ORDER By req_id DESC LIMIT 1 ");
                if ($check_req->rowCount() == 0) {
                    $arr[] = array('cus_id' => $row['cus_id'], 'sub_status' => $row['cus_status'], 'last_updated_date' => $last_updated_date);
                }
            }
        }
        return $arr;
    }

    function getCustomerPromotionType($connect, $cus_id)
    {
        $response = 'Loan Progress';

        $sql = $connect->query("SELECT cs.cus_id,cs.consider_level,cs.updated_date FROM closed_status cs JOIN acknowlegement_customer_profile cp ON cs.req_id = cp.req_id WHERE cs.cus_sts >= '20' and cs.cus_id = '$cus_id' ");

        while ($row = $sql->fetch()) {

            $check_req = $connect->query("SELECT req_id from request_creation where (cus_status NOT between 4 and 9) and cus_status < 20 and cus_id = '" . $row['cus_id'] . "' ORDER By req_id DESC LIMIT 1 ");
            if ($check_req->rowCount() == 0) {
                $response = 'Existing';
            }
        }

        $sql = $connect->query("SELECT req.* FROM request_creation req WHERE (req.cus_status >= 4 AND req.cus_status <= 9) and req.cus_id = '$cus_id' ");
        while ($row = $sql->fetch()) {

            $check_req = $connect->query("SELECT req_id from request_creation where (cus_status NOT between 4 and 9) and cus_status < 20 and cus_id = '" . $row['cus_id'] . "' ORDER By req_id DESC LIMIT 1 ");
            if ($check_req->rowCount() == 0) {
                $response = 'Repromotion';
            }
        }
        return $response;
    }
}
