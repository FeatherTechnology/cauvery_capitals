<?php
session_start();
include '../../ajaxconfig.php';

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

$bank_details = '';
if ($userid != 1) {

    $userQry = $connect->query("SELECT  bank_details FROM USER WHERE user_id = $userid ");
    $rowuser = $userQry->fetch();
    $bank_details = $rowuser['bank_details'];
}

if (isset($_POST['search_date']) && $_POST['search_date'] != '') {
    $search_date = $_POST['search_date'];
    $date = new DateTime($search_date);
    $full_date = $date->format('Y-m_d');
    $from_month = $date->format('m');
    $from_year = $date->format('Y');
}
$records = getOpeningBalance($connect, $full_date, $bank_details, $userid);
$hand_opening_balance = $records[0]['hand_opening'];
$bank1_name = $records[0]['bank1_name'];
$bank1_opening = $records[0]['bank1_opening'];

$bank2_name = $records[0]['bank2_name'];
$bank2_opening = $records[0]['bank2_opening'];
$total = $hand_opening_balance + $bank1_opening + $bank2_opening ;  

$records = getClosingBalance($connect, $full_date, $bank_details, $userid);
$hand = $records['hand_summary'];

$h_collection= $hand['ct_hand_collection'];
$h_issued = $hand['ct_hand_issued'];
$h_hand_expense = $hand['hand_expense'];
$h_agent = $hand['ct_cr_agent'] - $hand['ct_db_agent'];
$h_deposite = $hand['hand_cr_deposit'] - $hand['hand_db_deposite'];
$h_exchange = $hand['hand_cr_exchange'] - $hand['hand_db_exchange'];
$h_el = $hand['hand_cr_el'] - $hand['hand_db_el'];
$h_invest = $hand['hand_cr_hinvest'] - $hand['hand_db_hinvest'];
$h_till_now_collection = $hand['till_now_hand_collection'] ;
$h_till_now_loan_issued = $hand['till_now_hand_loan_issued'] ;
$h_till_now_agent_cr = $hand['till_now_hand_agent_cr_issued'] ;
$h_till_now_agent_db = $hand['till_now_hand_agent_db_issued'] ;
$h_till_now_hand_expense = $hand['till_now_hand_hexpense'] ;


$bankData = $records['bank_data'];

$b1_collection = $bankData['bank1_collection_on_date'];
$b1_loan_issue_on_date = $bankData['bank1_loan_issue_on_date'];
$b1_bexpense_amt = $bankData['bank1_bexpense_amt'];
$b1_withdraw_amt = $bankData['bank1_withdraw_amt'];
$b1_agent = $bankData['bank1_ag_cr_amt'] - $bankData['bank1_ag_db_amt'];
$b1_deposit = $bankData['bank1_cr_bdeposit_amt'] - $bankData['bank1_db_deposit_amt'];
$b1_exchanget = $bankData['bank1_cr_bexchange'] - $bankData['bank1_db_bexchange'];
$b1_el = $bankData['bank1_cr_bel_amt_on_date'] - $bankData['bank1_db_bel_amt_on_date'];
$b1_invest = $bankData['bank1_cr_binvest'] - $bankData['bank1_db_binvest'];


$coll_tillNow = $bankData['bank1_total_collection'] + $h_till_now_collection ;
$lon_issue_tillNow = $bankData['bank1_total_loan_issed'] + $h_till_now_loan_issued ;
$agent_tillNow = ($bankData['bank1_ag_cr_amt_upto_date'] + $h_till_now_agent_cr) - ($bankData['bank1_ag_db_amt_upto_date'] + $h_till_now_agent_db);
$expense_tillNow = $bankData['bank1_bexpense_amt_upto_date'] + $h_till_now_hand_expense;



$b2_collection = $bankData['bank2_collection_on_date'];
$b2_loan_issue_on_date = $bankData['bank2_loan_issue_on_date'];
$b2_bexpense_amt = $bankData['bank2_bexpense_amt'];
$b2_withdraw_amt = $bankData['bank2_withdraw_amt'];
$b2_agent = $bankData['bank2_ag_cr_amt'] - $bankData['bank2_ag_db_amt'];
$b2_deposit = $bankData['bank2_cr_bdeposit_amt'] - $bankData['bank2_db_deposit_amt'];
$b2_exchanget = $bankData['bank2_cr_bexchange'] - $bankData['bank2_db_bexchange'];
$b2_el = $bankData['bank2_cr_bel_amt_on_date'] - $bankData['bank2_db_bel_amt_on_date'];
$b2_invest = $bankData['bank2_cr_binvest'] - $bankData['bank2_db_binvest'];


