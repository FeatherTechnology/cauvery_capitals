//Sub Area Multi select initialization
const areaMultiselect = new Choices('#area_dummy', {
    removeItemButton: true,
    noChoicesText: null,
    placeholder: true,
    placeholderValue: 'Select Area Name',
    allowHTML: true
});
const areaMultiselect1 = new Choices('#area_dummy1', {
    removeItemButton: true,
    noChoicesText: null,
    placeholder: true,
    placeholderValue: 'Select Area Name',
    allowHTML: true
});
const areaMultiselect2 = new Choices('#area_dummy2', {
    removeItemButton: true,
    noChoicesText: null,
    placeholder: true,
    placeholderValue: 'Select Area Name',
    allowHTML: true,
    shouldSort: false
});
const intance = new Choices('#sub_area_dummy', {
    removeItemButton: true,
    noChoicesText: null,
    placeholder: true,
    placeholderValue: 'Select Sub Area Name',
    allowHTML: true
});
const intance1 = new Choices('#sub_area_dummy1', {
    removeItemButton: true,
    noChoicesText: null,
    placeholder: true,
    placeholderValue: 'Select Sub Area Name',
    allowHTML: true
});
// const intance2 = new Choices('#sub_area_dummy2', {
//     removeItemButton: true,
//     noChoicesText: null,
//     placeholder: true,
//     placeholderValue: 'Select Sub Area Name',
//     allowHTML: true,
//     shouldSort: false
// });
const dueLine = new Choices('#due_line', {
    removeItemButton: true,
    noChoicesText: null,
    placeholder: true,
    placeholderValue: 'Select Line Name',
    allowHTML: true
});

