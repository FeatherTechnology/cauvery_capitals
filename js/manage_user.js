//Multi select initialization
const branchMultiselect = new Choices('#branch_id1', {
    removeItemButton: true,
    noChoicesText: 'Select Branch Name',
    allowHTML: true
});

const agentMultiselect = new Choices('#agent1', {
    removeItemButton: true,
    noChoicesText: 'Select Agent Name',
    allowHTML: true

});

const lineMultiselect = new Choices('#line1', {
    removeItemButton: true,
    noChoicesText: 'Select Line Name',
    allowHTML: true

});
const groupMultiselect = new Choices('#group1', {
    removeItemButton: true,
    noChoicesText: 'Select Group Name',
    allowHTML: true

});

const verificationloanCatMultiselect = new Choices('#loan_cat1', {
    removeItemButton: true,
    noChoicesText: 'Select Loan Category',
    allowHTML: true
});

const approvalloanCatMultiselect = new Choices('#loan_cat2', {
    removeItemButton: true,
    noChoicesText: 'Select Loan Category',
    allowHTML: true
});

const acknowledgementloanCatMultiselect = new Choices('#loan_cat3', {
    removeItemButton: true,
    noChoicesText: 'Select Loan Category',
    allowHTML: true
});

const cash_tally_access_select = new Choices('#cash_tally_access1', {
    removeItemButton: true,
    noChoicesText: 'Select Cash Tally Access',
    allowHTML: true

});
const bankMultiselect = new Choices('#bank_details1', {
    removeItemButton: true,
    noChoicesText: 'Select Bank Name',
    allowHTML: true
});

const promotionAccess = new Choices('#pro_aty_access', {
    removeItemButton: true,
    noChoicesText: 'Select Promotion Activity',
    allowHTML: true
});

const dueFollowupLines = new Choices('#due_follup_lines', {
    removeItemButton: true,
    noChoicesText: 'Select Due Followup Lines',
    allowHTML: true
});

const updateScreen = new Choices('#update_screen', {
    removeItemButton: true,
    noChoicesText: 'Select Update Screen',
    allowHTML: true
});