$total_collection = $h_collection + $b1_collection + $b2_collection;
$total_loan_issue = $h_issued + $b1_loan_issue_on_date + $b2_loan_issue_on_date;
$total_agent = $h_agent + $b1_agent + $b2_agent;
$total_exp = $b1_bexpense_amt + $b2_bexpense_amt ;
$total_deposite = $b1_deposit + $b2_deposit + $h_deposite ;
$total_exchange = $h_exchange + $b1_exchanget + $b2_exchanget ;
$total_el = $h_el + $b1_el + $b2_el ;
$total_invest = $h_invest + $b1_invest + $b2_invest ;



?>

<table class="table custom-table">
    <thead>
        <th width='50'></th>
        <th>Hand Cash</th>
        <th><?php echo  $bank1_name ?></th>
        <th><?php echo  $bank2_name ?></th>
        <th>Total</th>
        <th>Till Now </th>
    </thead>
    <tbody>
            <tr>
                <td>Opening Balance</td>
                <td><?php echo $hand_opening_balance; ?></td>
                <td><?php echo  $b1_collection?></td>
                <td><?php echo $bank2_opening ?></td>
                <td><?php echo $total; ?></td>
                <td></td>
            </tr>
            <tr>
                <td>Collection</td>
                <td><?php echo $h_collection; ?></td>
                <td><?php echo  $bank1_opening?></td>
                <td><?php echo $b2_collection ?></td>
                <td><?php echo $total_collection; ?></td>
                <td><?php echo $coll_tillNow; ?></td>
            </tr>
            <tr>
                <td>Loan Issue</td>
                <td><?php echo $h_issued; ?></td>
                <td><?php echo  $b1_loan_issue_on_date?></td>
                <td><?php echo $b2_loan_issue_on_date ?></td>
                <td><?php echo $total_loan_issue; ?></td>
                <td><?php echo $lon_issue_tillNow; ?></td>
            </tr>
            <tr>
                <td>Agent </td>
                <td><?php echo $h_agent; ?></td>
                <td><?php echo  $b1_agent?></td>
                <td><?php echo $b2_agent ?></td>
                <td><?php echo $total_agent; ?></td>
                <td><?php echo $agent_tillNow; ?></td>
            </tr>
            <tr>
                <td>Expenses </td>
                <td></td>
                <td><?php echo  $b1_bexpense_amt?></td>
                <td><?php echo $b2_bexpense_amt ?></td>
                <td><?php echo $total_exp; ?></td>
                <td><?php echo $expense_tillNow; ?></td>
            </tr>
            <tr>
                <td>Deposite </td>
                <td><?php echo $h_deposite; ?></td>
                <td><?php echo  $b1_deposit?></td>
                <td><?php echo $b2_deposit ?></td>
                <td><?php echo $total_deposite; ?></td>
                <td></td>
            </tr>
            <tr>
                <td>Exchange</td>
                <td><?php echo $h_exchange; ?></td>
                <td><?php echo  $b1_exchanget?></td>
                <td><?php echo $b2_exchanget ?></td>
                <td><?php echo $total_exchange; ?></td>
                <td></td>
            </tr>
            <tr>
                <td>EL</td>
                <td><?php echo $h_el; ?></td>
                <td><?php echo  $b1_el?></td>
                <td><?php echo $b2_el ?></td>
                <td><?php echo $total_el; ?></td>
                <td></td>
            </tr>
            <tr>
                <td>Investment</td>
                <td><?php echo $h_invest; ?></td>
                <td><?php echo  $b1_invest?></td>
                <td><?php echo $b2_invest ?></td>
                <td><?php echo $total_invest; ?></td>
                <td></td>
            </tr>
        

    </tbody>
</table>

<?php




