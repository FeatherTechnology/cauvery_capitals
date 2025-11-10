$(document).ready(function () {

    $('#from_date').change(function () {
        const fromDate = $(this).val();
        const toDate = $('#to_date').val();
        $('#to_date').attr('min', fromDate);

        // Check if from_date is greater than to_date
        if (toDate && fromDate > toDate) {
            $('#to_date').val(''); // Clear the invalid value
        }
    });

    //Loan Report Table
    $('#reset_btn').click(function () {
        loanIssueReportTable();
    })

         $('#download_btn').click(function () {
        const from_date = $('#from_date').val();
        const to_date = $('#to_date').val();
        const tableId = "loan_issue_report_table"; // your table id
        const reportName = "Loan_Issue_Report";
        if (!from_date || !from_date) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Dates',
                text: 'Please select both From and To dates before downloading.',
                confirmButtonColor: '#0c70ab'
            });
            return;
        }
        $.ajax({
            url: 'reportFile/loan_issue/getLoanIssueReport.php',
            type: 'POST',
            dataType: 'json',
            data: {
                from_date: from_date,
                to_date: to_date,
                download: 1
            },
            success: function (response) {
                // ✅ If response is valid
                if (response && response.data && response.data.length > 0) {
                    exportToExcel(tableId, response.data, reportName);
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'No Data Found',
                        text: 'No records found for the selected date range.',
                        confirmButtonColor: '#0c70ab'
                    });
                }
            },
            error: function (xhr) {
                console.error("AJAX Error:", xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Response format error',
                    text: 'The server returned invalid data. Please check PHP output.',
                    confirmButtonColor: '#d33'
                });
            }
        });
    });

});

function loanIssueReportTable() {
    $('#loan_issue_report_table').DataTable().destroy();
    $('#loan_issue_report_table').DataTable({
        "order": [
            [0, "asc"]
        ],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'reportFile/loan_issue/getLoanIssueReport.php',
            'data': function (data) {
                var search = $('input[type=search]').val();
                data.search = search;
                data.from_date = $('#from_date').val();
                data.to_date = $('#to_date').val();
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
                } = generateReportTitle('Loan Issue Report List');

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
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api();

            // Remove formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Array of column indices to sum
            var columnsToSum = [20, 21, 22, 23, 24, 25, 26, 27];
            // Loop through each column index
            columnsToSum.forEach(function (colIndex) {
                // Total over all pages for the current column
                var total = api
                    .column(colIndex)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Update footer for the current column
                $(api.column(colIndex).footer()).html(`<b>` + total.toLocaleString() + `</b>`);
            });
        },
        'drawCallback': function () {
            searchFunction('loan_issue_report_table');
            paginationFunction('loan_issue_report_table');
        }
    });
}