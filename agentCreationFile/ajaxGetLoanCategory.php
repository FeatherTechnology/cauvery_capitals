

<?php 
include('../ajaxconfig.php');

$loan_category_arr = array();

$Qry = $connect->query("SELECT * from  loan_category where status=0 ");
$i=0;
while($row = $Qry->fetch()){
    $loan_category[$i] = $row['loan_category_name'];
    $i++;
}
// print_r($loan_category);
foreach($loan_category as $cat ){
    
    $result=$connect->query("SELECT * FROM loan_category_creation where loan_category_creation_id = '".$cat."' and status=0");
    while( $row = $result->fetch()){
        $loan_category_creation_id = $row['loan_category_creation_id'];
        $loan_category_creation_name = $row['loan_category_creation_name'];
        $loan_category_arr[] = array("loan_category_name_id" => $loan_category_creation_id, "loan_category_name" => $loan_category_creation_name);
    }
}

echo json_encode($loan_category_arr);

// Close the database connection
$connect = null;
?>