<?php

include('../../ajaxconfig.php');
@session_start();
if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];
}

// Step 1: Fetch role_type of the user
$userRes = $connect->query("SELECT role_type FROM user WHERE user_id = $user_id");
$userRow = $userRes->fetch();
$role_type = $userRow['role_type'];

// Step 2: Apply logic for fetching data
if ($role_type == 7) {
    // Role 7 (Admin)→ See all records
    $sql = $connect->query("
        SELECT ncp.cus_id,ncp.cus_name,ncp.mobile,ncp.insert_login_id,ncp.created_date,a.area_name FROM new_cus_promo ncp JOIN area_list_creation a ON ncp.area = a.area_id
        WHERE ncp.cus_id NOT IN (SELECT cus_id FROM customer_register)
    ");
} else {
    // Other roles → See only their own records
    $sql = $connect->query("
        SELECT ncp.cus_id,ncp.cus_name,ncp.mobile,ncp.insert_login_id,ncp.created_date,a.area_name FROM new_cus_promo ncp JOIN area_list_creation a ON ncp.area = a.area_id
        WHERE ncp.cus_id NOT IN (SELECT cus_id FROM customer_register)
          AND ncp.insert_login_id = $user_id
    ");
}

?>


<table class="table custom-table" id='new_promo_table' data-id='new_promotion'>
    <thead>
        <th width="10%">Date</th>
        <th>Customer ID</th>
        <th>Customer Name</th>
        <th>Mobile No.</th>
        <th>Area</th>
        <th>User Name</th>
        <th>Action</th>
        <th>Promotion Chart</th>
        <th>Follow Date</th>
    </thead>
    <tbody>
        <?php while ($row =  $sql->fetch()) { ?>
            <tr>
                <td><?php echo date('d-m-Y', strtotime($row['created_date'])); ?></td>
                <td><?php echo $row['cus_id']; ?></td>
                <td><?php echo $row['cus_name']; ?></td>
                <td><?php echo $row['mobile']; ?></td>
                <td><?php echo $row['area_name']; ?></td>
                   <td>
                    <?php
                    $qry = $connect->query("SELECT fullname FROM user WHERE user_id = '" . $row['insert_login_id'] . "'");
                        $full_name = $qry->fetch()['fullname'];
                        echo($full_name);
                    ?></td>
                <td>
                    <?php  //for intrest or not intrest choice to make
                    // if($row['int_status'] == '' or $row['int_status'] == NULL){

                    $action = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> ";

                    $action .= "<a class='intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Interested</span></a>
                            <a class='not-intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Not Interested</span></a>";
                    $action .= "</div></div>";
                    echo $action;

                    // }elseif($row['int_status'] == '0'){
                    //     echo 'Interested';
                    // }elseif($row['int_status'] == '1'){
                    //     echo 'Not Interested';
                    // }
                    ?>
                </td>
                <td>
                    <?php //for promotion chart
                    echo "<input type='button' class='btn btn-primary promo-chart' data-id='" . $row['cus_id'] . "' data-toggle='modal' data-target='#promoChartModal' value='View' />";
                    ?>
                </td>
                <td>
                    <?php
                    $qry = $connect->query("SELECT follow_date FROM new_promotion WHERE cus_id = '" . $row['cus_id'] . "' ORDER BY created_date DESC limit 1");
                    //take last promotion follow up date inserted from new promotion table
                    if ($qry->rowCount() > 0) {
                        $fdate = $qry->fetch()['follow_date'];
                        echo date('d-m-Y', strtotime($fdate));
                    } else {
                        echo '';
                    }
                    ?></td>

            </tr>
        <?php } ?>

    </tbody>
</table>

<script>
    $('#new_promo_table').dataTable({
        // 'processing': true,
        'iDisplayLength': 10,
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        dom: 'lBfrtip',
        buttons: [{
                extend: 'excel',
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
        ],
        'drawCallback': function() {
            searchFunction('new_promo_table');
        }
    })
    $('#new_promo_table tbody tr').not('th').each(function() {
        let tddate = $(this).find('td:eq(9)').text(); // Get the text content of the 8th td element (Follow date)
        let datecorrection = tddate.split("-").reverse().join("-").replaceAll(/\s/g, ''); // Correct the date format
        let values = new Date(datecorrection); // Create a Date object from the corrected date
        values.setHours(0, 0, 0, 0); // Set the time to midnight for accurate date comparison

        let curDate = new Date(); // Get the current date
        curDate.setHours(0, 0, 0, 0); // Set the time to midnight for accurate date comparison

        let colors = {
            'past': 'FireBrick',
            'current': 'DarkGreen',
            'future': 'CornflowerBlue'
        }; // Define colors for different date types

        if (tddate != '' && values != 'Invalid Date') { // Check if the extracted date and the created Date object are valid

            if (values < curDate) { // Compare the extracted date with the current date
                $(this).find('td:eq(9)').css({
                    'background-color': colors.past,
                    'color': 'white'
                }); // Apply styling for past dates
            } else if (values > curDate) {
                $(this).find('td:eq(9)').css({
                    'background-color': colors.future,
                    'color': 'white'
                }); // Apply styling for future dates
            } else {
                $(this).find('td:eq(9)').css({
                    'background-color': colors.current,
                    'color': 'white'
                }); // Apply styling for the current date
            }
        }
    });
</script>
<style>
    .dropdown-content {
        color: black;
    }

    @media (max-width: 598px) {
        #new_promo_div {
            overflow: auto;
        }
    }
</style>

<?php
// Close the database connection
$connect = null;
?>