const scheme_choices = new Choices('#scheme_name', {
    removeItemButton: true,
    noChoicesText: 'Select Scheme Name',
    allowHTML: true
});

// Document is ready
$(document).ready(function () {

    // remove delete option for last child
    $('#delete_row:last').filter(':last').removeClass("deleterow");

    {//To Order Alphabetically
        var firstOption = $("#loan_category_name option:first-child");
        $("#loan_category_name").html($("#loan_category_name option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#loan_category_name").prepend(firstOption);
    }

    // Modal Box for Category Name
    {
        $("#loancategorynameCheck").hide();
        $(document).on("click", "#submitLoanCategoryModal", function () {
            var loan_category_creation_id = $("#loan_category_creation_id").val();
            var loan_category_creation_name = $("#loan_category_creation_name").val();
            if (loan_category_creation_name != "") {
                $.ajax({
                    url: 'loancategoryFile/ajaxInsertLoanCategory.php',
                    type: 'POST',
                    data: { "loan_category_creation_name": loan_category_creation_name, "loan_category_creation_id": loan_category_creation_id },
                    cache: false,
                    success: function (response) {
                        var insresult = response.includes("Exists");
                        var updresult = response.includes("Updated");
                        if (insresult) {
                            $('#categoryInsertNotOk').show();
                            setTimeout(function () {
                                $('#categoryInsertNotOk').fadeOut('fast');
                            }, 2000);
                        } else if (updresult) {
                            $('#categoryUpdateOk').show();
                            setTimeout(function () {
                                $('#categoryUpdateOk').fadeOut('fast');
                            }, 2000);
                            $("#coursecategoryTable").remove();
                            resetloancategoryTable();
                            $("#loan_category_creation_name").val('');
                            $("#loan_category_creation_id").val('');
                        }
                        else {
                            $('#categoryInsertOk').show();
                            setTimeout(function () {
                                $('#categoryInsertOk').fadeOut('fast');
                            }, 2000);
                            $("#coursecategoryTable").remove();
                            resetloancategoryTable();
                            $("#loan_category_creation_name").val('');
                            $("#loan_category_creation_id").val('');
                        }
                    }
                });
            }
            else {
                $("#loancategorynameCheck").show();
            }
        });


        function resetloancategoryTable() {
            $.ajax({
                url: 'loancategoryFile/ajaxResetLoanCategoryTable.php',
                type: 'POST',
                data: {},
                cache: false,
                success: function (html) {
                    $("#updatedloancategoryTable").empty();
                    $("#updatedloancategoryTable").html(html);
                }
            });
        }

        $("#loan_category_creation_name").keyup(function () {
            var CTval = $("#loan_category_creation_name").val();
            if (CTval.length == '') {
                $("#loancategorynameCheck").show();
                return false;
            } else {
                $("#loancategorynameCheck").hide();
            }
        });

        $("body").on("click", "#edit_category", function () {
            var loan_category_creation_id = $(this).attr('value');
            $("#loan_category_creation_id").val(loan_category_creation_id);
            $.ajax({
                url: 'loancategoryFile/ajaxEditLoanCategory.php',
                type: 'POST',
                data: { "loan_category_creation_id": loan_category_creation_id },
                cache: false,
                success: function (response) {
                    $("#loan_category_creation_name").val(response);
                }
            });
        });

        $("body").on("click", "#delete_category", function () {
            var isok = confirm("Do you want delete course category?");
            if (isok == false) {
                return false;
            } else {
                var loan_category_creation_id = $(this).attr('value');
                var c_obj = $(this).parents("tr");
                $.ajax({
                    url: 'loancategoryFile/ajaxDeleteLoanCategory.php',
                    type: 'POST',
                    data: { "loan_category_creation_id": loan_category_creation_id },
                    cache: false,
                    success: function (response) {
                        var delresult = response.includes("Rights");
                        if (delresult) {
                            $('#categoryDeleteNotOk').show();
                            setTimeout(function () {
                                $('#categoryDeleteNotOk').fadeOut('fast');
                            }, 2000);
                        }
                        else {
                            c_obj.remove();
                            $('#categoryDeleteOk').show();
                            setTimeout(function () {
                                $('#categoryDeleteOk').fadeOut('fast');
                            }, 2000);
                        }
                    }
                });
            }
        });
    }

    $(function () {
        getSchemeDropdown();
        $('#coursecategoryTable').DataTable({
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "createdRow": function (row, data, dataIndex) {
                $(row).find('td:first').html(dataIndex + 1);
            },
            "drawCallback": function (settings) {
                this.api().column(0).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            },
            dom: 'lBfrtip',
            buttons: [{
                extend: 'excel',
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
            ],
            'drawCallback': function () {
                searchFunction('coursecategoryTable');
            }
        });
    });


    // add module 
    var k = 30;
    $(document).on("click", '.add_category_ref', function () {

        validateLoanCategoryTable();

        if (loanCategoryTableError == true) {
            var appendTxt = "<tr><td><input type='text' tabindex='" + k + "' class='chosen-select form-control loan_category_ref_name' id='loan_category_ref_name' name='loan_category_ref_name[]' /></td>" +
                "<td><button type='button' tabindex='" + k + "' id='add_category_ref' name='add_category_ref' value='Submit' class='btn btn-primary add_category_ref'>Add</button></td>" +
                "<td><span class='deleterow icon-trash-2' tabindex='" + k + "'></span></td>" +
                "</tr>";
        }
        else {
            return false;
        }

        $('#moduleTable').find('tbody').append(appendTxt);
        k++;
    });

    // Delete unwanted Rows
    $(document).on("click", '.deleterow', function () {
        $(this).parent().parent().remove();
    });


    $('#loanCategoryTableCheck').hide();
    let loanCategoryTableError = true;
    function validateLoanCategoryTable() {
        var currentrow = $("#moduleTable tr").last();
        if (currentrow.find("#loan_category_ref_name").val() == '') {
            $('#loanCategoryTableCheck').show();
            loanCategoryTableError = false;
            event.preventDefault();
            return false;
        } else {
            $('#loanCategoryTableCheck').hide();
            loanCategoryTableError = true;
            return true;
        }
    }

    // Submit Button 
    $('#submitLoanCategory').click(function () {

        validation(); validateLoanCategoryTable();
        validateLoanCalculationInputs();
    });
    $('#scheme_name').change(function () {
        getSchemeListTable($(this).val());
        console.log("asdjh",$(this).val());
    });
    $('#scheme_due_method').change(function () {
        if($(this).val()=='monthly'){
            $(".total_due").show();
            $(".advance_div").show();
            $("#advance_yes").prop("checked", true).trigger("change");
            $(".advance_due").show();
             $('#due_period').prop('readonly', true);
        }else{
             $(".total_due").hide();
            $(".advance_div").hide();
            $(".advance_due").hide();
            $('#due_period').prop('readonly', false);
        }
    });
    //Validation on submit
    $('#submit_loan_scheme').click(function () {
   event.preventDefault();
   var advance_type = $('input[name=advance]:checked').val();
   let advance_dues ='';
        if (advance_type == 'Yes') {
           advance_dues= $('#advance_due').val();
        }
      var dataToSend = {
        scheme_id: $('#scheme_id').val(),
        add_scheme_name: $('#add_scheme_name').val(),
        scheme_short: $('#scheme_short').val(),
        scheme_due_method: $('#scheme_due_method').val(),
        profit_methods: $('#profit_methods option:selected').val(),
        total_due: $('#total_due').val(),
        advance_type: advance_type,
        advance_due: advance_dues,
        due_period: $('#due_period').val(),
        intreset_type: $('input[name="intreset_type"]:checked').val(),
        intreset_min: $('#intreset_min').val(),
        intreset_max: $('#intreset_max').val(),
        doc_charge_type: $('input[name="doc_charge_type"]:checked').val(),
        doc_charge_min: $('#doc_charge_min').val(),
        doc_charge_max: $('#doc_charge_max').val(),
        proc_fee_type: $('input[name="proc_fee_type"]:checked').val(),
        proc_fee_min: $('#proc_fee_min').val(),
        proc_fee_max: $('#proc_fee_max').val(),
        overdue: $('#overdue').val()
    };

    let scheme_name = dataToSend.add_scheme_name;
    let scheme_short = dataToSend.scheme_short;
    let scheme_due_method = dataToSend.scheme_due_method;
    let profit_method = dataToSend.profit_methods;
    let total_due = dataToSend.total_due;
    let advance_due = dataToSend.advance_due;
    let due_period = dataToSend.due_period;
    let intreset_type = dataToSend.intreset_type;
    let intreset_min = dataToSend.intreset_min;
    let intreset_max = dataToSend.intreset_max;
    let doc_charge_type = dataToSend.doc_charge_type;
    let doc_charge_min = dataToSend.doc_charge_min;
    let doc_charge_max = dataToSend.doc_charge_max;
    let proc_fee_type = dataToSend.proc_fee_type;
    let proc_fee_min = dataToSend.proc_fee_min;
    let proc_fee_max = dataToSend.proc_fee_max;
    let overdue = dataToSend.overdue;
    if (
        scheme_due_method != '' && scheme_short != '' && due_period != '' && scheme_name != '' &&
        intreset_type != '' && intreset_min != '' && intreset_max != '' &&
        doc_charge_type != '' && doc_charge_min != '' && doc_charge_max != '' &&
        proc_fee_type != '' && proc_fee_min != '' && proc_fee_max != '' &&
        overdue != '' && profit_method != '' && profit_method != null &&
        (scheme_due_method != 'monthly' || (total_due != ''  && (advance_type == 'No' || advance_due != '')))
    ) {
        submitScheme(dataToSend);
        // return true;
    } else {
            Swal.fire({
                timerProgressBar: true,
                timer: 2000,
                title: 'Please Fill out Mandatory fields!',
                icon: 'error',
                showConfirmButton: true,
                confirmButtonColor: '#0c70ab'
            });
            return false;
        }

    });
       //Due period calculation
    $('#advance_due ,#total_due').keyup(function () {
        var advance_type = $('input[name=advance]:checked').val();
        var total_due = $('#total_due').val();
        let advance_due ='';
        if(advance_type =='Yes'){
         advance_due = $('#advance_due').val();
        }
        if (total_due != '' && advance_due == '') {
            $('#due_period').val(total_due);
        } else if (total_due != '' && advance_due != '') {
            var due_period = total_due - advance_due; console.log(due_period)
            $('#due_period').val(due_period);
        } else if (total_due == '' && advance_due == '') {
            $('#due_period').val('');
        } else if (total_due == '' && advance_due != '') {
            $('#due_period').val(advance_due);
        }
    })
    
    // Amount or percentage change on fields
    $('#docamt,#docpercentage').click(function () {
        var doc_charge_type = $('input[name=doc_charge_type]:checked').val();
        if (doc_charge_type == 'amt') {
            changeAmtinput('docmin', 'docmax', 'doc_charge_min', 'doc_charge_max');
        }
        if (doc_charge_type == 'percentage') {
            changePercentinput('docmin', 'docmax', 'doc_charge_min', 'doc_charge_max');
        }
    })

    // Amount or percentage change on fields
    $('#procamt,#procpercentage').click(function () {
        var proc_fee_type = $('input[name=proc_fee_type]:checked').val();
        if (proc_fee_type == 'amt') {
            changeAmtinput('procmin', 'procmax', 'proc_fee_min', 'proc_fee_max');
        }
        if (proc_fee_type == 'percentage') {
            changePercentinput('procmin', 'procmax', 'proc_fee_min', 'proc_fee_max');
        }
    })
    $('#interestamt,#interestpercentage').click(function () {
        var intreset_type = $('input[name=intreset_type]:checked').val();
        if (intreset_type == 'amt') {
            changeAmtinput('intresetmin', 'intersetmax', 'intreset_min', 'intreset_max');
        }
        if (intreset_type == 'percentage') {
            changePercentinput('intresetmin', 'intersetmax', 'intreset_min', 'intreset_max');
        }
    })
    $('#advance_yes,#advance_no').click(function () {
        var advance_type = $('input[name=advance]:checked').val();
        if (advance_type == 'Yes') {
           $(".advance_due").show();
        }
        if (advance_type == 'No') {
            $(".advance_due").hide();
        }
    })
   $(document).on('click', '.edit_loan_scheme', function (e) {
    e.preventDefault(); 
    var id = $(this).data('id');
    getSchemeDetails(id);
});
   $(document).on('click', '.delete_scheme', function (e) {
    e.preventDefault(); 
    var id = $(this).data('id');
    ChangeSchemStatus(id);
});


});

function validation() {
     let loancategoryValue = $('#loan_category_name').val(); let loanlimit = $('#loan_limit').val();
     let agent_loan = $('#agent_loan').val();

    if (loancategoryValue.length == '') {
        $('#loanCategoryCheck').show();
        event.preventDefault();
    }
    else {
        $('#loanCategoryCheck').hide();
    }

    if (loanlimit.length == '') {
        $('#loan_limitCheck').show();
        event.preventDefault();
    }
    else {
        $('#loan_limitCheck').hide();
    }
    if (agent_loan== '') {
        $('#agent_loanCheck').show();
        event.preventDefault();
    }
    else {
        $('#agent_loanCheck').hide();
    }
}


function DropDownCourse() {
    $.ajax({
        url: 'loancategoryFile/ajaxGetLoanCategory.php',
        type: 'post',
        data: {},
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            $("#loan_category_name").empty();
            $("#loan_category_name").append("<option value=''>" + 'Select Loan Category' + "</option>");
            for (var i = 0; i < len; i++) {
                var loan_category_creation_id = response[i]['loan_category_creation_id'];
                var loan_category_creation_name = response[i]['loan_category_creation_name'];
                $("#loan_category_name").append("<option value='" + loan_category_creation_id + "'>" + loan_category_creation_name + "</option>");

            }
            {//To Order Alphabetically
                var firstOption = $("#loan_category_name option:first-child");
                $("#loan_category_name").html($("#loan_category_name option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#loan_category_name").prepend(firstOption);
            }

        }
    });
}
function getSchemeTable() {
    
    var table = $('#loan_scheme_inner_table').DataTable();
    table.destroy();

    $('#loan_scheme_inner_table').DataTable({
        "order": [[0, "desc"]],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'loancategoryFile/getSchemeList.php',
            'data': function (data) {
                var search = $('input[type=search]').val();
                data.search = search;
            }
        },
        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'excel',
                title: "Loan Scheme List"
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
        "drawCallback": function () {
            searchFunction('loan_scheme_inner_table');
            paginationFunction('loan_scheme_inner_table');
        }
    });
}
function getSchemeDropdown() {
    $.post('loancategoryFile/getSchemeList.php', { action: 'dropdown' }, function (response) {
        
        $('#scheme_id').val('');
        $('#add_scheme_name').val('');
        $('#scheme_short').val('');
        $('#total_due').val('');
        $('#advance_due').val('');
        $('#due_period').val('');
        $('#intreset_min').val('');
        $('#intreset_max').val('');
        $('#doc_charge_min').val('');
        $('#doc_charge_max').val('');
        $('#proc_fee_min').val('');
        $('#proc_fee_max').val('');
        $('#overdue').val('');
        $('#add_scheme_id').val('');

        // Deselect radio buttons by name
        $('input[name="intreset_type"]').prop('checked', false);
        $('input[name="doc_charge_type"]').prop('checked', false);
        $('input[name="proc_fee_type"]').prop('checked', false);

        // Reset select dropdowns and trigger change (for Choices.js or Select2)
        $('#scheme_due_method').val('').trigger('change');
        $('#profit_methods').val('').trigger('change');

        // Hide sections
        $('.total_due').hide();
        $('.advance_due').hide();
        
        scheme_choices.clearStore();
        let selectedSchemeId = [];

        let schemename2 = ($('#scheme_name2').val() || '')
            .split(',')
            .map(s => s.trim());

        $.each(response, function (index, val) {
            let selected = '';

            if (schemename2.includes(val.id.toString())) {
                selected = 'selected';
                selectedSchemeId.push(val.id);
            }

            let items = [{
                value: val.id,
                label: val.scheme_name,
                selected: selected
            }];
            scheme_choices.setChoices(items);
        });
if(selectedSchemeId !=''){
        getSchemeListTable(selectedSchemeId);

}
    }, 'json');
}


function getSchemeListTable(scheme_id) {
    if (!Array.isArray(scheme_id)) {
        scheme_id = (scheme_id || '').split(',').map(id => id.trim());
    }
    console.log("fsdf",scheme_id);

    var table = $('#loan_scheme_outer_table').DataTable();
    table.destroy();

    $('#loan_scheme_outer_table').DataTable({
        "order": [[0, "desc"]],
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'loancategoryFile/getSchemeDropDown.php',
            'data': function (data) {
                data.scheme_id =  scheme_id.length > 0 ? scheme_id : null;; // Send as array
                data.search = $('#search').val(); // Optional: pass manual search text
            }
        },
        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'excel',
                title: "Loan Scheme List"
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
        "drawCallback": function () {
            searchFunction('loan_scheme_outer_table');
            paginationFunction('loan_scheme_outer_table');
        }
    });
}
//Change Document charge & Processing fee input field not readonly
function changeAmtinput(docmin, docmax, doc_charge_min, doc_charge_max) {
    $('#' + docmin).text('Min ₹');
    $('#' + docmax).text('Max ₹');
    $('#' + doc_charge_min).attr('readonly', false);
    $('#' + doc_charge_max).attr('readonly', false);
}
//Change Document charge & Processing fee input field not readonly
function changePercentinput(docmin, docmax, doc_charge_min, doc_charge_max) {
    $('#' + docmin).text('Min %');
    $('#' + docmax).text('Max %');
    $('#' + doc_charge_min).attr('readonly', false);
    $('#' + doc_charge_max).attr('readonly', false);
}
function getSchemeDetails(id) {
   $.ajax({
        url: 'loancategoryFile/ajaxGetSchemeDetails.php',
        type: 'post',
        data: {id},
        dataType: 'json',
        success: function (response) {
    if (response) {
        $('#scheme_id').val(response.scheme_id);
        $('#add_scheme_name').val(response.scheme_name);
        $('#scheme_short').val(response.short_name);
        $('#scheme_due_method').val(response.due_method);
        $('#due_period').val(response.due_period);
        $('#profit_methods').val(response.profit_method);
        $('#intreset_min').val(response.intreset_min);
        $('#intreset_max').val(response.intreset_max);
        $('#doc_charge_min').val(response.doc_charge_min);
        $('#doc_charge_max').val(response.doc_charge_max);
        $('#proc_fee_min').val(response.proc_fee_min);
        $('#proc_fee_max').val(response.proc_fee_max);
        $('#overdue').val(response.overdue);
        $("input[name='advance'][value='" + response.advance_type + "']").prop("checked", true);

        // Set Interest Type radio
        if (response.intreset_type === 'amt') {
            $('#interestamt').prop('checked', true);
        } else if (response.intreset_type === 'percentage') {
            $('#interestpercentage').prop('checked', true);
        }

        // Set Document Charge Type radio
        if (response.doc_charge_type === 'amt') {
            $('#docamt').prop('checked', true);
        } else if (response.doc_charge_type === 'percentage') {
            $('#docpercentage').prop('checked', true);
        }

        // Set Processing Fee Type radio
        if (response.proc_fee_type === 'amt') {
            $('#procamt').prop('checked', true);
        } else if (response.proc_fee_type === 'percentage') {
            $('#procpercentage').prop('checked', true);
        }

        // Handle Due Method visibility logic
        if (response.due_method === 'monthly') {
            $('#total_due').val(response.total_due);
            $('#advance_due').val(response.advance_due);
            $('.total_due').show();
            $(".advance_div").show();

            $('#due_period').val(response.due_period).prop('readonly', true);
        } else {
            $('.total_due, .advance_due').hide();

            $('#total_due').val('');
            $('#advance_due').val('');

            $('#due_period').val(response.due_period).prop('readonly', false);
        }
    }
}

    });
}
function ChangeSchemStatus(id) {
   $.ajax({
        url: 'loancategoryFile/SchemeStatusChange.php',
        type: 'post',
        data: {id},
        dataType: 'json',
    success: function (response) {
            if (response == 0) {
                Swal.fire({
                timerProgressBar: true,
                timer: 2000,
                title: 'Scheme deleted successfully.',
                icon: 'success',
                showConfirmButton: true,
                confirmButtonColor: '#0c70ab'
                });
                getSchemeTable();
            } else {
                Swal.fire({
                timerProgressBar: true,
                timer: 2000,
                title: 'Failed to delete scheme.',
                icon: 'error',
                showConfirmButton: true,
                confirmButtonColor: '#0c70ab'
                });
            }
            
        },
        error: function () {
            alert("AJAX error occurred.");
        }

    });
}
function validateLoanCalculationInputs() {
    let isAnyFilled = false;
    let isAllFilled = true;

    const inputIds = [
        '#monthly_intrests_rate_min',
        '#monthly_intrests_rate_max',
        '#monthly_due_periods_min',
        '#monthly_due_periods_max',
        '#monthly_document_charges_min',
        '#monthly_document_charges_max',
        '#monthly_processing_fees_min',
        '#monthly_processing_fees_max',
        '#monthly_overdues'
    ];

    inputIds.forEach(function (selector) {
        const value = $(selector).val().trim();
        if (value !== "") {
            isAnyFilled = true;
        } else {
            isAllFilled = false;
        }
    });

    // Check multiselect
    const profitMethod = $('#monthly_profit_method').val();
    if (profitMethod && profitMethod.length > 0) {
        isAnyFilled = true;
    } else {
        isAllFilled = false;
    }

    // Check radio buttons
    const docType = $('input[name="monthly_doc_charges_type"]:checked').length > 0;
    const procType = $('input[name="proc_fees_type"]:checked').length > 0;
    const advance = $('input[name="monthly_collection_info"]:checked').length > 0;

    if (docType || procType || advance) {
        isAnyFilled = true;
    }
    if (!docType || !procType || !advance) {
        isAllFilled = false;
    }

    // If nothing is filled, allow
    if (!isAnyFilled) {
        return true;
    }

    // If something is filled but not all, block
    if (!isAllFilled) {
        return false;
    }

    return true;
}