function getOpeningBalance($connect, $op_date, $bank_detail, $user_id)
{


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $handCreditQry = $connect->query("SELECT
        SUM(amt) AS hand_credits
        FROM (
            (SELECT COALESCE(SUM(rec_amt), 0) AS amt FROM ct_hand_collection WHERE date(created_date) = '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bank_withdraw WHERE date(created_date) = '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hoti WHERE date(created_date) = '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hinvest WHERE date(created_date) ='$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hexchange WHERE date(created_date) = '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hel WHERE date(created_date) = '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hdeposit WHERE date(created_date) ='$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
        ) AS Hand_Credit_Opening
    ");

    $handCredit = $handCreditQry->fetch()['hand_credits'];
    $handDebitQry = $connect->query("SELECT
        SUM(amt) AS hand_debits
        FROM (
            (SELECT COALESCE(SUM(amount), 0) AS amt FROM ct_db_bank_deposit WHERE date(created_date) = '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hinvest WHERE date(created_date) ='$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(netcash), 0) AS amt FROM ct_db_hissued WHERE date(created_date) = '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hel WHERE date(created_date) = '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hexchange WHERE date(created_date) = '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hexpense WHERE date(created_date) = '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hdeposit WHERE date(created_date) = '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
        ) AS Hand_Debit_Opening
    ");

    $handDebit = $handDebitQry->fetch()['hand_debits'];

    $records[0]['hand_opening'] = intVal($handCredit) - intVal($handDebit);

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $bank_details_arr = explode(',', $bank_detail);
    $i = 0;
    $bank_opening_all = 0;
    $i = 0;
    foreach ($bank_details_arr as $val) {

        // Get Bank Name
        $bankNameQry = $connect->query("SELECT bank_name FROM bank_creation WHERE id = '$val' ");
        $bank_name = $bankNameQry->fetchColumn(); // fetchColumn() directly gets the first column

        // Credit Calculation
        $bankCreditQry = $connect->query("SELECT
        SUM(amt) AS bank_credit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_cash_deposit WHERE date(created_date) = '$op_date' AND to_bank_id = '$val' AND insert_login_id = '$user_id')
            UNION ALL
            (SELECT COALESCE(SUM(credited_amt), 0) AS amt FROM ct_bank_collection WHERE date(created_date) = '$op_date' AND bank_id = '$val' AND insert_login_id = '$user_id')
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bdeposit WHERE date(created_date) = '$op_date' AND bank_id = '$val' AND insert_login_id = '$user_id')
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bel WHERE date(created_date) = '$op_date' AND bank_id = '$val' AND insert_login_id = '$user_id')
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bexchange WHERE date(created_date) = '$op_date' AND to_bank_id = '$val' AND insert_login_id = '$user_id')
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_binvest WHERE date(created_date) = '$op_date' AND bank_id = '$val' AND insert_login_id = '$user_id')
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_boti WHERE date(created_date) = '$op_date' AND to_bank_id = '$val' AND insert_login_id = '$user_id')
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bag WHERE date(created_date) = '$op_date' AND bank_id = '$val' AND insert_login_id = '$user_id')
        ) AS Bank_Credit_Opening
    ");
        $bankCredit = $bankCreditQry->fetch()['bank_credit'];

        // Debit Calculation
        $bankDebitQry = $connect->query("SELECT
        SUM(amt) AS bank_debit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_cash_withdraw WHERE date(created_date) = '$op_date' AND from_bank_id = '$val' AND insert_login_id = '$user_id')
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bdeposit WHERE date(created_date) = '$op_date' AND bank_id = '$val' AND insert_login_id = '$user_id')
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bel WHERE date(created_date) = '$op_date' AND bank_id = '$val' AND insert_login_id = '$user_id')
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bexchange WHERE date(created_date) = '$op_date' AND from_acc_id = '$val' AND insert_login_id = '$user_id')
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bexpense WHERE date(created_date) = '$op_date' AND bank_id = '$val' AND insert_login_id = '$user_id')
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_binvest WHERE date(created_date) = '$op_date' AND bank_id = '$val' AND insert_login_id = '$user_id')
            UNION ALL
            (SELECT COALESCE(SUM(netcash), 0) AS amt FROM ct_db_bissued WHERE date(created_date) = '$op_date' AND li_bank_id = '$val' AND insert_login_id = '$user_id')
            UNION ALL 
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bag WHERE date(created_date) = '$op_date' AND bank_id = '$val' AND insert_login_id = '$user_id')
        ) AS Bank_debit_Opening
    ");
        $bankDebit = $bankDebitQry->fetch()['bank_debit'];

        // Calculate Opening
        $opening = intval($bankCredit) - intval($bankDebit);
        $bank_opening_all += $opening;

        // Store in separate variables
        if ($i == 0) {
            $records[0]['bank1_name'] = $bank_name; 
    $records[0]['bank1_opening'] = $opening; 
        } elseif ($i == 1) {
          $records[0]['bank2_name'] = $bank_name; 
    $records[0]['bank2_opening'] = $opening;
        }

        $i++;
    }



    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $qry = $connect->query("SELECT `user_id` from `user` where ag_id IN (SELECT ag.ag_id FROM agent_creation ag JOIN `user` us ON FIND_IN_SET(ag.ag_id,us.agentforstaff) where us.user_id = '$user_id')  ");
    //without while it will not give all the agent ids
    $ag_ids = [];
    while ($rww = $qry->fetch()) {
        $ag_ids[] = $rww["user_id"];
    }
    $ag_ids = implode(',', $ag_ids);


    $agentCollQry = $connect->query("SELECT
        SUM(amt) AS agent_coll
        FROM (
            (SELECT COALESCE(SUM(total_paid_track), 0) AS amt FROM collection
            WHERE date(created_date) = '$op_date' AND FIND_IN_SET(insert_login_id,'$ag_ids') ORDER BY created_date DESC LIMIT 1)
            
        ) AS Agent_Collection_Credit_Opening
    ");

    $agentCollCredit = $agentCollQry->fetch()['agent_coll'];

    //only for collections we need user ids of agents
    $qry = $connect->query("SELECT ag.ag_id FROM agent_creation ag JOIN user us ON FIND_IN_SET(ag.ag_id,us.agentforstaff) where us.user_id = '$user_id'");
    $ag_ids = [];
    while ($rww = $qry->fetch()) {
        $ag_ids[] = $rww["ag_id"];
    }
    $ag_ids = implode(',', $ag_ids);


    $agentIssueQry = $connect->query("SELECT 
    COALESCE(SUM(amt), 0) AS agent_issue 
FROM (
    SELECT 
        COALESCE(SUM(
            COALESCE(cash, 0) + 
            COALESCE(cheque_value, 0) + 
            COALESCE(transaction_value, 0)
        ), 0) AS amt 
    FROM loan_issue 
    WHERE 
        DATE(created_date) = '$op_date' 
        AND FIND_IN_SET(agent_id,'$ag_ids')   AND agent_id IS NOT NULL AND insert_login_id = '$user_id'
      
) AS Agent_Issue_Debit_Opening
    ");

    $agentIssueDebit = $agentIssueQry->fetch()['agent_issue'];

    $agent_CL_op = intVal($agentCollCredit) - intVal($agentIssueDebit);

    //

    $agentCreditQry = $connect->query("SELECT
        SUM(amt) AS agent_credit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hag WHERE date(created_date) = '$op_date' AND FIND_IN_SET(ag_id,'$ag_ids') and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            
        ) AS Agent_Credit_Opening
    ");

    $agentCredit = $agentCreditQry->fetch()['agent_credit'];

    $agentDebitQry = $connect->query("SELECT
        SUM(amt) AS agent_debit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hag WHERE date(created_date) = '$op_date' AND FIND_IN_SET(ag_id,'$ag_ids') and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            
        ) AS Agent_Debit_Opening
    ");

    $agentDebit = $agentDebitQry->fetch()['agent_debit'];

    $agent_hand_op = intVal($agentDebit) - intVal($agentCredit);

    //

    $agentCreditQry = $connect->query("SELECT
        SUM(amt) AS agent_credit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bag WHERE date(created_date) = '$op_date' AND FIND_IN_SET(ag_id,'$ag_ids') and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            
        ) AS Agent_Credit_Opening
    ");

    $agentCredit = $agentCreditQry->fetch()['agent_credit'];

    $agentDebitQry = $connect->query("SELECT
        SUM(amt) AS agent_debit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bag WHERE date(created_date) = '$op_date' AND FIND_IN_SET(ag_id,'$ag_ids') and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            
        ) AS Agent_Debit_Opening
    ");

    $agentDebit = $agentDebitQry->fetch()['agent_debit'];

    $agent_bank_op = intVal($agentDebit) - intVal($agentCredit);



    $records[0]['agent_opening'] = $agent_hand_op + $agent_bank_op + $agent_CL_op;

    $records[0]['hand_opening'] = $records[0]['hand_opening'] - $agent_hand_op; //this will subract the hand debited amount for the agent with hand closing cash

    $records[0]['opening_balance'] = $records[0]['hand_opening'] + $bank_opening_all + $records[0]['agent_opening'];


    $qry = $connect->query("SELECT bank_untrkd from cash_tally where date(created_date) = '$op_date' and insert_login_id = '$user_id' ");
    if ($qry->rowCount() > 0) {
        $records[0]['bank_untrkd'] = $qry->fetch()['bank_untrkd'];
    } else {
        $records[0]['bank_untrkd'] = '0,0';
    }

    return $records;
}



function getClosingBalance($connect, $closing_date, $bank_detail, $user_id)
{
    $user_where = "";
    if ($user_id != '') {
        $user_where = "AND insert_login_id = '$user_id' ";
    }

    $handcollection = $connect->query("SELECT
    (SELECT COALESCE(SUM(rec_amt), 0) FROM ct_hand_collection WHERE date(created_date) = '$closing_date' $user_where) AS ct_hand_collection,
    (SELECT COALESCE(SUM(netcash), 0) FROM ct_db_hissued WHERE date(created_date) = '$closing_date' $user_where) AS ct_hand_issued,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_cr_hag WHERE date(created_date) = '$closing_date' $user_where) AS ct_cr_agent,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_db_hag WHERE date(created_date) = '$closing_date' $user_where) AS ct_db_agent,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_db_hexpense WHERE date(created_date) = '$closing_date' $user_where) AS hand_expense,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_cr_hdeposit WHERE date(created_date) = '$closing_date' $user_where) AS hand_cr_deposit,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_db_hdeposit WHERE date(created_date) = '$closing_date' $user_where) AS hand_db_deposite,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_cr_hexchange WHERE date(created_date) = '$closing_date' $user_where) AS hand_cr_exchange,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_db_hexchange WHERE date(created_date) = '$closing_date' $user_where) AS hand_db_exchange,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_cr_hel WHERE date(created_date) = '$closing_date' $user_where) AS hand_cr_el,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_db_hel WHERE date(created_date) = '$closing_date' $user_where) AS hand_db_el,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_cr_hinvest WHERE date(created_date) = '$closing_date' $user_where) AS hand_cr_hinvest,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_db_hinvest WHERE date(created_date) = '$closing_date' $user_where) AS hand_db_hinvest,
    
    (SELECT COALESCE(SUM(rec_amt), 0) FROM ct_hand_collection WHERE date(created_date) <= '$closing_date' $user_where) AS till_now_hand_collection,
    (SELECT COALESCE(SUM(netcash), 0) FROM ct_db_hissued WHERE date(created_date) <= '$closing_date' $user_where) AS till_now_hand_loan_issued,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_cr_hag WHERE date(created_date) <= '$closing_date' $user_where) AS till_now_hand_agent_cr_issued,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_db_hag WHERE date(created_date) <= '$closing_date' $user_where) AS till_now_hand_agent_db_issued,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_db_hexpense WHERE date(created_date) <= '$closing_date' $user_where) AS till_now_hand_hexpense
    
    
