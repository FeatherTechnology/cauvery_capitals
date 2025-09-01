<?php
include('../../ajaxconfig.php');
$user_id = ($_POST['user_id'] != '') ? $_POST['user_id'] : '';

$type = $_POST['type'];

if ($type == 'today') {
    $where = " DATE(iv.updated_date) = CURRENT_DATE and iv.cus_status > 13 ";

} else if ($type == 'day') {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $where = " (DATE(iv.updated_date) >= DATE('$from_date') && DATE(iv.updated_date) <= DATE('$to_date')) and iv.cus_status > 13 ";

} else if ($type == 'month') {
    $month = date('m', strtotime($_POST['month']));
    $year = date('Y', strtotime($_POST['month']));

    $where = " (MONTH(iv.updated_date) = '$month' && YEAR(iv.updated_date) = '$year') and iv.cus_status > 13 ";
}

$condition = getAreaList($connect, $user_id); //condition will be returned if user id selected

getDetials($connect, $where, $condition);

function getDetials($connect, $where, $condition)
{
    // >13 means entries moved to collection from issue
    //will show only interest amunt under user's branch not others also
    //excluding due type interest , coz interest loans will be sepately calculated. those interest will be collected every month as due amount
    $qry = $connect->query("SELECT COALESCE(SUM(alc.int_amt_cal), 0) AS int_amt_cal from in_verification iv
    JOIN acknowlegement_loan_calculation alc ON iv.req_id = alc.req_id  
    where due_type != 'Interest' AND $where $condition ");
    $row = $qry->fetch();
    $benefit_amount = $row['int_amt_cal']; //interest amount

    //getting only due type interest 
    $qry = $connect->query("SELECT COALESCE(SUM(alc.int_amt_cal), 0) AS int_amt_cal from in_verification iv
    JOIN acknowlegement_loan_calculation alc ON iv.req_id = alc.req_id  
    where due_type = 'Interest' AND $where $condition ");
    $row = $qry->fetch();
    $interest_amount = $row['int_amt_cal']; //interest amount on interest type loans

    $response['benefit_amount'] = moneyFormatIndia($benefit_amount);
    $response['interest_amount'] = moneyFormatIndia($interest_amount);

    echo json_encode($response);
}

//Format number in Indian Format
function moneyFormatIndia($num)
{
    $isNegative = false;
    if ($num < 0) {
        $isNegative = true;
        $num = abs($num);
    }

    $explrestunits = "";
    if (strlen((string)$num) > 3) {
        $lastthree = substr((string)$num, -3);
        $restunits = substr((string)$num, 0, -3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        foreach ($expunit as $index => $value) {
            if ($index == 0) {
                $explrestunits .= (int)$value . ",";
            } else {
                $explrestunits .= $value . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }

    return $isNegative ? "-" . $thecash : $thecash;
}

function getAreaList($connect, $user_id)
{

    if ($user_id != '') { //to get user's sub area id based on user's branch assigned

        $userQry = $connect->query("SELECT line_id FROM USER WHERE user_id = $user_id ");
        while ($rowuser = $userQry->fetch()) {
            $group_id = $rowuser['line_id'];
        }
        $group_id = explode(',', $group_id);
        $area_list = array();
        foreach ($group_id as $group) {
            $groupQry = $connect->query("SELECT area_id FROM area_line_mapping_area where line_map_id = $group ");
            $row_sub = $groupQry->fetch();
            $area_list[] = $row_sub['area_id'];
        }
        $area_ids = array();
        foreach ($area_list as $subarray) {
            $area_ids = array_merge($area_ids, explode(',', $subarray));
        }
        $area_list = array();
        $area_list = implode(',', $area_ids);
    } else {
        $area_list = '';
    }
    $condition = ($area_list != '') ? " and FIND_IN_SET(iv.area ,'" . $area_list . "')" : '';
    return $condition;
}

// Close the database connection
$connect = null;
