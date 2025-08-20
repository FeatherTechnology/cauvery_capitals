<?php

class acknowledgmentClass
{
    public $user_id;
    function __construct($user_id)
    {
        $this->user_id = $user_id;
    }
    function getAcknowledgmentCounts($connect)
    {
        $response = array();
        $today = date('Y-m-d');
        $month = (isset($_POST['month']) && $_POST['month'] != '') ? date('Y-m-01', strtotime($_POST['month'])) : date('Y-m-01');
        $area_list = $_POST['area_list'];

        $tot_in_ack = "SELECT COUNT(*) as tot_in_ack FROM request_creation where ( cus_status >= 3 and cus_status NOT IN(4, 5, 6, 8, 9, 10, 11, 12) ) and month(updated_date) = month('$month') and year(updated_date) = year('$month')";
        $today_in_ack = "SELECT COUNT(*) as today_in_ack FROM request_creation where cus_status = 3 and date(updated_date) = '$today' ";
        $tot_issue = "SELECT COUNT(*) as tot_issue FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id WHERE req.cus_status >= 14 and month(req.updated_date) = month('$month') and year(req.updated_date) = year('$month')";
        $today_issue = "SELECT COUNT(*) as today_issue FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id WHERE req.cus_status >= 14 and date(req.updated_date) = '$today' ";
        $tot_ack_bal = "SELECT COUNT(*) as tot_ack_bal FROM request_creation where (cus_status < 14 and cus_status >= 3 and cus_status NOT IN(4, 5, 6, 7, 8, 9, 10, 11, 12) ) and month(updated_date) = month('$month') and year(updated_date) = year('$month')";
        $today_ack_bal = "SELECT COUNT(*) as today_ack_bal FROM request_creation where cus_status = 3 and date(updated_date) = '$today' ";
        $tot_cancel = "SELECT COUNT(*) as tot_cancel from request_creation where cus_status = 7 and month(updated_date) = month('$month') and year(updated_date) = year('$month')";
        $today_cancel = "SELECT COUNT(*) as today_cancel from request_creation where cus_status = 7 and date(updated_date) = '$today' ";
        $tot_new = "SELECT COUNT(*) as tot_new from request_creation where (cus_status < 14 and cus_status >= 3 and cus_status NOT IN(4, 5, 6, 7, 8, 9, 10, 11, 12) ) and cus_data = 'New' and month(updated_date) = month('$month') and year(updated_date) = year('$month')";
        $today_new = "SELECT COUNT(*) as today_new from request_creation where cus_status = 3 and cus_data = 'New' and date(updated_date) = '$today' ";
        $tot_existing = "SELECT COUNT(*) as tot_existing from request_creation where (cus_status < 14 and cus_status >= 3 and cus_status NOT IN(4, 5, 6, 7, 8, 9, 10, 11, 12) ) and cus_data = 'Existing' and month(updated_date) = month('$month') and year(updated_date) = year('$month')";
        $today_existing = "SELECT COUNT(*) as today_existing from request_creation where cus_status = 3 and cus_data = 'Existing' and date(updated_date) = '$today' ";

        if (empty($area_list)) {
            $area_list = $this->getUserGroupBasedSubArea($connect, $this->user_id);
        }

        $tot_in_ack .= " AND area IN ($area_list) ";
        $today_in_ack .= " AND area IN ($area_list) ";
        $tot_issue .= " AND ( CASE WHEN cp.area_confirm_area IS NOT NULL THEN cp.area_confirm_area IN ($area_list) ELSE TRUE END )";
        $today_issue .= " AND ( CASE WHEN cp.area_confirm_area IS NOT NULL THEN cp.area_confirm_area IN ($area_list) ELSE TRUE END )";
        $tot_ack_bal .= " AND area IN ($area_list) ";
        $today_ack_bal .= " AND area IN ($area_list) ";
        $tot_cancel .= " AND area IN ($area_list) ";
        $today_cancel .= " AND area IN ($area_list) ";
        $tot_new .= " AND area IN ($area_list) ";
        $today_new .= " AND area IN ($area_list) ";
        $tot_existing .= " AND area IN ($area_list) ";
        $today_existing .= " AND area IN ($area_list) ";


        $tot_in_ackQry = $connect->query($tot_in_ack);
        $today_in_ackQry = $connect->query($today_in_ack);
        $tot_issueQry = $connect->query($tot_issue);
        $today_issueQry = $connect->query($today_issue);
        $tot_ack_balQry = $connect->query($tot_ack_bal);
        $today_ack_balQry = $connect->query($today_ack_bal);
        $tot_cancelQry = $connect->query($tot_cancel);
        $today_cancelQry = $connect->query($today_cancel);
        $tot_newQry = $connect->query($tot_new);
        $today_newQry = $connect->query($today_new);
        $tot_existingQry = $connect->query($tot_existing);
        $today_existingQry = $connect->query($today_existing);


        $response['tot_in_ack'] = $tot_in_ackQry->fetch()['tot_in_ack'];
        $response['today_in_ack'] = $today_in_ackQry->fetch()['today_in_ack'];
        $response['tot_issue'] = $tot_issueQry->fetch()['tot_issue'];
        $response['today_issue'] = $today_issueQry->fetch()['today_issue'];
        $response['tot_ack_bal'] = $tot_ack_balQry->fetch()['tot_ack_bal'];
        $response['today_ack_bal'] = $today_ack_balQry->fetch()['today_ack_bal'];
        $response['tot_cancel'] = $tot_cancelQry->fetch()['tot_cancel'];
        $response['today_cancel'] = $today_cancelQry->fetch()['today_cancel'];
        $response['tot_revoke'] = 0;
        $response['today_revoke'] = 0;
        $response['tot_new'] = $tot_newQry->fetch()['tot_new'];
        $response['today_new'] = $today_newQry->fetch()['today_new'];
        $response['tot_existing'] = $tot_existingQry->fetch()['tot_existing'];
        $response['today_existing'] = $today_existingQry->fetch()['today_existing'];


        return $response;
    }

function getUserGroupBasedSubArea($connect, $user_id)
{
    $area_ids = [];

    // Step 1: Get the group_id(s) from USER table
    $userQry = $connect->query("SELECT group_id FROM USER WHERE user_id = $user_id");
    if ($userQry && $rowuser = $userQry->fetch()) {
        $group_ids = explode(',', $rowuser['group_id']);
    } else {
        // No user or group ID found
        return '';
    }

    // Step 2: For each group_id, get the corresponding area_id(s)
    foreach ($group_ids as $group) {
        $group = intval($group); // sanitize to avoid injection

        $groupQry = $connect->query("SELECT area_id FROM area_group_mapping_area WHERE group_map_id = $group");
        if ($groupQry && $row_sub = $groupQry->fetch()) {
            if (!empty($row_sub['area_id'])) {
                $areas = explode(',', $row_sub['area_id']);
                $area_ids = array_merge($area_ids, $areas);
            }
        }
        // else skip silently if no mapping or query fails
    }

    // Step 3: Remove duplicates and return as comma-separated string
    $area_ids = array_unique($area_ids);
    $area_ids = array_map('intval', $area_ids); // sanitize all to int
    return implode(',', $area_ids);
}

}