// Document is ready
$(document).ready(function () {

    // {//To Order role Alphabetically
    //     var firstOption = $("#role option:first-child");
    //     $("#role").html($("#role option:not(:first-child)").sort(function (a, b) {
    //         return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
    //     }));
    //     $("#role").prepend(firstOption);
    // }
     // {//To Order ag_name Alphabetically
    //     var firstOption = $("#ag_name option:first-child");
    //     $("#ag_name").html($("#ag_name option:not(:first-child)").sort(function (a, b) {
    //         return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
    //     }));
    //     $("#ag_name").prepend(firstOption);
    // }
    // {//To Order role_type Alphabetically
    //     var firstOption = $("#role_type option:first-child");
    //     $("#role_type").html($("#role_type option:not(:first-child)").sort(function (a, b) {
    //         return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
    //     }));
    //     $("#role_type").prepend(firstOption);
    // }

    $('#role').change(function () {

        $('.userInfoTable').hide();

        $('#company_id').val('');
        $('#company_name').val('');
        branchMultiselect.clearStore();
        lineMultiselect.clearStore();
        groupMultiselect.clearStore();

        var role = $('#role').val();
        getRoleBasedDetails(role);
    });

    $('#role_type').change(function () {

        $('.userInfoTable').hide();

        $('#company_id').val('');
        $('#company_name').val('');
        branchMultiselect.clearStore();
        lineMultiselect.clearStore();
        groupMultiselect.clearStore();

        var role = $('#role').val();
        var role_type = $('#role_type').val();
        getRoleTypeBasedDetails(role, role_type)
    });

    $('#dir_name').change(function () {
        var dir_id = $('#dir_name').val();
        geDirectorDetails(dir_id);
    });

    $('#ag_name').change(function () {
        var ag_id = $('#ag_name').val();
        getAgentDetails(ag_id);
    });

    $('#staff_name').change(function () {
        var staff_id = $('#staff_name').val();
        getStaffDetails(staff_id);
    });

    $('#cnf_password').keyup(function () {
        var pass = $('#password').val();
        var cnf_pass = $('#cnf_password').val();
        if (pass != cnf_pass) {
            $('#passworkCheck').show();
            $('#cnf_password').css("border", "1px solid red");
        } else {
            $('#cnf_password').css("border", "");
            $('#passworkCheck').hide();
        }
    });

    $('#branch_id1').change(function () {
        var branch_id1 = branchMultiselect.getValue();
        var branch_id = '';
        for (var i = 0; i < branch_id1.length; i++) {
            if (i > 0) {
                branch_id += ',';
            }
            branch_id += branch_id1[i].value;
        }

        getGroupDropdown(branch_id);
        getLineDropdown(branch_id);
        getdueFollupLineDropdown(branch_id);
    });

    $('#bank_details1').change(function () {
        var bank_details1 = bankMultiselect.getValue();
        var bank_details = '';
        for (var i = 0; i < bank_details1.length; i++) {
            if (i > 0) {
                bank_details += ',';
            }
            bank_details += bank_details1[i].value;
        }
        var arr = bank_details.split(",");
        arr.sort(function (a, b) { return a - b });
        var sortedStr = arr.join(",");

        $('#bank_details').val(sortedStr);

    })
    $('#cash_tally_access1').change(function () {
        
        var cash_tally_access1 = cash_tally_access_select.getValue();
        var cash_tally_access = '';
        for (var i = 0; i < cash_tally_access1.length; i++) {
            if (i > 0) {
                cash_tally_access += ',';
            }
            cash_tally_access += cash_tally_access1[i].value;
        }
        var arr = cash_tally_access.split(",");
        arr.sort(function (a, b) { return a - b });
        var sortedStr = arr.join(",");
        $('#cash_tally_access').val('');
        $('#cash_tally_access').val(sortedStr);

    })

    $('#due_follup_lines').change(function () {
        var due_follup_lines = dueFollowupLines.getValue();
        var due_follup_lines_id = '';
        for (var i = 0; i < due_follup_lines.length; i++) {
            if (i > 0) {
                due_follup_lines_id += ',';
            }
            due_follup_lines_id += due_follup_lines[i].value;
        }
        var arr = due_follup_lines_id.split(",");
        arr.sort(function (a, b) { return a - b });
        var sortedStr = arr.join(",");
        $('#due_follup_line_id').val('');
        $('#due_follup_line_id').val(sortedStr);

    })

    $('#update_screen').change(function () {
        // Get values from multiselect and sort
        const screenList = updateScreen.getValue();
        const screenSortedStr = screenList
            .map(item => item.value)
            .sort((a, b) => a - b)
            .join(',');
    
        $('#update_screen_id').val(screenSortedStr);
    });

    //modules checkbox events
    $("#adminmodule").on("change", function () {
        const checkboxesToEnable = document.querySelectorAll("input.admin-checkbox");
        var adminmodule = document.querySelector('#adminmodule');
        checkbox(checkboxesToEnable, adminmodule);
    });

    $("#mastermodule").on("change", function () {
        const checkboxesToEnable = document.querySelectorAll("input.master-checkbox");
        var mastermodule = document.querySelector('#mastermodule');
        checkbox(checkboxesToEnable, mastermodule);
    });

    $("#requestmodule").on("change", function () {
        const checkboxesToEnable = document.querySelectorAll("input.request-checkbox");
        var requestmodule = document.querySelector('#requestmodule');
        checkbox(checkboxesToEnable, requestmodule);
        if (!requestmodule.checked) {
            agentMultiselect.clearStore();
            $('.agent_div').hide();
        }
    });

    $("#verificationmodule").on("change", function () {
        const checkboxesToEnable = document.querySelectorAll("input.verification-checkbox");
        var verificationmodule = document.querySelector('#verificationmodule');
        checkbox(checkboxesToEnable, verificationmodule);
        if (!verificationmodule.checked) {
            verificationloanCatMultiselect.clearStore();
            $('.ver_loancat_div').hide();
        }
    });

    $("#approvalmodule").on("change", function () {
        const checkboxesToEnable = document.querySelectorAll("input.approval-checkbox");
        var approvalmodule = document.querySelector('#approvalmodule');
        checkbox(checkboxesToEnable, approvalmodule);
        if (!approvalmodule.checked) {
            approvalloanCatMultiselect.clearStore();
            $('.app_loancat_div').hide();
        }
    });

    $("#acknowledgementmodule").on("change", function () {
        const checkboxesToEnable = document.querySelectorAll("input.acknowledgement-checkbox");
        var acknowledgementmodule = document.querySelector('#acknowledgementmodule');
        checkbox(checkboxesToEnable, acknowledgementmodule);
        if (!acknowledgementmodule.checked) {
            acknowledgementloanCatMultiselect.clearStore();
            $('.ack_loancat_div').hide();
        }
    });

    $("#loanissuemodule").on("change", function () {
        const checkboxesToEnable = document.querySelectorAll("input.loan_issue-checkbox");
        var loanissuemodule = document.querySelector('#loanissuemodule');
        checkbox(checkboxesToEnable, loanissuemodule);
    });

    $("#collectionmodule").on("change", function () {
        const checkboxesToEnable = document.querySelectorAll("input.collection-checkbox");
        var collectionmodule = document.querySelector('#collectionmodule');
        checkbox(checkboxesToEnable, collectionmodule);
    });

    $("#closedmodule").on("change", function () {
        const checkboxesToEnable = document.querySelectorAll("input.closed-checkbox");
        var closedmodule = document.querySelector('#closedmodule');
        checkbox(checkboxesToEnable, closedmodule);
    });

    $("#nocmodule").on("change", function () {
        const checkboxesToEnable = document.querySelectorAll("input.noc-checkbox");
        var nocmodule = document.querySelector('#nocmodule');
        checkbox(checkboxesToEnable, nocmodule);
    });

    $("#updatemodule").on("change", function () {
        const checkboxesToEnable = document.querySelectorAll("input.update-checkbox");
        var updatemodule = document.querySelector('#updatemodule');
        checkbox(checkboxesToEnable, updatemodule);
    });

    $("#concernmodule").on("change", function () {
        const checkboxesToEnable = document.querySelectorAll("input.concern-checkbox");
        var concernmodule = document.querySelector('#concernmodule');
        checkbox(checkboxesToEnable, concernmodule);
    });

    $("#accountsmodule").on("change", function () {
        const checkboxesToEnable = document.querySelectorAll("input.accounts-checkbox");
        var accountsmodule = document.querySelector('#accountsmodule');
        checkbox(checkboxesToEnable, accountsmodule);
        if (!accountsmodule.checked) {
            $('.bank_details').hide()
        }
    });
    // $("#cash_tally ").on("change", function () {
    //     const checkboxesToEnable = document.querySelectorAll("input.accounts-checkbox");
    //     var accountsmodule = document.querySelector('#cash_tally ');
    //     checkbox(checkboxesToEnable, accountsmodule);
    //     if (!accountsmodule.checked) {
    //         $('.cash_tally_access').hide()
    //     }
    // });
    $("#followupmodule").on("change", function () {
        const checkboxesToEnable = document.querySelectorAll("input.followup-checkbox");
        var followupmodule = document.querySelector('#followupmodule');
        checkbox(checkboxesToEnable, followupmodule);
    });

    $("#reportmodule").on("change", function () {
        const checkboxesToEnable = document.querySelectorAll("input.report-checkbox");
        var reportmodule = document.querySelector('#reportmodule');
        checkbox(checkboxesToEnable, reportmodule);
    });

    $("#searchmodule").on("change", function () {
        const checkboxesToEnable = document.querySelectorAll("input.search-checkbox");
        var searchmodule = document.querySelector('#searchmodule');
        checkbox(checkboxesToEnable, searchmodule);
    });

    $("#bulk_upload_module").on("change", function () {
        const checkboxesToEnable = document.querySelectorAll("input.bulk_upload-checkbox");
        var bulk_upload_module = document.querySelector('#bulk_upload_module');
        checkbox(checkboxesToEnable, bulk_upload_module);
    });

    $("#loan_track_module").on("change", function () {
        const checkboxesToEnable = document.querySelectorAll("input.loan_track-checkbox");
        var loan_track_module = document.querySelector('#loan_track_module');
        checkbox(checkboxesToEnable, loan_track_module);
    });

    $("#sms_module").on("change", function () {
        const checkboxesToEnable = document.querySelectorAll("input.sms_generation-checkbox");
        var sms_module = document.querySelector('#sms_module');
        checkbox(checkboxesToEnable, sms_module);
    });

    $('#cash_tally').click(function () {
        var cash_tally = document.querySelector('#cash_tally');
        if (cash_tally.checked) {
            getBankDetails();
            $('.bank_details').show()
            $('.cash_tally_access').show()
        } else {
            $('.bank_details').hide()
            $('.cash_tally_access').hide()
        }
    })

    $('#promotion_activity').click(function () {
        var promotion_activity = document.querySelector('#promotion_activity');
        if (promotion_activity.checked) {
            $('.promotion_activity_div').show()
        } else {
            $('.promotion_activity_div').hide()
        }
    })

    $('#conf_followup').click(function () {
        var conf_followup = document.querySelector('#conf_followup');
        if (conf_followup.checked) {
            $('.conf_followup_div').show()
        } else {
            $('.conf_followup_div').hide()
        }
    })
    
    $('#updatemodule').click(function () {
        var update_screen = document.querySelector('#updatemodule');
        if (!update_screen.checked) {
            $('.update_screen_div').hide();
        }
    });

    $('#update').click(function () {
        var update_screen = document.querySelector('#update');
        if (update_screen.checked) {
            $('.update_screen_div').show();
        } else {
            $('.update_screen_div').hide();
        }
    });

    $('#request').click(function () {
        var request_screen = document.querySelector('#request');
        if (request_screen.checked) {
            getAgentDropdown($('#company_id').val());
            $('.agent_div').show();
        } else {
            $('.agent_div').hide();
        }
    });

    $('#verification').click(function () {
        var verification_screen = document.querySelector('#verification');
        if (verification_screen.checked) {
            getLoanCatDropdown('#ver_loan_cat_upd', verificationloanCatMultiselect);
            $('.ver_loancat_div').show();
        } else {
            verificationloanCatMultiselect.clearStore();
            $('.ver_loancat_div').hide();
        }
    });

    $('#approval').click(function () {
        var approval_screen = document.querySelector('#approval');
        if (approval_screen.checked) {
            getLoanCatDropdown('#app_loan_cat_upd', approvalloanCatMultiselect);
            $('.app_loancat_div').show();
        } else {
            approvalloanCatMultiselect.clearStore();
            $('.app_loancat_div').hide();
        }
    });

    $('#acknowledgement').click(function () {
        var acknowledgement_screen = document.querySelector('#acknowledgement');
        if (acknowledgement_screen.checked) {
            getLoanCatDropdown('#ack_loan_cat_upd', acknowledgementloanCatMultiselect);
            $('.ack_loancat_div').show();
        } else {
            acknowledgementloanCatMultiselect.clearStore();
            $('.ack_loancat_div').hide();
        }
    });

    $('#promotion_activity').click(function () {
        var promotion_activity_screen = document.querySelector('#promotion_activity');
        if (promotion_activity_screen.checked) {
            $('.promotion_activity_div').show();
        } else {
            $('.promotion_activity_div').hide();
        }
    });

	$('#submit_manage_user').click(function (event) {
	event.preventDefault(); // Stop default submit; we'll submit programmatically after validation

		if (validation()) {
			let confirmAction = confirm("Are you sure you want to submit Manage user ?");
			if (confirmAction) {
				// Ensure the submit button name/value is posted so PHP sees $_POST['submit_manage_user']
				if (!document.querySelector('input[name="submit_manage_user"]')) {
					const hiddenSubmit = document.createElement('input');
					hiddenSubmit.type = 'hidden';
					hiddenSubmit.name = 'submit_manage_user';
					hiddenSubmit.value = 'Submit';
					document.getElementById('manage_user_form').appendChild(hiddenSubmit);
				}

				const formEl = document.getElementById('manage_user_form');
				const submitBtn = document.getElementById('submit_manage_user');
				if (formEl.requestSubmit) {
					formEl.requestSubmit(submitBtn);
				} else {
					formEl.submit();
				}
			}
		}
	});
});


