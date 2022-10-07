$(document).ready(function(){
    let module = 'reports';

    let table_main = $('.table-main').DataTable( {
        dom: 'lBfrtip',
        paging:  true,
        buttons: [
            {
                extend: 'colvis',
                text:  '<i class="fa fa-eye pr-1"></i> Visibility',
            },
            {
                text:      '<i class="fa fa-history pr-1"> </i> Refresh',
                action: function ( e, dt, node, config ) {
                    table_main.search('').draw();
                    preloadTable();
                }
            },
            {
                extend:    'copyHtml5',
                text:      '<i class="fa fa-copy pr-1"></i> Copy',
                titleAttr: 'Copy'
            },
            {
                title: 'MEDICINE EXPIRATION REPORTS',
                extend:    'excelHtml5',
                text:      '<i class="fa fa-file-excel pr-1"></i> Excel',
                titleAttr: 'Excel',
            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print pr-1"> </i> Print',
                action: function ( e, dt, node, config ) {
                    loadPrintWindow($('.table-main'), 'MEDICINE EXPIRATION REPORTS', auth_user);
                }
            },
        ],
        search: {
            return: true
        },
        lengthMenu: [10, 25, 50, 100, 500, 1000],
        columnDefs: [
            { className: "text-center", targets: [0, 2, 7 ]},
            { targets: [3, 4, 5], visible: false }
        ],
        columns: [
            { "data": "index" },
            { "data": "name" },
            { "data": "quantity" },
            { "data": "description" },
            { "data": "category_id" },
            { "data": "type_id" },
            { "data": "expired_at" },
            { "data": "days" },
            { "data": "status" },
        ]
    });

    preloadTable();
    loadUserDetails();

    async function preloadTable() {
        runLoader($('.table-main'));

        let payload = {
            module: module,
            action: 'se-all',
            csrf_token: app_csrf_token,
        };

        let response = await Api.all(payload);

        if(!response.success) {
            serverError();
        }

        loadTable(table_main, response.data);
        
        $('.table-main').waitMe("hide");
        $('[data-toggle="tooltip"]').tooltip()
    }

    $(document).on('click', '.btn-filter', function() {
        $('#filter-modal').modal('show');
        $('#filter-modal #status').select2({
            theme: "bootstrap",
        });
        initTableSelect($('#filter-modal .select2-list'));

    });

    $(document).on('submit', '#filter-form', async function(e) {
        e.preventDefault();
        runLoader($('#filter-modal .modal-content'));

        let payload = {
            module: module,
            action: 'se-filter',
            csrf_token: app_csrf_token,
            category_id: $('#category_id').val(),
            type_id: $('#type_id').val(),
            medicine_id: $('#medicine_id').val(),
            expired_at: $('#expired_at').val(),
            status: $('#status').val(),
        };

        let response = await Api.all(payload);

        if(!response.success) {
            $('#filter-modal .modal-content').waitMe("hide");
            serverError();
        }
        
        loadTable(table_main, response.data);
        $('#filter-modal .modal-content').waitMe("hide");
    });
});