const subStatusMultiselect = new Choices('#sub_status_mapping', {
    removeItemButton: true,
    noChoicesText: 'Select Customer Status',
    allowHTML: true
});
// Document is ready
$(document).ready(function () {

    //Mapping Type Change
    $('#line,#group').click(function () {
        var mapping_type = $('input[name=mapping_type]:checked').val();
        if (mapping_type == 'line') { $('.line_mapping').show(); $('.group_mapping').hide(); }
        if (mapping_type == 'group') { $('.line_mapping').hide(); $('.group_mapping').show(); }
    })

    {//To Order Alphabetically
        var firstOption = $("#area option:first-child");
        $("#area").html($("#area option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#area").prepend(firstOption);
    }
    {//To Order Alphabetically
        var firstOption = $("#area1 option:first-child");
        $("#area1").html($("#area1 option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#area1").prepend(firstOption);
    }
    {//To Order Alphabetically
        var firstOption = $("#area2 option:first-child");
        $("#area2").html($("#area2 option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#area2").prepend(firstOption);
    }

    // ************************************************************** Line Mapping *************************************************************************************** 

    $('#area_dummy').change(function () {
        //Area Multi select store
        var area_list = areaMultiselect.getValue();
        var area = '';
        for (var i = 0; i < area_list.length; i++) {
            if (i > 0) {
                area += ',';
            }
            area += area_list[i].value;
        }
        var arr = area.split(",");
        arr.sort(function (a, b) { return a - b });
        var sortedStr = arr.join(",");
        $('#area').val(sortedStr);
        var areaselected = $('#area').val();

        getAreaBasedSubArea(areaselected);
    })

    // if ($('#type').val() == 'line') { // loan only if line
    //     var companySelected = $('#company_id').val();
    //     getBranchDropdown(companySelected);
    // }

    //on submit add sub area list to hidden input
    $('#submit_area_mapping_line').click(function () {
        var sub_area_list = intance.getValue();
        var area_list = areaMultiselect.getValue();

        //Validation
        var line_name = $('#line_name').val(); var company_name = $('#company_name').val(); var branch = $('#branch').val(); var area = $('#area').val();
        if (line_name == '' || company_name == '' || branch == '' || area_list.length == 0 || sub_area_list.length == 0) {
            Swal.fire({
                timerProgressBar: true,
                timer: 2000,
                title: 'Please Fill out Mandatory fields!',
                icon: 'error',
                showConfirmButton: true
            });
            event.preventDefault();
        }

        //Area Multi select store
        var area_list = areaMultiselect.getValue();
        var area = '';
        for (var i = 0; i < area_list.length; i++) {
            if (i > 0) {
                area += ',';
            }
            area += area_list[i].value;
        }
        var arr = area.split(",");
        arr.sort(function (a, b) { return a - b });
        var sortedStr = arr.join(",");
        $('#area').val(sortedStr);

        //Sub area multi select store
        var sub_area_list = intance.getValue();
        var sub_area = '';
        for (var i = 0; i < sub_area_list.length; i++) {
            if (i > 0) {
                sub_area += ',';
            }
            sub_area += sub_area_list[i].value;
        }
        var arr = sub_area.split(",");
        arr.sort(function (a, b) { return a - b });
        var sortedStr = arr.join(",");
        $('#sub_area').val(sortedStr);
    })

    // ************************************************************** Group Mapping *************************************************************************************** 

    $('#area_dummy1').change(function () {
        //Area Multi select store
        var area_list = areaMultiselect1.getValue();
        var area = '';
        for (var i = 0; i < area_list.length; i++) {
            if (i > 0) {
                area += ',';
            }
            area += area_list[i].value;
        }
        var arr = area.split(",");
        arr.sort(function (a, b) { return a - b });
        var sortedStr = arr.join(",");
        $('#area1').val(sortedStr);
        var areaselected = $('#area1').val();

        getAreaBasedSubArea1(areaselected);
    });

    // $('#company_id1').change(function(){
    // if ($('#type').val() == 'group') {
    //     var companySelected = $('#company_id1').val();
    //     getBranchDropdown1(companySelected);
    // }
    // })
    //on submit add sub area list to hidden input
    $('#submit_area_mapping_group').click(function () {
        var sub_area_list = intance1.getValue();
        var area_list = areaMultiselect1.getValue();
        //Validation
        var group_name = $('#group_name').val(); var company_name = $('#company_name1').val(); var branch = $('#branch1').val();
        if (group_name == '' || company_name == '' || branch == '' || area_list.length == 0 || sub_area_list.length == 0) {
            Swal.fire({
                timerProgressBar: true,
                timer: 2000,
                title: 'Please Fill out Mandatory fields!',
                icon: 'error',
                showConfirmButton: true,
                confirmButtonColor: '#0c70ab'
            });
            event.preventDefault();
        }
        //Area Multi select store
        var area_list = areaMultiselect1.getValue();
        var area = '';
        for (var i = 0; i < area_list.length; i++) {
            if (i > 0) {
                area += ',';
            }
            area += area_list[i].value;
        }
        var arr = area.split(",");
        arr.sort(function (a, b) { return a - b });
        var sortedStr = arr.join(",");
        $('#area1').val(sortedStr);

        //Sub Area Multi select store
        var sub_area_list = intance1.getValue();
        var sub_area = '';
        for (var i = 0; i < sub_area_list.length; i++) {
            if (i > 0) {
                sub_area += ',';
            }
            sub_area += sub_area_list[i].value;
        }
        var arr = sub_area.split(",");
        arr.sort(function (a, b) { return a - b });
        var sortedStr = arr.join(",");
        $('#sub_area1').val(sortedStr);
    })


    // ************************************************************** Due Followup Mapping ****************************************************************** 

    $('#branch2').change(function(){
        let branchId = $(this).val();

        if(branchId){
            getLineNameDropdown(branchId);

        }else{
            areaMultiselect2.clearStore();
            // intance2.clearStore();
            dueLine.clearStore();
            $('#cus_count, #loan_count').val('');
            
        }

    });
    
    $('#area_dummy2').change(function () {
        // Get values from multiselect and sort
        const area_list = areaMultiselect2.getValue(); // returns array of objects like [{value: "1", label: "Area 1"}, ...]
    
        // Handle Select All logic
        selectAllAreas(area_list, areaMultiselect2);
    
        const sortedStr = areaMultiselect2.getValue()
            .map(item => item.value)
            .filter(val => val !== 'select_all') // exclude 'select_all' from final string
            .sort((a, b) => a - b)
            .join(',');
    
        $('#area2').val(sortedStr);
    
        // getAreaBasedSubArea2(sortedStr);
        getCusLoanCount();
    });
    
    // $('#sub_area_dummy2').change(function () {
    //     // Get values from multiselect and sort
    //     const subarea_list = intance2.getValue(); // returns array of objects like [{value: "1", label: "Area 1"}, ...]
    
    //     // Handle Select All logic
    //     selectAllAreas(subarea_list, intance2);

    // });
    
    $('#due_line').change(function () {
        // Get values from multiselect and sort
        const lineList = dueLine.getValue();
        const lineSortedStr = lineList
            .map(item => item.value)
            .sort((a, b) => a - b)
            .join(',');
    
        $('#dueline').val(lineSortedStr);
    
        if(lineSortedStr){
            getArea2(lineSortedStr);
        }
    });
    
    $('#loan_cat1').change(function () {
       
        const loanList = $('#loan_cat1').val();
        $('#loan_cat').val(loanList);

        getCusLoanCount();
    });

    $('#sub_status_mapping').change(function(){
        getSubStatusValues();
        getCusLoanCount();
    });

    $('#refresh_count').click(function(event){
        event.preventDefault();            
        getCusLoanCount();
    })
    //on submit add sub area list to hidden input
    $('#submit_area_mapping_duefollowup').click(function () {
        var loanCategory = $('#loan_cat1').val();
        var due_line = dueLine.getValue();
        var area_list = areaMultiselect2.getValue();
        // var sub_area_list = intance2.getValue();
        var subStatus = subStatusMultiselect.getValue();

        //Validation
        var duefollowup_name = $('#duefollowup_name').val(); var company_name = $('#company_name2').val(); var branch = $('#branch2').val(); var cuscnt = $('#cus_count').val(); var loancnt = $('#loan_count').val();
        if (duefollowup_name == '' || company_name == '' || branch == '' || loanCategory == '' || due_line.length == 0 || subStatus.length == 0 || area_list.length == 0 || cuscnt == '' || loancnt == '' ) {
            Swal.fire({
                timerProgressBar: true,
                title: 'Please Fill out Mandatory fields!',
                icon: 'error',
                showConfirmButton: true
            });
            event.preventDefault();
        }

        // Line multi-select
        const dueLineStr = getSortedCommaSeparatedValues(dueLine);
        $('#dueline').val(dueLineStr);

        // Area multi-select
        const areaStr = getSortedCommaSeparatedValues(areaMultiselect2);
        $('#area2').val(areaStr);

        // // Sub-area multi-select
        // const subAreaStr = getSortedCommaSeparatedValues(intance2);
        // $('#sub_area2').val(subAreaStr);        

        $('#loan_cat').val(loanCategory);

        // Sub Status multi-select
        getSubStatusValues();
        

    });

});//document ready end

//on page load for Edit page
$(function () {
    let type = $('#type').val();
    if (type == 'line') {
        getArea();
        let area = $('#area_id_upd').val();
        getAreaBasedSubArea(area);
        getBranchDropdown()
    } else if (type == 'group') {
        getArea1();
        let area = $('#area_id1_upd').val();
        getAreaBasedSubArea1(area);
        getBranchDropdown1();
    } else if (type == 'duefollowup') {
        initform();

        async function initform(){
            await getBranchDropdown2();
            await getLoanCatDropdown();
            await getSubStsMapping();
            let upd = $('#id').val();
            if(upd > 0){
                let branchid = $('#branch_id_upd2').val();
                let lineid = $('#due_line_name').val();
                await getLineNameDropdown(branchid);
                await getArea2(lineid);
                // let area = $('#area_id2_upd').val();
                // await getAreaBasedSubArea2(area);
            }
        }
    }

})

//Get Area 
function getArea() {
    var area_id_upd = $('#area_id_upd').val();
    var values = area_id_upd.split(',');
    var map = 'line';
    $.ajax({
        url: 'areaMapping/ajaxGetArea.php',
        type: 'post',
        data: { 'map': map },
        dataType: 'json',
        success: function (response) {

            areaMultiselect.clearStore();
            var len = response.length;
            for (var i = 0; i < len; i++) {
                var area_id = response[i]['area_id'];
                var area_name = response[i]['area_name'];
                var checked = '';
                var checked = response[i]['disabled'];
                var selected = '';
                if (area_id_upd != '' && values.includes(area_id.toString())) {
                    selected = 'selected';
                    checked = false;
                }
                var items = [
                    {
                        value: area_id,
                        label: area_name,
                        selected: selected,
                        disabled: checked,
                    }
                ];
                areaMultiselect.setChoices(items);
                areaMultiselect.init();

            }
        }
    });
}

//Get Area 
function getArea1() {
    var area_id_upd = $('#area_id1_upd').val();
    var values = area_id_upd.split(',');
    var map = 'group';
    $.ajax({
        url: 'areaMapping/ajaxGetArea.php',
        type: 'post',
        data: { 'map': map },
        dataType: 'json',
        success: function (response) {

            areaMultiselect1.clearStore();
            var len = response.length;
            for (var i = 0; i < len; i++) {
                var area_id = response[i]['area_id'];
                var area_name = response[i]['area_name'];
                var checked = '';
                var checked = response[i]['disabled'];
                var selected = '';
                if (area_id_upd != '' && values.includes(area_id.toString())) {
                    selected = 'selected';
                    checked = false;
                }
                var items = [
                    {
                        value: area_id,
                        label: area_name,
                        selected: selected,
                        disabled: checked,
                    }
                ];
                areaMultiselect1.setChoices(items);
                areaMultiselect1.init();

            }
        }
    });
}
//Get Area 
function getArea2(lineid) {
    return new Promise((resolve, reject) => {
        var area_id_upd = $('#area_id2_upd').val();
        var areaid = $('#area2').val();
        var branchid = $('#branch2').val();
        var values = area_id_upd.split(',');
        var map = 'duefollowup';
        var loanCat = $('#loan_cat1').val();
        $.ajax({
            url: 'areaMapping/ajaxGetMappedArea.php',
            type: 'post',
            data: { 'map': map, 'lineid': lineid, 'loanCatId': loanCat, 'branchid': branchid },
            dataType: 'json',
            success: function (response) {
                areaMultiselect2.clearStore();
            
                // Start with "Select All" manually
                var items = [
                    {
                        value: 'select_all',
                        label: 'Select All',
                        selected: '',
                        disabled: ''
                    }
                ];
            
                var len = response.length;
                var areaItems = [];
            
                for (var i = 0; i < len; i++) {
                    var area_id = response[i]['area_id'];
                    var area_name = response[i]['area_name'];
                    var checked = response[i]['disabled'];
                    var selected = '';
            
                    if (area_id_upd && values.includes(area_id.toString())) {
                        selected = 'selected';
                        checked = false;
                    }
                    if (areaid && areaid.includes(area_id.toString())) {
                        selected = 'selected';
                        checked = false;
                    }
            
                    areaItems.push({
                        value: area_id,
                        label: area_name,
                        selected: selected,
                        disabled: checked
                    });
                }
            
                // Sort the area items alphabetically by label
                areaItems.sort(function (a, b) {
                    return a.label.localeCompare(b.label);
                });
            
                // Merge "Select All" with sorted area items
                items = items.concat(areaItems);
            
                areaMultiselect2.setChoices(items, 'value', 'label', true);
                resolve(); // Notify completion
            },
            error: function (xhr, status, error) {
                reject(error); // Handle errors
            }
        });
        
    });
}

//Get Area Based Sub Area
function getAreaBasedSubArea(area) {
    var sub_area_upd = $('#sub_area_upd').val();
    var values = sub_area_upd.split(',');
    var map = 'line';
    $.ajax({
        url: 'areaMapping/ajaxGetSubArea.php',
        type: 'post',
        data: { 'area': area, 'map': map },
        dataType: 'json',
        success: function (response) {

            intance.clearStore();
            var len = response.length;
            for (var i = 0; i < len; i++) {
                for (var j = 0; j < response[i].length; j++) {

                    var sub_area_id = response[i][j]['sub_area_id'];
                    var sub_area_name = response[i][j]['sub_area_name'];
                    var checked = response[i][j]['disabled'];
                    var selected = '';
                    if (sub_area_upd != '' && values.includes(sub_area_id.toString())) {
                        selected = 'selected';
                        checked = false;
                    }
                    var items = [
                        {
                            value: sub_area_id,
                            label: sub_area_name,
                            selected: selected,
                            disabled: checked,
                        }
                    ];
                    intance.setChoices(items);
                    intance.init();
                }

            }
        }
    });
}
//Get Area Based Sub Area
function getAreaBasedSubArea1(area) {
    var sub_area_upd = $('#sub_area_upd1').val();
    var values = sub_area_upd.split(',');
    var map = 'group';
    $.ajax({
        url: 'areaMapping/ajaxGetSubArea.php',
        type: 'post',
        data: { 'area': area, 'map': map },
        dataType: 'json',
        success: function (response) {

            intance1.clearStore();
            var len = response.length;
            for (var i = 0; i < len; i++) {
                for (var j = 0; j < response[i].length; j++) {

                    var sub_area_id = response[i][j]['sub_area_id'];
                    var sub_area_name = response[i][j]['sub_area_name'];
                    var checked = response[i][j]['disabled'];
                    var selected = '';
                    if (sub_area_upd != '' && values.includes(sub_area_id.toString())) {
                        selected = 'selected';
                        checked = false;
                    }
                    var items = [
                        {
                            value: sub_area_id,
                            label: sub_area_name,
                            selected: selected,
                            disabled: checked,
                        }
                    ];
                    intance1.setChoices(items);
                    intance1.init();
                }

            }
        }
    });
}

//Get Area Based Sub Area
// function getAreaBasedSubArea2(area) {
//     return new Promise((resolve, reject) => {
//         var sub_area_upd = $('#sub_area_upd2').val();
//         var values = sub_area_upd.split(',');
//         var map = 'duefollowup';
//         $.ajax({
//             url: 'areaMapping/ajaxGetSubArea.php',
//             type: 'post',
//             data: { 'area': area, 'map': map },
//             dataType: 'json',
//             success: function (response) {

//                 intance2.clearStore();
                        
//                 // Start with "Select All" manually
//                 var items = [
//                     {
//                         value: 'select_all',
//                         label: 'Select All',
//                         selected: ''
//                     }
//                 ];

//                 var len = response.length;
//                 var subareaItems = [];

//                 for (var i = 0; i < len; i++) {
//                     for (var j = 0; j < response[i].length; j++) {

//                         var sub_area_id = response[i][j]['sub_area_id'];
//                         var sub_area_name = response[i][j]['sub_area_name'];
//                         var selected = 'selected';
//                         // if (sub_area_upd != '' && values.includes(sub_area_id.toString())) {
//                         //     selected = 'selected';
//                         // }

//                         subareaItems.push({
//                             value: sub_area_id,
//                             label: sub_area_name,
//                             selected: selected
//                         });

//                     }

//                 }
        
//                 // Sort the area items alphabetically by label
//                 subareaItems.sort(function (a, b) {
//                     return a.label.localeCompare(b.label);
//                 });
            
//                 // Merge "Select All" with sorted area items
//                 items = items.concat(subareaItems);
            
//                 intance2.setChoices(items, 'value', 'label', true);

//                 resolve(); // Notify completion
//             },
//             error: function (xhr, status, error) {
//                 reject(error); // Handle errors
//             }
//         });
//     });
// }

//Get BranchDropdown Based on Company id
function getBranchDropdown() {
    var branch_id_upd = $('#branch_id_upd').val();
    var company_id = (!$('#company_id_upd').val()) ? $('#company_id').val() : $('#company_id_upd').val();
    $.ajax({
        url: 'areaMapping/getBranchDropdown.php',
        type: 'post',
        dataType: 'json',
        data: { 'company_id': company_id },
        cache: false,
        success: function (response) {
            $('#branch').empty();
            $('#branch').append('<option>Select Branch</option>');
            for (var i = 0; i < response.length; i++) {
                var selected = '';
                if (branch_id_upd != '' && branch_id_upd == response[i]['branch_id']) {
                    selected = "selected";
                }
                $('#branch').append("<option value ='" + response[i]['branch_id'] + "' " + selected + " > " + response[i]['branch_name'] + " </option>");
            }
            {//To Order Alphabetically
                var firstOption = $("#branch option:first-child");
                $("#branch").html($("#branch option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#branch").prepend(firstOption);
            }
        }
    })
}

//Get BranchDropdown Based on Company id
function getBranchDropdown1() {
    var branch_id_upd = $('#branch_id_upd1').val();
    var company_id = (!$('#company_id_upd1').val()) ? $('#company_id1').val() : $('#company_id_upd1').val();
    $.ajax({
        url: 'areaMapping/getBranchDropdown.php',
        type: 'post',
        dataType: 'json',
        data: { 'company_id': company_id },
        cache: false,
        success: function (response) {

            $('#branch1').empty();
            $('#branch1').append('<option>Select Branch</option>');
            for (var i = 0; i < response.length; i++) {
                var selected = '';
                if (branch_id_upd != '' && branch_id_upd == response[i]['branch_id']) {
                    selected = "selected";
                }
                $('#branch1').append("<option value = '" + response[i]['branch_id'] + "' " + selected + " > " + response[i]['branch_name'] + " </option>");
            }
            {//To Order Alphabetically
                var firstOption = $("#branch1 option:first-child");
                $("#branch1").html($("#branch1 option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#branch1").prepend(firstOption);
            }
        }
    })
}

//Get BranchDropdown Based on Company id
function getBranchDropdown2() {
    return new Promise((resolve, reject) => {
        var branch_id_upd = $('#branch_id_upd2').val();
        var company_id = (!$('#company_id_upd2').val()) ? $('#company_id2').val() : $('#company_id_upd2').val();
        $.ajax({
            url: 'areaMapping/getBranchDropdown.php',
            type: 'post',
            dataType: 'json',
            data: { 'company_id': company_id },
            cache: false,
            success: function (response) {

                $('#branch2').empty();
                $('#branch2').append('<option value="">Select Branch</option>');
                for (var i = 0; i < response.length; i++) {
                    var selected = '';
                    if (branch_id_upd != '' && branch_id_upd == response[i]['branch_id']) {
                        selected = "selected";
                    }
                    $('#branch2').append("<option value = '" + response[i]['branch_id'] + "' " + selected + " > " + response[i]['branch_name'] + " </option>");
                }
                {//To Order Alphabetically
                    var firstOption = $("#branch2 option:first-child");
                    $("#branch2").html($("#branch2 option:not(:first-child)").sort(function (a, b) {
                        return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                    }));
                    $("#branch2").prepend(firstOption);
                }

                resolve(); // Notify completion
            },
            error: function (xhr, status, error) {
                reject(error); // Handle errors
            }
        });
    });
}

function getLineNameDropdown(branchid){
    return new Promise((resolve, reject) => {
        $.post('areaMapping/getLineName.php',{branchid}, function(response){
            dueLine.clearStore();
            let line = $('#due_line_name').val().split(',');
            $.each(response, function(index, val){
                let selected ='';
                if (line != '' && line.includes(val.map_id.toString())) {
                    selected = 'selected';
                }
                let item = [
                    {
                        value: val.map_id,
                        label: val.line_name,
                        selected: selected,
                    }
                ];
                dueLine.setChoices(item);
                dueLine.init();
            });

            resolve(); // Resolve once dropdown is populated
        }, 'json').fail(function(xhr, status, error) {
            reject(error); // Reject if there's an error
        });
    });
}

function getCusLoanCount(){
    const areaid = areaMultiselect2.getValue()
        .map(item => item.value)
        .filter(val => val !== 'select_all') // exclude 'select_all' from final string
        .sort((a, b) => a - b)
        .join(',');

    var loanCatId = $('#loan_cat1').val();

    const subStatus = subStatusMultiselect.getValue()
        .map(item => item.value)
        .sort((a, b) => a.localeCompare(b))
        .join(',');

        const lineList = dueLine.getValue();
        const mapId = lineList
            .map(item => item.value)
            .sort((a, b) => a - b)
            .join(',');

    $.post('areaMapping/getCusAndLoanCount.php',{areaid, loanCatId, subStatus, mapId}, function(response){
        let cusCnt = (response.cus_count) ? response.cus_count : 0;
        let loanCnt = (response.loan_count) ? response.loan_count : 0;
        $('#cus_count').val(cusCnt);
        $('#loan_count').val(loanCnt);
    },'json');
}

function getSortedCommaSeparatedValues(multiselectInstance) {
    const selected = multiselectInstance.getValue();
    const values = selected.map(item => parseInt(item.value, 10));
    values.sort((a, b) => a - b);
    return values.join(',');
}

//get Loan category Dropdown
function getLoanCatDropdown() {
    return new Promise((resolve, reject) => {
        var loan_cat_upd = $('#loan_cat_upd').val().split(',');
        $.ajax({
            url: 'manageUser/getLoanCatDropdown.php',
            data: {},
            dataType: 'json',
            type: 'post',
            cache: false,
            success: function (response) {
                $('#loan_cat1').empty();
                $('#loan_cat1').append('<option value="">Select Loan category</option>');
                for (var i = 0; i < response.length; i++) {
                    var selected = '';
                    if (loan_cat_upd != '' && loan_cat_upd == response[i]['loan_cat_id']) {
                        selected = "selected";
                    }
                    $('#loan_cat1').append("<option value = '" + response[i]['loan_cat_id'] + "' " + selected + " > " + response[i]['loan_cat_name'] + " </option>");
                }
                {//To Order Alphabetically
                    var firstOption = $("#loan_cat1 option:first-child");
                    $("#loan_cat1").html($("#loan_cat1 option:not(:first-child)").sort(function (a, b) {
                        return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                    }));
                    $("#loan_cat1").prepend(firstOption);
                }

                resolve();
            },
            error: function (xhr, status, error) {
                reject(error); 
            }
        });
    });
}

function selectAllAreas(selectedList, choicesInstance) {
    const selectedValues = selectedList.map(item => item.value);

    if (selectedValues.includes('select_all')) {
        const allChoices = choicesInstance._store.choices
            .filter(choice => choice.value !== 'select_all' && !choice.disabled);

        const allValues = allChoices.map(choice => choice.value);

        const alreadySelectedAll = allValues.every(val => selectedValues.includes(val));

        // Remove current selections
        choicesInstance.removeActiveItems();

        if (!alreadySelectedAll) {
            // Select all except 'select_all'
            choicesInstance.setChoiceByValue(allValues);
        }
    }
}

function getSubStsMapping() {
    return new Promise((resolve, reject) => {
        let subStatus = ['Legal', 'Error', 'OD', 'Pending', 'Current'];
        let editSubStatus = $('#cus_sts').val() || '';

        subStatusMultiselect.clearStore();

        let items = subStatus.map(val => ({
            value: val,
            label: val,
            selected: editSubStatus.includes(val)
        }));

        subStatusMultiselect.setChoices(items);
        subStatusMultiselect.init();

        resolve(); // Mark as done
    });
}

function getSubStatusValues(){
    const subStatusArr = subStatusMultiselect.getValue();
    var subStsStr = subStatusArr
        .map(item => item.value)
        .sort((a, b) => a.localeCompare(b))
        .join(',');
    $('#customer_status').val(subStsStr);
}