$(function () {

    var user_id_upd = $('#user_id_upd').val();
    if (user_id_upd > 0) {
        var role_upd = $('#role_upd').val();
        var role_type_upd = $('#role_type_upd').val();
        var dir_id_upd = $('#dir_id_upd').val();
        var ag_id_upd = $('#ag_id_upd').val();
        var staff_id_upd = $('#staff_id_upd').val();
        var company_id_upd = $('#company_id_upd').val();
        var branch_id_upd = $('#branch_id_upd').val();
        $('#password').attr('type', 'text');
        $('#cnf_password').attr('type', 'text');

        getAgentDropdown(company_id_upd);
        getRoleBasedDetails(role_upd);
        if (role_upd == '1') {
            $('#role_type').val(role_type_upd);
            getRoleTypeBasedDetails(role_upd, role_type_upd);
            geDirectorDetails(dir_id_upd);

        } else if (role_upd == '2') {
            getAgentDetails(ag_id_upd);

        } else if (role_upd == '3') {
            getRoleTypeBasedDetails(role_upd, role_type_upd);
            getStaffDetails(staff_id_upd);

        }

        getGroupDropdown(branch_id_upd);
        getLineDropdown(branch_id_upd);
        getdueFollupLineDropdown(branch_id_upd);
        getBankDetails();
        getProAccess();
        // getcashTallyAccess()

        var due_followup = document.querySelector('#due_followup');
        var conf_followup = document.querySelector('#conf_followup');
        if (due_followup.checked) {
            getdueFollupLineDropdown();
            $('.due_followupline_div').show()
        }
        if (conf_followup.checked) {
            $('.conf_followup_div').show()
        }

        var update_screen = document.querySelector('#update');
        if (update_screen.checked) {
            let editVal = $('#update_screen_id').val();
            if (editVal) {
                let selectedValues = editVal.split(',');
                selectedValues.forEach(value => {
                    updateScreen.setChoiceByValue(value.trim());
                });
            }

            $('.update_screen_div').show()
        }

        var request_screen = document.querySelector('#request');
        if (request_screen.checked) {
            getAgentDropdown(company_id_upd);
            $('.agent_div').show()
        }else{
            $('.agent_div').hide()
        }

        var verification_screen = document.querySelector('#verification');
        if (verification_screen.checked) {
            getLoanCatDropdown('#ver_loan_cat_upd', verificationloanCatMultiselect);
            $('.ver_loancat_div').show()
        }else{
            verificationloanCatMultiselect.clearStore();
            $('.ver_loancat_div').hide()
        }

        var approval_screen = document.querySelector('#approval');
        if (approval_screen.checked) {
            getLoanCatDropdown('#app_loan_cat_upd', approvalloanCatMultiselect);
            $('.app_loancat_div').show()
        }else{
            approvalloanCatMultiselect.clearStore();
            $('.app_loancat_div').hide()
        }

        var acknowledgement_screen = document.querySelector('#acknowledgementmodule');
        if (acknowledgement_screen.checked) {
            getLoanCatDropdown('#ack_loan_cat_upd', acknowledgementloanCatMultiselect);
            $('.ack_loancat_div').show()
        }else{
            acknowledgementloanCatMultiselect.clearStore();
            $('.ack_loancat_div').hide()
        }

        var promotionActivity_screen = document.querySelector('#promotion_activity');
        if (promotionActivity_screen.checked) {
            $('.promotion_activity_div').show()
        }else{
            $('.promotion_activity_div').hide()
        }

        var cash_tally = document.querySelector('#cash_tally');
        if (cash_tally.checked) {
            var cash_tally_access_upd = $('#cash_tally_access_upd').val();
            if (cash_tally_access_upd) {
                let selectedValues = cash_tally_access_upd.split(',');
                selectedValues.forEach(value => {
                    cash_tally_access_select.setChoiceByValue(value.trim());
                });
            }

            $('.cash_tally_access').show()
            $('.bank_details').show()
        }

        var mastermodule = document.getElementById('mastermodule');
        var adminmodule = document.getElementById('adminmodule');
        var requestmodule = document.getElementById('requestmodule');
        var verificationmodule = document.getElementById('verificationmodule');
        var approvalmodule = document.getElementById('approvalmodule');
        var acknowledgementmodule = document.getElementById('acknowledgementmodule');
        var loanissuemodule = document.getElementById('loanissuemodule');
        var collectionmodule = document.getElementById('collectionmodule');
        var closedmodule = document.getElementById('closedmodule');
        var nocmodule = document.getElementById('nocmodule');
        // var doctrackmodule = document.getElementById('doctrackmodule');
        var updatemodule = document.getElementById('updatemodule');
        var concernmodule = document.getElementById('concernmodule');
        var accountsmodule = document.getElementById('accountsmodule');
        var followupmodule = document.getElementById('followupmodule');
        var reportmodule = document.getElementById('reportmodule');
        var searchmodule = document.getElementById('searchmodule');
        var bulk_upload_module = document.getElementById('bulk_upload_module');
        var loan_track_module = document.getElementById('loan_track_module');
        var sms_module = document.getElementById('sms_module');

        if (mastermodule.checked) { const checkboxesToEnable = document.querySelectorAll("input.master-checkbox"); var mastermodule = document.querySelector('#mastermodule'); checkbox(checkboxesToEnable, mastermodule); }
        if (adminmodule.checked) { const checkboxesToEnable = document.querySelectorAll("input.admin-checkbox"); var adminmodule = document.querySelector('#adminmodule'); checkbox(checkboxesToEnable, adminmodule); }
        if (requestmodule.checked) { const checkboxesToEnable = document.querySelectorAll("input.request-checkbox"); var requestmodule = document.querySelector('#requestmodule'); checkbox(checkboxesToEnable, requestmodule); }
        if (verificationmodule.checked) { const checkboxesToEnable = document.querySelectorAll("input.verification-checkbox"); var verificationmodule = document.querySelector('#verificationmodule'); checkbox(checkboxesToEnable, verificationmodule); }
        if (approvalmodule.checked) { const checkboxesToEnable = document.querySelectorAll("input.approval-checkbox"); var approvalmodule = document.querySelector('#approvalmodule'); checkbox(checkboxesToEnable, approvalmodule); }
        if (acknowledgementmodule.checked) { const checkboxesToEnable = document.querySelectorAll("input.acknowledgement-checkbox"); var acknowledgementmodule = document.querySelector('#acknowledgementmodule'); checkbox(checkboxesToEnable, acknowledgementmodule); }
        if (loanissuemodule.checked) { const checkboxesToEnable = document.querySelectorAll("input.loan_issue-checkbox"); var loanissuemodule = document.querySelector('#loanissuemodule'); checkbox(checkboxesToEnable, loanissuemodule); }
        if (collectionmodule.checked) { const checkboxesToEnable = document.querySelectorAll("input.collection-checkbox"); var collectionmodule = document.querySelector('#collectionmodule'); checkbox(checkboxesToEnable, collectionmodule); }
        if (closedmodule.checked) { const checkboxesToEnable = document.querySelectorAll("input.closed-checkbox"); var closedmodule = document.querySelector('#closedmodule'); checkbox(checkboxesToEnable, closedmodule); }
        if (nocmodule.checked) { const checkboxesToEnable = document.querySelectorAll("input.noc-checkbox"); var nocmodule = document.querySelector('#nocmodule'); checkbox(checkboxesToEnable, nocmodule); }
        // if(doctrackmodule.checked){const checkboxesToEnable = document.querySelectorAll("input.doctrack-checkbox");var doctrackmodule = document.querySelector('#doctrackmodule');checkbox(checkboxesToEnable,doctrackmodule);}
        if (updatemodule.checked) { const checkboxesToEnable = document.querySelectorAll("input.update-checkbox"); var updatemodule = document.querySelector('#updatemodule'); checkbox(checkboxesToEnable, updatemodule); }
        if (concernmodule.checked) { const checkboxesToEnable = document.querySelectorAll("input.concern-checkbox"); var concernmodule = document.querySelector('#concernmodule'); checkbox(checkboxesToEnable, concernmodule); }
        if (accountsmodule.checked) { const checkboxesToEnable = document.querySelectorAll("input.accounts-checkbox"); var accountsmodule = document.querySelector('#accountsmodule'); checkbox(checkboxesToEnable, accountsmodule); }
        if (followupmodule.checked) { const checkboxesToEnable = document.querySelectorAll("input.followup-checkbox"); var followupmodule = document.querySelector('#followupmodule'); checkbox(checkboxesToEnable, followupmodule); }
        if (reportmodule.checked) { const checkboxesToEnable = document.querySelectorAll("input.report-checkbox"); var reportmodule = document.querySelector('#reportmodule'); checkbox(checkboxesToEnable, reportmodule); }
        if (searchmodule.checked) { const checkboxesToEnable = document.querySelectorAll("input.search-checkbox"); var searchmodule = document.querySelector('#searchmodule'); checkbox(checkboxesToEnable, searchmodule); }
        if (bulk_upload_module.checked) { const checkboxesToEnable = document.querySelectorAll("input.bulk_upload-checkbox"); var bulk_upload_module = document.querySelector('#bulk_upload_module'); checkbox(checkboxesToEnable, bulk_upload_module); }
        if (loan_track_module.checked) { const checkboxesToEnable = document.querySelectorAll("input.loan_track-checkbox"); var loan_track_module = document.querySelector('#loan_track_module'); checkbox(checkboxesToEnable, loan_track_module); }
        if (sms_module.checked) { const checkboxesToEnable = document.querySelectorAll("input.sms_generation-checkbox"); var sms_module = document.querySelector('#sms_module'); checkbox(checkboxesToEnable, sms_module); }
    }
})

