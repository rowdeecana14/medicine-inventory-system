
$(document).ready(function(){
    let id = null;
    let module_label = 'Dispencing Transactions';
    let module = 'stockout';
    profile_fields =  [ 'image_profile', 'health_official', 'patient', 'dispenced_at', 'remarks' ];

    let table_main = $('.table-main').DataTable( {
        dom: 'lBfrtip',
        paging:  true,
        buttons: [
            'colvis'
        ],
        search: {
            return: true
        },
        columnDefs: [
            { className: "text-center", targets: [ 2 ]},
        ],
        columns: [
            { "data": "index" },
            { "data": "transaction_no" },
            { "data": "total" },
            { "data": "health_official" },
            { "data": "patient" },
            { "data": "dispenced_at" },
            { "data": "action" },
        ]
    });

    let table_show_medicine = $('.table-show-medicine').DataTable( {
        dom: 'lBfrtip',
        paging:  true,
        buttons: [
            'colvis'
        ],
        search: {
            return: true
        },
        columnDefs: [
            { className: "text-center", targets: [ 4 ]},
        ],
        columns: [
            { "data": "index" },
            { "data": "image" },
            { "data": "name" },
            { "data": "description" },
            { "data": "quantity" },
        ]
    });

    table_main.on( 'order.dt search.dt', function () {
        table_main.column(0, {}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    loadUserDetails();
    preloadTable();

    async function preloadTable() {
        let payload = {
            module: module,
            action: 'all',
            csrf_token: app_csrf_token,
        };

        let response = await Api.all(payload);

        if(!response.success) {
            serverError();
        }
        
        loadTable(table_main, response.data);
        $('[data-toggle="tooltip"]').tooltip()
    }

    function reIndexMedicineTable() {
        $('.table-medicine tbody tr').each(function(index){
            $(this).find('td:first').text(index + 1);
            $(this).find('.medicine_id').attr('name','medicine_id['+index+']');
            $(this).find('.quantity').attr('name','quantity['+index+']');
            $(this).find('.dosage').attr('name','dosage['+index+']');
        });
    }

    function validateTableValues(table) {
        let count_empty_fields = 0;
    
        $(`.${table} tbody tr`).each(function(index){
    
            if (checkEmptyField($(this).find('.medicine_id'))) {
                count_empty_fields += 1;
                return;
            }
    
            if (checkEmptyField($(this).find('.quantity'))) {
                count_empty_fields += 1;
                return;
            }

            // if (checkEmptyField($(this).find('.dosage'))) {
            //     count_empty_fields += 1;
            //     return;
            // }
        });
    
        if (count_empty_fields > 0) {
            errorMessage('Empty field, input value first.');
        }
    
        return count_empty_fields === 0;
    }
    
    function checkEmptyField(element) {
    
        if ($(element).val() == "" || $(element).val() == null) {
            if ($(element).prop('tagName') == 'SELECT') {
                $(element).select2('focus');
            } else {
                $(element).focus();
            }
    
            return true;
        }
    
        return false;
    }

    function getMedicineData() {
        let medicines = [];

        $('.table-medicine tbody tr').each(function(index){
            medicines.push({
                'medicine_id': $(this).find('.medicine_id').val(),
                'quantity': $(this).find('.quantity').val(),
                'dosage': $(this).find('.dosage').val(),
            });
        });

        return medicines;
    }

    $(document).on('click', ".btn-add-medicine", function() {

        if (!validateTableValues('table-medicine')) {
            return;
        }

        $(this).attr('disabled', true);
        let cloned = $('.table-medicine tbody tr:first').clone();

        cloned.find('.btn-add-medicine').addClass('d-none');
        cloned.find('input').val('').removeClass('required');
        cloned.find('.btn-remove-medicine').removeClass('d-none');
        cloned.find('label.error').remove();

        $('.table-medicine tbody tr:last').after(cloned);
        $('[data-toggle="tooltip"]').tooltip({ container: 'body' });

        // $('.table-medicine tbody span.select2').remove();

        $('.table-medicine tbody .select2-container').remove();
        $('.table-medicine tbody select.select2').removeAttr('data-select2-id');

        initTableSelect($('.table-medicine tbody .select2-list'));
        reIndexMedicineTable();

        $(this).attr('disabled', false);
    });

    $(document).on('click', '.btn-remove-medicine', function(){
        $(this).parent().closest('tr').remove();
        $('.tooltip').remove();
        reIndexMedicineTable();
    });

    $(document).on('click', '.btn-create', async function() {
        $('#create-modal').modal('show');
        $('.current-date').val(moment().format("MM/DD/YYYY")).trigger('change');
        initTableSelect($('#create-modal .select2-list'));
    });


    $(document).on('select2:select', '.medicine_id', function() {
        $('[data-toggle="tooltip"]').tooltip();
        let current_selected = $(this).val();
        // GET ALL SELECTED FIELD
        let lists = $('.medicine_id option:selected').map(function() {
            if ($(this).val() == current_selected) {
                return $(this).val();
            }
        }).get();

        // CHECK TOTAL EXISTING OF SELECTED FIELD
        if (lists.length > 1) {

            $(this).val("").trigger("change");
            errorMessage('Duplicate name, select other medicine name.');
            return false;
        }

        console.log($(this).html())
    });

    $(document).on('click', '.btn-save', async function() {

        if(!validateForm($('#create-form'))) {
            return errorMessage(`Fill out all required fields.`);
        }

        if (!validateTableValues('table-medicine')) {
            return;
        }

        let confirm = await confirmationModal(`You want to save this new ${module_label.toLowerCase()}? `,  'No, cancel it!', 'Yes, save it!');

        if(!confirm) {
            return;
        }

        runLoader($('#create-modal .modal-content'));

        let form = {
            "module": module,
            "action": "store",
            "csrf_token": app_csrf_token,
            "health_official_id":  $('#health_official_id').val(),
            "patient_id": $('#patient_id').val(),
            "dispenced_at": $('#dispenced_at').val(),
            "remarks": $('#remarks').val(),
            "medicines":  getMedicineData()
        };

        let response = await Api.store(form);

        if(!response.success) {
            $('#create-modal .modal-content').waitMe("hide");
            return serverError();
        }

        if(!response.data.success) {
            $('#create-modal .modal-content').waitMe("hide");

            let message = '';

            for(let count = 0; count < response.data.errors.length; count++) {
                let error = response.data.errors[count];

                message += `
                    
                    <div class="alert alert-danger" role="alert">
                        The availabe stocks for this ${error.medicine} is ${error.available}.
                    </div>
                `
            }

            return errorMessageModal(message);
        }

        formReset($('#create-form'));
        saveMessage(`${module_label} successfully saved.`);
        preloadTable();
        
        $('#create-modal .modal-content').waitMe("hide");
        $('#create-modal').modal('hide');
    });

    $(document).on('click', '.btn-show', async function() {
        $('#show-modal').modal('show');
        runLoader($('#show-modal .modal-content'));

        id =  Number($(this).data('id'));
        let payload = {
            module: module,
            action: 'profile',
            csrf_token: app_csrf_token,
            id: id,
        };
        let response = await Api.show(payload);

        if(response.success) {
            showModalProfile($('#show-modal'), response?.data?.profile || {});
            loadTable(table_show_medicine, response?.data?.medicines || []);
        }
        else {
            serverError();
        }

        $('#show-modal .modal-content').waitMe("hide");
    });
});