");

$handCollection = $handcollection->fetch(PDO::FETCH_ASSOC);


$bankQry = $connect->query("SELECT 
    bn.bank_name,
    bn.id AS bank_id,
    SUM(CASE WHEN bc.created_date = '$closing_date' THEN bc.credited_amt ELSE 0 END) AS collection_on_date,
    SUM(CASE WHEN bi.created_date = '$closing_date' THEN bi.netcash ELSE 0 END) AS loan_issue_on_date,
    SUM(CASE WHEN db.created_date = '$closing_date' THEN db.amt ELSE 0 END) AS ag_db_amt,
    SUM(CASE WHEN cr.created_date = '$closing_date' THEN cr.amt ELSE 0 END) AS ag_cr_amt,
    SUM(CASE WHEN be.created_date = '$closing_date' THEN be.amt ELSE 0 END) AS bexpense_amt,
    SUM(CASE WHEN bw.created_date = '$closing_date' THEN bw.amt ELSE 0 END) AS withdraw_amt,
    SUM(CASE WHEN bd.created_date = '$closing_date' THEN bd.amt ELSE 0 END) AS db_deposit_amt,
    SUM(CASE WHEN cbd.created_date = '$closing_date' THEN cbd.amt ELSE 0 END) AS cr_bdeposit_amt,
    SUM(CASE WHEN bex.created_date = '$closing_date' THEN bex.amt ELSE 0 END) AS cr_bexchange,
    SUM(CASE WHEN dbex.created_date = '$closing_date' THEN dbex.amt ELSE 0 END) AS db_bexchange,
    SUM(CASE WHEN bel.created_date = '$closing_date' THEN bel.amt ELSE 0 END) AS cr_bel_amt_on_date,
    SUM(CASE WHEN dbel.created_date = '$closing_date' THEN dbel.amt ELSE 0 END) AS db_bel_amt_on_date,
    SUM(CASE WHEN dbinv.created_date = '$closing_date' THEN dbinv.amt ELSE 0 END) AS db_binvest,
    SUM(CASE WHEN crinv.created_date = '$closing_date' THEN crinv.amt ELSE 0 END) AS cr_binvest,

    (SELECT COALESCE(SUM(credited_amt), 0) FROM ct_bank_collection  WHERE created_date <= '$closing_date' AND bank_id IN ($bank_detail) AND insert_login_id = '$user_id') AS total_collection,
    (SELECT COALESCE(SUM(netcash), 0) FROM ct_db_bissued WHERE created_date <= '$closing_date' AND li_bank_id IN ($bank_detail)AND insert_login_id = '$user_id') AS total_loan_issed,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_db_bag WHERE created_date <= '$closing_date'AND bank_id IN ($bank_detail) AND insert_login_id = '$user_id') AS ag_db_amt_upto_date,
    (SELECT COALESCE(SUM(amt), 0)FROM ct_cr_bag WHERE created_date <= '$closing_date'AND bank_id IN ($bank_detail)AND insert_login_id = '$user_id') AS ag_cr_amt_upto_date,
    (SELECT COALESCE(SUM(amt), 0)FROM ct_db_bexpense WHERE created_date <= '$closing_date' AND bank_id IN ($bank_detail) AND insert_login_id = '$user_id') AS bexpense_amt_upto_date