//Dropdowns
//get Staff  Type dropdown
function getStaffTypeDropdown() {
    var role_type_upd = $('#role_type_upd').val();
    $.ajax({
        url: 'staffCreation/ajaxGetStaffType.php',
        type: 'post',
        data: {},
        dataType: 'json',
        success: function (response) {

            // Sort response alphabetically by staff_type_name
            var sortedResponse = response.slice().sort(function(a, b) {
                return a.staff_type_name.localeCompare(b.staff_type_name);
            });

            var htmlString = "<option value=''>Select Role Type</option>";

            for (var i = 0; i < sortedResponse.length; i++) {
                var staff_type_id = sortedResponse[i]['staff_type_id'];
                var staff_type_name = sortedResponse[i]['staff_type_name'];
                var selected = '';
                if (role_type_upd != '' && role_type_upd == staff_type_id) {
                    selected = 'selected';
                }
                htmlString += "<option value='" + staff_type_id + "' " + selected + ">" + staff_type_name + "</option>";
            }

            $("#role_type").html(htmlString);
        }
    });
}


//get Director Name dropdown
function getDirectorName(dir_type) {
    var dir_id_upd = $('#dir_id_upd').val();
    $.ajax({
        url: 'manageUser/ajaxGetDirectorName.php',
        type: 'post',
        data: { 'dir_type': dir_type },
        dataType: 'json',
        success: function (response) {

            // Sort response alphabetically by dir_name
            var sortedResponse = response.slice().sort(function(a, b) {
                return a.dir_name.localeCompare(b.dir_name);
            });

            var htmlString = "<option value=''>Select Director Name</option>";

            for (var i = 0; i < sortedResponse.length; i++) {
                var dir_id = sortedResponse[i]['dir_id'];
                var dir_name = sortedResponse[i]['dir_name'];
                var selected = '';
                if (dir_id_upd != '' && dir_id_upd == dir_id) {
                    selected = 'selected';
                }
                htmlString += "<option value='" + dir_id + "' " + selected + ">" + dir_name + "</option>";
            }

            $("#dir_name").html(htmlString);
        }
    });
}


