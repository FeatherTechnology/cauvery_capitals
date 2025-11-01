<?php
//  1. Set the log file path
$logFile = __DIR__ . '/monthly_update_log.txt';

// 2. Define the logMessage() function
function logMessage($message)
{
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// 3. Prevent script from running on non-1st days
if (date('d') !== '1') {
    logMessage("Not the 1st of the month. Script exited.");
    exit;
}
logMessage(" Script started at " . date('h:i:s A'));

include_once(__DIR__ . '/../../ajaxconfig.php');

logMessage("Database connection loaded. Fetching customer profile IDs...");

try {
   $qry = $connect->query("SELECT ii.req_id as cp_req_id FROM in_issue ii WHERE ii.status = 0 AND ii.cus_status BETWEEN 14 AND 17 ORDER BY ii.id ASC");
$customer_req_id = array_column($qry->fetchAll(PDO::FETCH_ASSOC), 'cp_req_id');
} catch (Exception $e) {
    logMessage(" Database error: " . $e->getMessage());
    exit;
}

logMessage("Total cp_ids fetched: " . count($customer_req_id));

// 2. Split req_ids into chunks of 2 (optional, for grouping only)
$chunks = array_chunk($customer_req_id, 2);
// $chunks = [
//   [74376, 74370],
//   [74410, 74292],
//   [74378 , 73416] ,       
//   [74359 , 74514]        
// ];

foreach ($chunks as $chunk) {
    // Process each req_id individually inside the chunk
    foreach ($chunk as $req_id) {
        logMessage("Processing req_id: $req_id");

        // Prepare POST data with single reqID (because resetCustomerStatus.php expects only one)
        $postData = ['reqID' => $req_id];

        // Call resetCustomerStatus.php for one req_id
        $ch = curl_init('http://localhost/cauvery_capitals\collectionFile\resetCustomerStatus.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $responseJSON = curl_exec($ch);
        curl_close($ch);
       $response = json_decode(trim($responseJSON), true);


     if (!empty($response) && isset($response['req_id'])) {
            $pending_sts = (isset($response['pending_customer'][0]) && $response['pending_customer'][0] === true) ? 'true' : 'false';
            $od_sts = (isset($response['od_customer'][0]) && $response['od_customer'][0] === true )? 'true' : 'false';
            $due_nil_sts = (isset($response['due_nil_customer'][0]) && $response['due_nil_customer'][0] === true) ? 'true' : 'false';
            $closed_sts = (isset($response['closed_customer'][0]) && $response['closed_customer'][0] === true) ? 'true' : 'false';
            $balAmnt = $response['balAmnt'][0] ?? 0;
            $payableAmnt = $response['payable_as_req'][0] ?? 0;
          
            // Call updateCustomerStatus.php for this req_id
            $ch2 = curl_init('http://localhost/cauvery_capitals\updateCustomerStatusFile\updateCustomerStatus.php');
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLOPT_POSTFIELDS, [
                'req_id' => $req_id,
                'pending_sts' => $pending_sts,
                'od_sts' => $od_sts,
                'due_nil_sts' =>  $due_nil_sts,
                'closed_sts' =>$closed_sts,
                'bal_amt' => $balAmnt,
                'payable' => $payableAmnt,
                'userid' => '2'
            ]);
            $updateResponse = curl_exec($ch2);
             if (curl_errno($ch2)) {
            logMessage(" CURL error for updateCustomerStatus: " . curl_error($ch2));
            curl_close($ch2);
            continue;
        }
            curl_close($ch2);
            logMessage(" Updated cp_id $req_id: $updateResponse");


        } else {
            logMessage(" No data found for req_id $req_id <br>");
        }
    }
}
function normalize($value) {
    return (strpos(strtolower($value), 'true') === 0) ? 'true' : 'false';
}
?>