FROM bank_creation bn
LEFT JOIN ct_bank_collection bc ON bc.bank_id = bn.id AND bc.insert_login_id = '$user_id'
LEFT JOIN ct_db_bissued bi ON bi.li_bank_id = bn.id AND bi.insert_login_id = '$user_id'
LEFT JOIN ct_cr_bag cr ON cr.bank_id = bn.id AND cr.insert_login_id = '$user_id'
LEFT JOIN ct_db_bag db ON db.bank_id = bn.id AND db.insert_login_id = '$user_id'
LEFT JOIN ct_db_bexpense be ON be.bank_id = bn.id AND be.insert_login_id = '$user_id'
LEFT JOIN ct_cr_bank_withdraw bw  ON bw.from_bank_id = bn.id AND bw.insert_login_id = '$user_id'
LEFT JOIN ct_db_bdeposit bd ON bd.bank_id = bn.id AND bd.insert_login_id = '$user_id'
LEFT JOIN ct_cr_bdeposit cbd  ON cbd.bank_id = bn.id AND cbd.insert_login_id = '$user_id'
LEFT JOIN ct_cr_bexchange bex  ON bex.from_bank_id = bn.id AND bex.insert_login_id = '$user_id'
LEFT JOIN ct_db_bexchange dbex ON dbex.from_acc_id = bn.id AND dbex.insert_login_id = '$user_id'
LEFT JOIN ct_cr_bel bel ON bel.bank_id = bn.id AND bel.insert_login_id = '$user_id'
LEFT JOIN ct_db_bel dbel ON dbel.bank_id = bn.id AND dbel.insert_login_id = '$user_id'
LEFT JOIN ct_db_binvest dbinv  ON dbinv.bank_id = bn.id AND dbinv.insert_login_id = '$user_id'
LEFT JOIN ct_cr_binvest crinv ON crinv.bank_id = bn.id AND crinv.insert_login_id = '$user_id'


WHERE 
    bn.id IN ($bank_detail)
GROUP BY 
    bn.id;
");

   $bankData = [];
$i = 1;
while ($bank = $bankQry->fetch(PDO::FETCH_ASSOC)) {
    foreach ($bank as $key => $value) {
        $bankData["bank{$i}_{$key}"] = $value;
    }
    $i++;
}

 return [
        'hand_summary' => $handCollection,
        'bank_data' => $bankData
    ];
    
}

// }

// Close the database connection
$connect = null;