//get Staff Name dropdown
function getStaffName(role_type) {
    var staff_id_upd = $('#staff_id_upd').val();
    $.ajax({
        url: 'manageUser/ajaxGetStaffName.php',
        type: 'post',
        data: { 'role_type': role_type },
        dataType: 'json',
        success: function (response) {

            // Sort response alphabetically by staff_name
            var sortedResponse = response.slice().sort(function(a, b) {
                return a.staff_name.localeCompare(b.staff_name);
            });

            var htmlString = "<option value=''>Select Staff Name</option>";

            for (var i = 0; i < sortedResponse.length; i++) {
                var staff_id = sortedResponse[i]['staff_id'];
                var staff_name = sortedResponse[i]['staff_name'];
                var selected = '';
                if (staff_id_upd != '' && staff_id_upd == staff_id) {
                    selected = 'selected';
                }
                htmlString += "<option value='" + staff_id + "' " + selected + ">" + staff_name + "</option>";
            }

            $("#staff_name").html(htmlString);
        }
    });
}

//Table View
//get Director Details
function geDirectorDetails(dir_id) {
    $('.userInfoTable').show();
    $('.conditionalInfo').hide();
    $('.occupationInfo').hide();
    $.ajax({
        url: 'manageUser/ajaxResetUserTable1.php',
        data: { 'dir_id': dir_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#userInfoTable tbody').empty();
            $('#userInfoTable').append(`<tbody><tr><td>` + response[0]['dir_code'] + `</td><td>` + response[0]['dir_name'] + `</td><td>` + response[0]['mail_id'] + `</td></tr></tbody>`);

            $('#company_id').val(response[0]['company_id']);
            $('#company_name').val(response[0]['company_name']);

            //setting full name and mail id for insert table
            $('#full_name').val(response[0]['dir_name']);
            $('#email').val(response[0]['mail_id']);

            getBranchDropdown(response[0]['company_id']);
        }
    })
}

//get Agent Details
function getAgentDetails(ag_id) {
    $('.userInfoTable').show();
    $('.conditionalInfo').show();
    $('.occupationInfo').hide();
    $.ajax({
        url: 'manageUser/ajaxResetUserTable.php',
        data: { 'ag_id': ag_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#userInfoTable tbody').empty();
            $('#userInfoTable').append(`<tbody><tr><td>` + response[0]['ag_code'] + `</td><td>` + response[0]['ag_name'] + `</td><td>` + response[0]['mail'] + `</td></tr></tbody>`);

            $('#conditionalInfo tbody').empty();
            $('#conditionalInfo').append(`<tbody><tr><td>` + response[0]['loan_category'] + `</td>
            <td>`+ response[0]['scheme'] + `</td><td>` + response[0]['loan_payment'] + `</td><td>` + response[0]['responsible'] + `</td><td>` + response[0]['collection_point'] + `</td></tr></tbody>`);

            if (response[0]['collection_point'] == 'Yes') {
                $('.line_div').show()
            } else {
                $('.line_div').hide()
            }

            $('#company_id').val(response[0]['company_id']);
            $('#company_name').val(response[0]['company_name']);

            //setting full name and mail id for insert table
            $('#full_name').val(response[0]['ag_name']);
            $('#email').val(response[0]['mail']);

            getBranchDropdown(response[0]['company_id']);
        }
    })
}

