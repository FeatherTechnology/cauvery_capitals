$(document).ready(function () {
    //Collection Report Table
    $('#reset_btn').click(function () {
        noDuePayReportTable();
    })
});

function noDuePayReportTable() {
    $('#no_pay_due_report_table').DataTable().destroy();
    $('#no_pay_due_report_table').DataTable({
        "order": [
            [0, "desc"]
        ],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'reportFile/no_due_pay/getNoDuePayRreport.php',
            'data': function (data) {
                var search = $('input[type=search]').val();
                data.search = search;
                data.from_date = $('#from_date').val();
            }
        },
        dom: 'lBfrtip',
        buttons: [{
            text: 'Excel',
            action: function (e, dt, node, config) {
                // Generate fresh title & filename every click
                const {
                    title,
                    filename
                } = generateReportTitle('No Due Pay Report List');

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
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        'drawCallback': function () {
            searchFunction('no_pay_due_report_table');
        }
    });
}
