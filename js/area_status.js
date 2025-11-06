
// Document is ready
$(document).ready(function () {

    //Mapping Type Change

    $('#area').click(function () {
        var area_status = $('input[name=area_status]:checked').val();
        if (area_status == 'area') {
            $('.area_status').show();
            dT1();
        }
    })

    $(function () {
        $('.area_status').show();
        dT1();
    })
});//document ready end
function dT1() {
    var table = $('#area_status_table').DataTable();
    table.destroy();
    $('#area_status_table').DataTable({
        "order": [[0, "desc"]],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        // 'bInfo':false, // to remove bottom paging info (showing 0 to 0 of 0),

        'ajax': {
            'url': 'ajaxFetch/ajaxGetAreaFetch.php',
            'data': function (data) {
                var search = $('#search').val();
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
                } = generateReportTitle('Area Status List');

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
            searchFunction('area_status_table');
            paginationFunction('area_status_table');
        }
    });
}

//For Enable
function enable(area_id) {
    var action = "enable";
    if (confirm('Do you want to Enable this Area?')) {
        $.ajax({
            url: 'areaStatus/enableDisableArea.php',
            data: { 'area_id': area_id, 'action': action },
            dataType: 'json',
            type: 'post',
            cache: false,
            success: function (response) {
                if (response.includes('Successfully')) {
                    dT1();
                    $('#area_enable').show();
                    setTimeout(function () {
                        $('#area_enable').fadeOut('fast');
                    }, 2000);
                }
            }
        })
    }
}

//For Disable
function disable(area_id) {
    var action = "disable";

    if (confirm('Do you want to Disable this Area?')) {
        $.ajax({
            url: 'areaStatus/enableDisableArea.php',
            data: { 'area_id': area_id, 'action': action },
            dataType: 'json',
            type: 'post',
            cache: false,
            success: function (response) {
                if (response.includes('Successfully')) {
                    dT1();
                    $('#area_disable').show();
                    setTimeout(function () {
                        $('#area_disable').fadeOut('fast');
                    }, 2000);
                }
            }
        })
    }

}



