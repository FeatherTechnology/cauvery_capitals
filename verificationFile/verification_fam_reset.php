<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table " id="famdatatable">
    <div id="hiddenExport" style="display:none;"></div>
    <thead>
        <tr>
            <th width="25%">S.No</th>
            <th>Name</th>
            <th>Relationship</th>
            <th>ACTION</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $cus_id = preg_replace('/\D/', '', $_POST['cus_id']);
        $famInfo = $connect->query("SELECT * , CONCAT(first_name, ' ', last_name) AS famname FROM `verification_family_info` where cus_id = '$cus_id' order by id desc");

        $i = 1;
        while ($fam = $famInfo->fetch()) {
        ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $fam["famname"]; ?></td>
                <td><?php echo $fam["relationship"]; ?></td>
                <td>
                    <a id="verification_fam_edit" value="<?php echo $fam['id']; ?>"> <span class="icon-border_color"></span></a> &nbsp
                    <a id="verification_fam_delete" value="<?php echo $fam['id']; ?>"> <span class='icon-trash-2'></span> </a>
                </td>
            </tr>
        <?php $i = $i + 1;
        }
        ?>
    </tbody>
</table>

<script type="text/javascript">
    $(function() {
        $('#famdatatable').DataTable({
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
                searchFunction('famdatatable');
            },
            dom: 'lBfrtip',
            buttons: [{
                    text: 'Excel',
                    action: function(e, dt, node, config) {
                        // Generate fresh title & filename every click
                        const {
                            title,
                            filename
                        } = generateReportTitle('Family Info List');

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
<?php
// Close the database connection
$connect = null;
?>