//get Staff Details
function getStaffDetails(staff_id) {
    $('.userInfoTable').show();
    $('.occupationInfo').show();
    $('.conditionalInfo').hide();
    $.ajax({
        url: 'manageUser/ajaxResetDetailsTable.php',
        data: { 'staff_id': staff_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#userInfoTable tbody').empty();
            $('#userInfoTable').append(`<tbody><tr><td>` + response[0]['staff_code'] + `</td><td>` + response[0]['staff_name'] + `</td><td>` + response[0]['mail'] + `</td></tr></tbody>`);

            $('#occupationInfo tbody').empty();
            $('#occupationInfo').append(`<tbody><tr><td>` + response[0]['company_name'] + `</td><td>` + response[0]['department'] + `</td>
            <td>`+ response[0]['team'] + `</td><td>` + response[0]['designation'] + `</td></tr></tbody>`);

            $('#company_id').val(response[0]['company_id']);
            $('#company_name').val(response[0]['company_name']);

            //setting full name and mail id for insert table
            $('#full_name').val(response[0]['staff_name']);
            $('#email').val(response[0]['mail']);

            getBranchDropdown(response[0]['company_id']);
        }
    })
}

//Mapping info
//get Branch Dropdown
function getBranchDropdown(company_id) {
    var branch_id_upd = $('#branch_id_upd').val().split(',');
    $.ajax({
        url: 'manageUser/getBranchList.php',
        data: { 'company_id': company_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            branchMultiselect.clearStore();
            for (var i = 0; i < response.length; i++) {
                var branch_id = response[i]['branch_id'];
                var branch_name = response[i]['branch_name'];
                var selected = '';
                if (branch_id_upd != '') {
                    for (var j = 0; j < branch_id_upd.length; j++) {
                        if (branch_id_upd[j] == branch_id) {
                            selected = 'selected';
                        }
                    }
                }
                var items = [{
                    value: branch_id,
                    label: branch_name,
                    selected: selected
                }]
                branchMultiselect.setChoices(items);
                branchMultiselect.init();
            }
        }
    })
}

//get Agent Dropdown
function getAgentDropdown(company_id) {
    var agent_id_upd = $('#agentforstaff_upd').val().split(',');
    $.ajax({
        url: 'manageUser/getAgentDropdown.php',
        data: { 'company_id': company_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            agentMultiselect.clearStore();
            for (var i = 0; i < response.length; i++) {
                var ag_id = response[i]['ag_id'];
                var ag_name = response[i]['ag_name'];
                var selected = '';
                if (agent_id_upd != '') {
                    for (var j = 0; j < agent_id_upd.length; j++) {
                        if (agent_id_upd[j] == ag_id) {
                            selected = 'selected';
                        }
                    }
                }
                var items = [{
                    value: ag_id,
                    label: ag_name,
                    selected: selected
                }]
                agentMultiselect.setChoices(items);
                agentMultiselect.init();
            }
        }
    })
}

//get Loan category Dropdown
function getLoanCatDropdown(updId, multipleSelectID) {
    var loan_cat_upd = $(updId).val().split(',').map(s => s.trim());
    $.ajax({
        url: 'manageUser/getLoanCatDropdown.php',
        data: {},
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            multipleSelectID.clearChoices(); // correct method
            const items = response.map(item => ({
                value: item.loan_cat_id,
                label: item.loan_cat_name,
                selected: loan_cat_upd.includes(String(item.loan_cat_id))
            }));
            multipleSelectID.setChoices(items, 'value', 'label', true);
        }
    })
}

//get Line Dropdown
function getLineDropdown(branch_id) {
    var line_id_upd = $('#line_id_upd').val().split(',');
    $.ajax({
        url: 'manageUser/getLineDropdown.php',
        data: { 'branch_id': branch_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            lineMultiselect.clearStore();
            for (var i = 0; i < response.length; i++) {
                var map_id = response[i]['map_id'];
                var line_name = response[i]['line_name'];
                var selected = '';
                if (line_id_upd != '') {
                    for (var j = 0; j < line_id_upd.length; j++) {
                        if (line_id_upd[j] == map_id) {
                            selected = 'selected';
                        }
                    }
                }
                var items = [{
                    value: map_id,
                    label: line_name,
                    selected: selected
                }]
                lineMultiselect.setChoices(items);
                lineMultiselect.init();
            }
        }
    })
}

// linedropdown in duefollowup
function getdueFollupLineDropdown(branch_id) {
    var dueFollowUp_upd = $('#due_followup_lines_upd').val().split(',');
    if (dueFollowUp_upd != '') {
        $('.due_followupline_div').show();
    }
    
    $.ajax({
        url: 'manageUser/getDueFollowUpLineName.php',
        data: { branch_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            dueFollowupLines.clearStore();
            for (var i = 0; i < response.length; i++) {
                var map_id = response[i]['map_id'];
                var duefollowup_name = response[i]['duefollowup_name'];
                var selected = '';
                if (dueFollowUp_upd != '') {
                    for (var j = 0; j < dueFollowUp_upd.length; j++) {
                        if (dueFollowUp_upd[j] == map_id) {
                            selected = 'selected';
                        }
                    }
                }
                var items = [{
                    value: map_id,
                    label: duefollowup_name,
                    selected: selected
                }]
                dueFollowupLines.setChoices(items);
                dueFollowupLines.init();
            }
        }
    })
}

//get Group Dropdown
function getGroupDropdown(branch_id) {
    var group_id_upd = $('#group_id_upd').val().split(',');
    $.ajax({
        url: 'manageUser/getGroupDropdown.php',
        data: { 'branch_id': branch_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            groupMultiselect.clearStore();
            for (var i = 0; i < response.length; i++) {
                var map_id = response[i]['map_id'];
                var group_name = response[i]['group_name'];
                var selected = '';
                if (group_id_upd != '') {
                    for (var j = 0; j < group_id_upd.length; j++) {
                        if (group_id_upd[j] == map_id) {
                            selected = 'selected';
                        }
                    }
                }
                var items = [{
                    value: map_id,
                    label: group_name,
                    selected: selected
                }]
                groupMultiselect.setChoices(items);
                groupMultiselect.init();
            }
        }
    })
}

