$(document).ready(function () {
    //Collection Report Table
    $('#reset_btn').click(function () {
        collectionReportTable();
    })
});

function collectionReportTable() {
    $('#principal_interest_table').DataTable().destroy();
    $('#principal_interest_table').DataTable({
        "order": [
            [0, "desc"]
        ],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'reportFile/principal_interest/getPrincipalInterest.php',
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
                } = generateReportTitle('Principal - Interest Report List');

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
            var columnsToSum = [13, 14, 15, 16, 17, 18];

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
            searchFunction('principal_interest_table');
            paginationFunction('principal_interest_table');
        }
    });
}
