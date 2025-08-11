const docMultiselect = new Choices('#doc_creation1', {
    removeItemButton: true,
    noChoicesText: 'Select Documentation',
    shouldSort: false,//remove default asc sorting
    allowHTML: true
})

// Document is ready
$(document).ready(function () {

    {//To Order loan_category Alphabetically
        var firstOption = $("#loan_category option:first-child");
        $("#loan_category").html($("#loan_category option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#loan_category").prepend(firstOption);
    }

    $('#doc_creation1').change(function () {
        var doc_creation1 = docMultiselect.getValue();
        var doc_creation = '';
        for (var i = 0; i < doc_creation1.length; i++) {
            if (i > 0) {
                doc_creation += ',';
            }
            doc_creation += doc_creation1[i].value;
        }
        var arr = doc_creation.split(",");
        arr.sort(function (a, b) { return a - b });
        var sortedStr = arr.join(",");

        $('#doc_creation').val(sortedStr);
    })

    $('#submit_doc_mapping').click(function () {
        //Validations
        var loan_category = $('#loan_category').val(); var doc_creation = docMultiselect.getValue();
        if (loan_category === '' || doc_creation.length == 0) {
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
    })



});// Document ready end

$(function () {
    getDocumentCreationDropdown();
})



function getDocumentCreationDropdown() {
    var doc_creation_upd = $('#doc_creation_upd').val().split(',');
    if (doc_creation_upd != '') {
        for (var i = 0; i < doc_creation_upd.length; i++) {
            if (doc_creation_upd[i] == '1') { var select1 = 'selected'; } else
                if (doc_creation_upd[i] == '2') { var select2 = 'selected'; } else
                    if (doc_creation_upd[i] == '3') { var select3 = 'selected'; } else
                        if (doc_creation_upd[i] == '4') { var select4 = 'selected'; } else
                            if (doc_creation_upd[i] == '5') { var select5 = 'selected'; } else
                                if (doc_creation_upd[i] == '6') { var select6 = 'selected'; }
        }
    }

    var items = [
        {
            value: '1',
            label: 'Sign Documents',
            selected: select1
        },
        {
            value: '2',
            label: 'Cheque Details',
            selected: select2
        },
        {
            value: '3',
            label: 'Mortage',
            selected: select3
        },
        {
            value: '4',
            label: 'Endorsement',
            selected: select4
        },
        {
            value: '5',
            label: 'Gold',
            selected: select5
        },
        {
            value: '6',
            label: 'Documents',
            selected: select6
        },
    ]
    docMultiselect.clearStore();
    docMultiselect.setChoices(items);
    docMultiselect.init();
}