//Get Bank Details list
function getBankDetails() {
    var bank_details_upd = $('#bank_details_upd').val().split(',');
    if (bank_details_upd != '') {
        $('.bank_details').show();
    }
    $.ajax({
        url: 'manageUser/getBankDetails.php',
        data: {},
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            bankMultiselect.clearStore();
            for (var i = 0; i < response.length; i++) {
                var id = response[i]['id'];
                var short_name = response[i]['short_name'];
                var acc_no = response[i]['acc_no'].slice(-5);
                var selected = '';
                if (bank_details_upd != '') {
                    for (var j = 0; j < bank_details_upd.length; j++) {
                        if (bank_details_upd[j] == id) {
                            selected = 'selected';
                        }
                    }
                }
                var items = [{
                    value: id,
                    label: short_name + ' - ' + acc_no,
                    selected: selected
                }]
                bankMultiselect.setChoices(items);
                bankMultiselect.init();
            }
            $('#bank_details1').trigger('change');// trigger change event for updating once again from temp select box to hidden input
        }
    })
}

//Get promotion access list
function getProAccess() {
    var promotion_access_upd = $('#promotion_access_upd').val().split(',');

    const valueToLabelMap = {
        '1': 'Existing',
        '2': 'New ',
        '3': 'Repromotion', 
        '4': 'Events' 

    };
    promotionAccess.clearStore();

    let items = [];

    $.each(valueToLabelMap, function(val, label) {
        let selected = '';

        if (promotion_access_upd.includes(val)) {
            selected = 'selected';
        }

        items.push({
            value: val,  
            label: label,
            selected: selected 
        });
    });
    promotionAccess.setChoices(items);
    promotionAccess.init();
}

function getcashTallyAccess() {
    var cash_tally_access_upd = $('#cash_tally_access_upd').val().split(',');

    const valueToLabelMap = {
        '1': 'Collection',
        '2': 'Loan Issued ',
        '3': 'Expenses' ,
        '4': 'Other Transaction' 
    };
    cash_tally_access_select.clearStore();

    let items = [];

    $.each(valueToLabelMap, function(val, label) {
        let selected = '';

        if (cash_tally_access_upd.includes(val)) {
            selected = 'selected';
        }

        items.push({
            value: val,  
            label: label,
            selected: selected 
        });
    });
    cash_tally_access_select.setChoices(items);
    cash_tally_access_select.init();
}

//Screen Mapping
//modules checkbox events
function checkbox(checkboxesToEnable, module) {
    if (module.checked) {
        checkboxesToEnable.forEach(function (checkbox) {
            checkbox.disabled = false;
        });
    } else {
        checkboxesToEnable.forEach(function (checkbox) {
            checkbox.disabled = true;
            checkbox.checked = false;
        });
    }
}

// for taking selected values from multiselect to hidden input field. so that it can be passed as comma imploded string
function multipleSelectSort(instanceId, storeId){
    const screenList = instanceId.getValue();
    const screenSortedStr = screenList
        .map(item => (item && item.value !== undefined ? String(item.value) : ''))
        .sort((a, b) => a.localeCompare(b, undefined, { numeric: true }))
        .join(',');

    $(storeId).val(screenSortedStr); 
    
    return screenSortedStr ? screenSortedStr.split(',').length : 0;
}