function submitScheme(data) {
    $.ajax({
        url: 'loancategoryFile/submitLoanScheme.php',
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function (response) {
        

        if (response == 1) {
            alert("Scheme Updated !");
        } else if (response == 2) {
            alert("Scheme Submitted !");
        }

        $('#scheme_id').val('');
        $('#add_scheme_name').val('');
        $('#scheme_short').val('');
        $('#total_due').val('');
        $('#advance_due').val('');
        $('#due_period').val('');
        $('#intreset_min').val('');
        $('#intreset_max').val('');
        $('#doc_charge_min').val('');
        $('#doc_charge_max').val('');
        $('#proc_fee_min').val('');
        $('#proc_fee_max').val('');
        $('#overdue').val('');
        $('#add_scheme_id').val('');

        // Deselect radio buttons by name
        $('input[name="intreset_type"]').prop('checked', false);
        $('input[name="doc_charge_type"]').prop('checked', false);
        $('input[name="proc_fee_type"]').prop('checked', false);

        // Reset select dropdowns and trigger change (for Choices.js or Select2)
        $('#scheme_due_method').val('').trigger('change');
        $('#profit_methods').val('').trigger('change');

        // Hide sections
        $('.total_due').hide();
        $('.advance_due').hide();

        // Refresh table
        getSchemeTable();
    },
    error: function(xhr, status, error) {
        alert("AJAX Error: " + error);
    }
    });
}



