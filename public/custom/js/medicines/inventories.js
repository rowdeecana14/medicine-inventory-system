
$(document).ready(function(){
    fields = [ 'image_profile', 'name', 'description', 'expired_at', 'status', 'category_id', 'type_id'];
    profile_fields =  [ 'image_profile', 'names', 'description', 'expired_at',  'status', 'category', 'type', 'created_at', 'updated_at', 'updated_by', 'created_by'];
    let id = null;
    let module_label = 'Inventory Lists';
    let module = 'inventory';
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
            {"className": "text-center", "targets": [ 4, 5, 6, 7]}
        ],
        columns: [
            { "data": "index" },
            { "data": "image" },
            { "data": "medicine" },
            { "data": "description" },
            { "data": "stockin" },
            { "data": "stockout" },
            { "data": "expired" },
            { "data": "available" },
            { "data": "level" },
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

    $('.table-main tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table_main.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
});