function validation() {
    var role = $('#role').val();
    var validation = true;

    multipleSelectSort(branchMultiselect, '#branch_id');
    multipleSelectSort(agentMultiselect, '#agentforstaff');

    let groupSort = multipleSelectSort(groupMultiselect, '#group');
    if (groupSort == 0) {  
        $('#groupCheck').show();
        validation = false; 
    } else { 
        $('#groupCheck').hide(); 
    }

    let lineSort = multipleSelectSort(lineMultiselect, '#line');
    var role = $('#role').val();
    if (lineSort == 0 && role != '2') {  
        $('#lineCheck').show(); 
        validation = false;
    } else { 
        $('#lineCheck').hide(); 
    }

    let varLoanCatchecked = $('#verification').is(':checked');
    if(varLoanCatchecked){
        let varLoanCat = multipleSelectSort(verificationloanCatMultiselect, '#ver_loan_cat');
        if (varLoanCat == 0) {  
            $('#ver_loan_catCheck').show(); 
            validation = false;
        } else { 
            $('#ver_loan_catCheck').hide(); 
        }
    }

    let appLoanCatchecked = $('#approval').is(':checked');
    if(appLoanCatchecked){
        let appLoanCat = multipleSelectSort(approvalloanCatMultiselect, '#app_loan_cat');
        if (appLoanCat == 0) {  
            $('#app_loan_catCheck').show(); 
            validation = false;
        } else { 
            $('#app_loan_catCheck').hide(); 
        }
    }

    let ackLoanCatchecked = $('#acknowledgement').is(':checked');
    if(ackLoanCatchecked){
        let ackLoanCat = multipleSelectSort(acknowledgementloanCatMultiselect, '#ack_loan_cat');
        if (ackLoanCat == 0) {  
            $('#ack_loan_catCheck').show(); 
            validation = false;
        } else { 
            $('#ack_loan_catCheck').hide(); 
        }
    }

    var isPromotionChecked = $('#promotion_activity').is(':checked');
    if(isPromotionChecked){
        let proAtyAccessIdSort = multipleSelectSort(promotionAccess, '#pro_aty_access_id');
        if (proAtyAccessIdSort == 0) {  
            $('#proCheck').show();
            validation = false; 
        } else { 
            $('#proCheck').hide(); 
        }  
    }

    let dueFollowupChecked = $('#due_followup').is(':checked');
    let dueFollowupIdSort = multipleSelectSort(dueFollowupLines, '#due_follup_line_id');

    if (dueFollowupChecked && dueFollowupIdSort == 0) { //Checkbox checked but no lines selected 
        $('.duefollowupCheck').show();
        $('.dueFollowupCheck').hide(); 
        validation = false; 
        
    } else if(!dueFollowupChecked && dueFollowupIdSort > 0){ //Lines selected but checkbox not checked 
        $('.dueFollowupCheck').show();
        $('.duefollowupCheck').hide(); 
        validation = false; 
    } else {
        $('.dueFollowupCheck').hide();
        $('.duefollowupCheck').hide(); 
    }

    var cash_tally = document.querySelector('#cash_tally');
    var cash_tally_access_selects = cash_tally_access_select.getValue();
    if (!cash_tally.checked) {
        $('#cash_tally_access').val('')
    } else{
        if(cash_tally_access_selects.length == 0){
            event.preventDefault();
            $('.cash_tally_access').show();
            $('.cash_tally_accessCheck').show();
            validation = false;
        }else{
            $('.cash_tally_accessCheck').hide();
        }
    }

    var conf_followup = document.querySelector('#conf_followup');
    var CFGroupOrDuefollowup =  $('#conf_followup_line_or_duefollowup').val();
    if (!conf_followup.checked) {
        $('#conf_followup_line_or_duefollowup').val('')
    } else{
        if(CFGroupOrDuefollowup ==''){
            event.preventDefault();
            $('.conf_followup_div').show();
            $('.confFollowupCheck').show();
            validation = false;
        }else{
            $('.confFollowupCheck').hide();
        }
    }


    if (role == '1') {
        $('#roleCheck').hide();
        var role_type = $('#role_type').val();
        if (role_type == '11' || role_type == '12') {
            $('#roleTypeCheck').hide();
            var dir_name = $('#dir_name').val();
            if (dir_name == '') {
                $('#dirnameCheck').show();
                validation = false;
            } else {
                $('#dirnameCheck').hide();
            }
        } else {
            $('#roleTypeCheck').show();
            validation = false;
        }
    } else if (role == '2') {
        $('#roleCheck').hide();
        var ag_name = $('#ag_name').val();
        if (ag_name == '') {
            $('#agnameCheck').show();
            validation = false;
        } else {
            $('#agnameCheck').hide();
        }
    } else if (role == '3') {
        $('#roleCheck').hide();
        var role_type = $('#role_type').val();
        if (role_type != '') {
            $('#roleTypeCheck').hide();
            var staff_name = $('#staff_name').val();
            if (staff_name == '') {
                $('#staffnameCheck').show();
                validation = false;
            } else {
                $('#staffnameCheck').hide();
            }
        } else {
            $('#roleTypeCheck').show();
            validation = false;
        }
    } else {
        $('#roleCheck').show();
        validation = false;
    }

    var user_id = $('#user_id').val();
    if (user_id == '') {
        $('#usernameCheck').show();
        validation = false;
    } else {
        $('#usernameCheck').hide();
    }

    var pass = $('#password').val();
    if (pass == '') {
        $('#passCheck').show();
        validation = false;
    } else {
        $('#passCheck').hide();
    }

    var cnf_pass = $('#cnf_password').val();
    if (cnf_pass == '') {
        $('#cnfpassCheck').show();
        validation = false;
    } else {
        $('#cnfpassCheck').hide();
    }

    if (pass != cnf_pass) {
        $('#passworkCheck').show();
        validation = false;
    } else { 
        $('#passworkCheck').hide(); 
    }

    var branch_id = $('#branch_id').val();
    if (branch_id == '') {
        $('#BranchCheck').show();
        validation = false;
    } else {
        $('#BranchCheck').hide();
    }

    var cash_tally = document.querySelector('#cash_tally');
    if (!cash_tally.checked) {
        $('#bank_details').val('')
    }

    var update = document.querySelector('#update');
    var update_screen = updateScreen.getValue();
    if (!update.checked) {
        $('#update_screen_id').val('')
    } else{
        if(update_screen.length == 0){
            $('.update_screen_div').show();
            $('.updateScreenCheck').show();
            validation = false;
        }else{
            $('.updateScreenCheck').hide();
        }
    }

    // validation for report
    var reportmodule = document.querySelector('#reportmodule');
    var report_access = $('#report_access').val();

    // Case 1: Checkbox checked but dropdown empty
    if (reportmodule.checked && report_access == '') {
        $('#reportAccessCheck').show();
        validation = false;
    } else {
        $('#reportAccessCheck').hide();
    }

    // Case 2: Dropdown has value but checkbox not checked
    if (!reportmodule.checked && report_access != '') {
        $('.reportCheck').show();
        validation = false;
    } else {
        $('.reportCheck').hide();
    }

    // validtaion for promotion activity
    var promotion_activity = document.querySelector('#promotion_activity');
    var promotion_activity_mapping_access = $('#promotion_activity_mapping_access').val();

    // Case 1: Checkbox checked but dropdown empty
    if (promotion_activity.checked && promotion_activity_mapping_access == '') {
        $('#proMapCheck').show();
        validation = false;
    } else {
        $('#proMapCheck').hide();
    }

    // Case 2: Dropdown has value but checkbox not checked
    if (!promotion_activity.checked && promotion_activity_mapping_access != '') {
        $('.promotionActivityCheck').show();
        validation = false;
    } else {
        $('.promotionActivityCheck').hide();
    }

return validation;
}

//Edit Screen Functionalities
function getRoleBasedDetails(role) {
    if (role == '1') { //Director
        $(".role_type").show();
        $('.director').hide();
        $('.agent').hide();
        $('.staff').hide();
        $('.line_div').show();
        $("#role_type").empty();
        $('#role_type').append(`<option value="">Select Role Type</option><option value='11'>Director</option><option value='12'>Executive Director</option>`);

    } else if (role == '2') { //Agent
        $(".role_type").hide();
        $('.director').hide();
        $('.agent').show();
        $('.staff').hide();
        $('.line_div').hide();

    } else if (role == '3') { //Staff
        $(".role_type").show();
        $('.director').hide();
        $('.agent').hide();
        $('.staff').hide();
        $('.line_div').show();

        getStaffTypeDropdown();

    } else {
        $(".role_type").hide();
        $('.director').hide();
        $('.agent').hide();
        $('.staff').hide();
        $('.line_div').hide();
        $("#role_type").empty();
    }
}

function getRoleTypeBasedDetails(role, role_type) {
    if (role == '1') {
        $('.director').show();
        $('.agent').hide();
        $('.staff').hide();
        if (role_type == '11') {
            getDirectorName('1');
        } else if (role_type == '12') {
            getDirectorName('2');
        }
    } else if (role == '3') {
        $('.director').hide();
        $('.agent').hide();
        $('.staff').show();
        getStaffName(role_type);
    }
}