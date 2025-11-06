<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table" id="chequeInfo_table_data">
    <div id="hiddenExport" style="display:none;"></div>
    <thead>
        <tr>
            <th width="10%"> S.No </th>
            <th> Holder type </th>
            <th> Holder Name </th>
            <th> Relationship </th>
            <th> Bank Name </th>
            <th> Cheque Count </th>
            <th> Uploads </th>
            <th> ACTION </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['req_id'];
        $chequeInfo = $connect->query("SELECT * FROM `cheque_info` where req_id = '$req_id' order by id desc");

        $i = 1;
        while ($cheque = $chequeInfo->fetch()) {
            $fam_id = $cheque["holder_relationship_name"];
            $result = $connect->query("SELECT CONCAT(first_name, ' ', last_name) AS famname FROM `verification_family_info` where id='$fam_id'");
            $row = $result->fetch();

            $doc_upd_name = '';
            $id = $cheque["id"];
            $updresult = $connect->query("SELECT upload_cheque_name FROM `cheque_upd` where cheque_table_id = '$id'");
            $a = 1;
            while ($upd = $updresult->fetch()) {
                $docName = $upd['upload_cheque_name'];
                $doc_upd_name .= "<a href=uploads/verification/cheque_upd/";
                $doc_upd_name .= $docName;
                $doc_upd_name .= " target='_blank'>";
                $doc_upd_name .=  $docName . ' ';
                $doc_upd_name .= "</a>,";
                $a++;
            }
        ?>

            <tr>
                <td><?php echo $i; ?></td>

                <td><?php if ($cheque["holder_type"] == '0') {
                        echo 'Customer';
                    } elseif ($cheque["holder_type"] == '1') {
                        echo 'Guarantor';
                    } elseif ($cheque["holder_type"] == '2') {
                        echo 'Family Members';
                    }  ?></td>

                <td> <?php if ($cheque["holder_type"] == '0' || $cheque["holder_type"] == '1') {
                            echo $cheque["holder_name"];
                        } elseif ($cheque["holder_type"] == '2') {
                            echo $row["famname"];
                        } ?></td>
                <td><?php echo $cheque["cheque_relation"]; ?></td>
                <td><?php echo $cheque["chequebank_name"]; ?></td>
                <td><?php echo $cheque["cheque_count"]; ?></td>
                <td><?php echo rtrim($doc_upd_name, ','); ?></td>

                <td>
                    <?php if ($doc_upd_name == '') { ?>
                        <a class="cheque_info_edit" value="<?php echo $cheque['id']; ?>" style="text-decoration: underline;"> Entry </a>
                    <?php } ?>
                </td>

            </tr>

        <?php $i = $i + 1;
        }     ?>
    </tbody>
</table>


<script type="text/javascript">
    $(function() {
        $('#chequeInfo_table_data').DataTable({
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:first').html(dataIndex + 1);
            },
            "drawCallback": function(settings) {
                this.api().column(0).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
                searchFunction('chequeInfo_table_data');
            },
            dom: 'lBfrtip',
            buttons: [{
                    text: 'Excel',
                    action: function(e, dt, node, config) {
                        // Generate fresh title & filename every click
                        const {
                            title,
                            filename
                        } = generateReportTitle('Cheque Info List');

                        // Create a hidden temporary export button
                        const tmpBtn = new $.fn.dataTable.Buttons(dt, {
                            buttons: [{
                                extend: 'excelHtml5',
                                title: title,
                                filename: filename,
                            }]
                        }).container().appendTo($('#hiddenExport'));

                        // Trigger that button’s click programmatically
                        tmpBtn.find('.buttons-excel').click();

                        // Remove the temporary button after export
                        tmpBtn.remove();
                    }
                },
                {
                    extend: 'colvis',
                    collectionLayout: 'fixed four-column',
                }
            ],
        });
    });
</script>