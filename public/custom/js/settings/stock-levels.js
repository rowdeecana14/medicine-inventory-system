
$(document).ready(function(){
    fields = [ 'quantity', 'level'];
    let id = null;
    let module = 'stock_levels';
    let module_label = 'Stock Level';
    let table = $('.table').DataTable( {
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
            { "data": "level" },
            { "data": "updated_by" },
            { "data": "updated_at" },
            { "data": "action" },
        ]
    });

    table.on( 'order.dt search.dt', function () {
        table.column(0, {}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    preloadTable();
    loadUserDetails();

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

        loadTable(table, response.data);
        $('[data-toggle="tooltip"]').tooltip()
    }

    $('.table tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
   
    $(document).on('click', '.btn-edit', async function() {
        $('#edit-modal').modal('show');
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
            showModalDetails($('#edit-modal'), response.data);
        }
        else {
            serverError();
        }

        $('.label-qty').text( response?.data?.label || '');
        $('#edit-modal .modal-content').waitMe("hide");
    });

    $(document).on('submit', '#edit-form', async function(e) {
        e.preventDefault();
        
        if(!validateFormWithError($('#edit-form'))) {
            return errorMessage(`Fill out all required fields.`);
        }

        let confirm = await confirmationModal(`You want to save this update ${module_label.toLowerCase()}? `,  'No, cancel it!', 'Yes, save it!');

        if(!confirm) {
            return;
        }

        runLoader($('#edit-modal .modal-content'));

        let form = new FormData(document.querySelector('#edit-form'));
        form.append('module',  module);
        form.append('action', 'update');
        form.append('id', id);
        form.append('csrf_token', app_csrf_token);

        let response = await Api.update(serialize(form));

        if(!response.success) {
            $('#edi-modal .modal-content').waitMe("hide");
            return serverError();
        }

        if(!response.data.success) {
            $('#edit-modal .modal-content').waitMe("hide");
        }
            
        formReset($('#edit-form'));
        updateMessage(`${module_label} successfully updated.`);
        preloadTable();

        $('#edit-modal').modal('hide');
    });
});