$(document).ready(function () {
    //Collection Report Table
    $('#reset_btn').click(function () {
        console.log("kk");
        dayEndReportTable();
    })
});

function dayEndReportTable(){
   var search_date = $('#search_date').val();

    $.ajax({
        url: 'reportFile/day_end_report/get_day_end_report.php',
        data: { 'search_date': search_date },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#day_end_div').empty()
            $('#day_end_div').html(response)
        }
    })
}
