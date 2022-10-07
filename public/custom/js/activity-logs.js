$(document).ready(function(){
    let module = 'activity_logs';

    let table_main = $('.table-main').DataTable( {
        dom: 'lBfrtip',
        paging:  true,
        buttons: [
            'colvis'
        ],
        search: {
            return: true
        },
        columns: [
            { "data": "index" },
            { "data": "image" },
            { "data": "name" },
            { "data": "role" },
            { "data": "module" },
            { "data": "action" },
            { "data": "datetime" },
        ]
    });

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

        loadTable(table_main, response.data.logs);

        $('.year').text(response.data.year);
        $('[data-toggle="tooltip"]').tooltip()
    }
});