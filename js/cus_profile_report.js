$(document).ready(function () {

    //Customer Profile Report Table
    $('#cust_profile_report_table').DataTable({
        "order": [
            [0, "desc"]
        ],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'reportFile/customer_profile/getCustomerProfileReport.php',
            'data': function (data) {
                var search = $('input[type=search]').val();
                data.search = search;
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
                } = generateReportTitle('Customer Profile Report List');

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
            searchFunction('cust_profile_report_table');
            paginationFunction('cust_profile_report_table');
        }
    });

});
