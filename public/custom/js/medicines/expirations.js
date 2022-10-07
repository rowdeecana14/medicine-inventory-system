
$(document).ready(function(){
    fields = [ 'quantity', 'expired_at'];
    profile_fields =  [ 
        'image_profile', 'names', 'description', 'expired_at',  'status', 'category', 'type', 'available', 'received',
        'dispenced', 'expired', 'created_at', 'updated_at', 'updated_by', 'created_by'
    ];
    let id = null;
    let expiration_id = null;
    let available = 0;
    let module_label = 'Expiration Informations';
    let module = 'expirations';
    let default_image = "../../public/assets/img/config/medicine.jpg";
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
            {"className": "text-center", "targets": [ 4, 5]}
        ],
        columns: [
            { "data": "index" },
            { "data": "image" },
            { "data": "medicine" },
            { "data": "description" },
            { "data": "expired" },
            { "data": "available" },
            { "data": "action" },
        ]
    });

    let table_expiries = $('.table-expiries').DataTable( {
        dom: 'lBfrtip',
        paging:  true,
        buttons: [
            'colvis'
        ],
        search: {
            return: true
        },
        columnDefs: [
            {"className": "text-center", "targets": [ 1]}
        ],
        columns: [
            { "data": "index" },
            { "data": "quantity" },
            { "data": "expired_at" },
            { "data": "days" },
        ]
    });

    let table_edit_expiries = $('.table-edit-expiries').DataTable( {
        dom: 'lBfrtip',
        paging:  true,
        buttons: [
            'colvis'
        ],
        search: {
            return: true
        },
        columnDefs: [
            {"className": "text-center", "targets": [ 1]}
        ],
        columns: [
            { "data": "index" },
            { "data": "quantity" },
            { "data": "expired_at" },
            { "data": "days" },
            { "data": "action" },
        ]
    });

    table_main.on( 'order.dt search.dt', function () {
        table_main.column(0, {}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    table_expiries.on( 'order.dt search.dt', function () {
        table_expiries.column(0, {}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    table_edit_expiries.on( 'order.dt search.dt', function () {
        table_edit_expiries.column(0, {}).nodes().each( function (cell, i) {
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

    async function preloadExpiration() {
        let payload = {
            module: module,
            action: 'show',
            csrf_token: app_csrf_token,
            id: id,
        };
        let response = await Api.show(payload);

        if(response.success) {
            available =  response?.data?.medicine?.available || 0;
            loadTable(table_edit_expiries, response?.data?.expiries || []);
            showModalProfile($('#edit-modal'), response?.data?.medicine || {});
        }
        else {
            serverError();
        }
    }

    $('.table-main tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table_main.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
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
            showModalProfile($('#show-modal'), response?.data?.medicine || {});
            loadTable(table_expiries, response?.data?.expiries || []);
        }
        else {
            serverError();
        }

        $('[data-toggle="tooltip"]').tooltip()
        $('#show-modal .modal-content').waitMe("hide");
    });

    $(document).on('click', '.btn-edit', async function() {
        $('#edit-modal').modal('show');
        $(".preview-image").attr('src', default_image);
        runLoader($('#edit-modal .modal-content'));
        
        id =  Number($(this).data('id'));
        let payload = {
            module: module,
            action: 'show',
            csrf_token: app_csrf_token,
            id: id,
        };
        let response = await Api.show(payload);

        if(response.success) {
            available =  response?.data?.medicine?.available || 0;
            showModalProfile($('#edit-modal'), response?.data?.medicine || {});
            loadTable(table_edit_expiries, response?.data?.expiries || []);
        }
        else {
            serverError();
        }
        $('[data-toggle="tooltip"]').tooltip()
        $('#edit-modal .modal-content').waitMe("hide");
    });

    $(document).on('click', '.btn-create', async function() {
        $('#create-expiration-modal').modal('show');
        $('#create-expiration-modal #quantity').attr({ 'max': available, 'placeholder': `Available stock is ${available}` });
    });

    $(document).on('click', '.btn-create-save', async function() {
        if(!validateFormWithError($('#create-form'))) {
            return errorMessage(`Fill out all required fields.`);
        }

        runLoader($('#create-expiration-modal .modal-content'));

        let form = new FormData(document.querySelector('#create-form'));
        form.append('id',  id);
        form.append('module',  module);
        form.append('action', 'store-expiration');
        form.append('csrf_token', app_csrf_token);

        let response = await Api.store(serialize(form))

        if(!response.success) {
            $('#create-expiration-modal .modal-content').waitMe("hide");
            return serverError();
        }

        if(!response.data.success) {
            $('#create-expiration-modal .modal-content').waitMe("hide");
            return errorMessage(response.data.message);
        }

        formReset($('#create-form'));
        saveMessage(`${module_label} successfully saved.`);
        preloadExpiration();
        
        $('#create-expiration-modal .modal-content').waitMe("hide");
        $('#create-expiration-modal').modal('hide');
    });

    $(document).on('click', '.btn-edit-expiration', async function() {
        $('#edit-expiration-modal').modal('show');
        runLoader($('#edit-expiration-modal .modal-content'));
        
        expiration_id =  Number($(this).data('id'));
        
        let payload = {
            module: module,
            action: 'show-expiration',
            csrf_token: app_csrf_token,
            id: expiration_id,
        };
        let response = await Api.show(payload);

        if(response.success) {
            initSelect($('#edit-expiration-modal .select2-list'));
            showModalDetails($('#edit-expiration-modal'), response.data);
        }
        else {
            serverError();
        }

        $('#edit-expiration-modal #quantity').attr({ 'max': available + $('#edit-expiration-modal #quantity').val(), 'placeholder': `Available stock is ${available}`});
        $('[data-toggle="tooltip"]').tooltip()
        $('#edit-expiration-modal .modal-content').waitMe("hide");
    });

    $(document).on('click', '.btn-edit-save', async function() {
        if(!validateFormWithError($('#edit-form'))) {
            return errorMessage(`Fill out all required fields.`);
        }

        runLoader($('#edit-expiration-modal .modal-content'));

        let form = new FormData(document.querySelector('#edit-form'));
        form.append('id',  expiration_id);
        form.append('medicine_id',  id);
        form.append('module',  module);
        form.append('action', 'update-expiration');
        form.append('csrf_token', app_csrf_token);

        let response = await Api.store(serialize(form))

        if(!response.success) {
            $('#edit-expiration-modal .modal-content').waitMe("hide");
            return serverError();
        }

        if(!response.data.success) {
            $('#edit-expiration-modal .modal-content').waitMe("hide");
            return errorMessage(response.data.message);
        }

        formReset($('#edit-form'));
        updateMessage(`${module_label} successfully updated.`);
        preloadTable();
        preloadExpiration();
        
        $('#edit-expiration-modal .modal-content').waitMe("hide");
        $('#edit-expiration-modal').modal('hide');
    });

    $(document).on('click', '.btn-delete-expiration', async function()  {
        let confirm = await deleteConfirmation();

        if(confirm) {
            openLoaderModal();

            let payload = {
                module: module,
                action: 'remove-expiration',
                csrf_token: app_csrf_token,
                id:  Number($(this).data('id')),
            };
    
            let response = await Api.remove(payload);

            if(response.success) {
                deleteMessage(`${module_label} successfully deleted.`);
                preloadTable();
                preloadExpiration();
            }
            else {
                serverError();
            }

            closeLoaderModal();
        }
    });

});