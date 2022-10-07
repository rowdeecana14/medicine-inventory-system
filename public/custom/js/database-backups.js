$(document).ready(function(){
    let module = 'database_backups';
    let module_label  = 'Database Backup'

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
            { "data": "file_name" },
            { "data": "file_size" },
            { "data": "created_at" },
            { "data": "created_by" },
            { "data": "action" },
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

        loadTable(table_main, response.data);

        $('.year').text(response.data.year);
        $('[data-toggle="tooltip"]').tooltip()
    }

    $(document).on('click', '.btn-backup', async function() {
        openLoaderModal();

        let payload = {
            module: module,
            action: 'backup',
            csrf_token: app_csrf_token,
        };
        let response = await Api.store(payload);
        
        closeLoaderModal();

        if(response.success) {
            saveMessage(`${module_label} successfully saved.`);
            preloadTable();
        }
        else {
            serverError();
        }
    });

    $(document).on('click', '.btn-delete', async function()  {
        let confirm = await deleteConfirmation();

        if(confirm) {
            openLoaderModal();

            let payload = {
                module: module,
                action: 'remove',
                csrf_token: app_csrf_token,
                id:  Number($(this).data('id')),
                file_name: $(this).data('file'),
            };
    
            let response = await Api.remove(payload);

            closeLoaderModal();

            if(response.success) {
                deleteMessage(`${module_label} successfully deleted.`);
                preloadTable();
            }
            else {
                serverError();
            }
        }
    });

    $(document).on('click', '.btn-download', async function()  {
        openLoaderModal();

        let payload = {
            module: module,
            action: 'download',
            csrf_token: app_csrf_token,
            id:  Number($(this).data('id')),
            file_name: $(this).data('file'),
        };
        let response = await Api.store(payload);
        
        closeLoaderModal();

        if(response.success && response.data.success) {
            let path = response.data.data.path;
            let link = document.createElement('a');

            link.href = path;
            link.setAttribute('download', $(this).data('file'));
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            saveMessage(`${module_label} file is downloading.`);
        }
        else {
            serverError();
        }
        
    });

});