
$(document).ready(function(){
    fields = [ 
        'id', 'image_profile', 'first_name', 'middle_name', 'last_name', 'position_id', 'gender_id', 'contact_no', 'license_no',
        'civil_status_id', 'purok_id', 'baranggay_id', 'status', 'email', 'birth_date', 'suffix', 'street_building_house'
    ];
    profile_fields = [
        'image_profile', 'fullname', 'position', 'gender', 'contact_no', 'email', 'birth_date', 'age', 'license_no',
        'civil_status', 'address', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at',
    ];
    let id = null;
    let module_label = 'Health Official Informations';
    let module = 'health_officials';
    let default_image = "../../public/assets/img/config/female.png";
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
            { "data": "position" },
            { "data": "gender" },
            { "data": "contact_no" },
            { "data": "status" },
            { "data": "action" },
        ]
    });
    let table_transactions = $('.table-transactions').DataTable( {
        dom: 'lBfrtip',
        paging:  true,
        buttons: [
            'colvis'
        ],
        search: {
            return: true
        },
        columnDefs: [
            { className: "text-center", targets: [ 2]},
        ],
        columns: [
            { "data": "index" },
            { "data": "transaction_no" },
            { "data": "items" },
            { "data": "person" },
            { "data": "date" },
            { "data": "type" },
            { "data": "action" },
        ]
    });
    let table_transaction_medicines = $('.table-transaction-medicines').DataTable( {
        dom: 'lBfrtip',
        paging:  true,
        buttons: [
            'colvis'
        ],
        search: {
            return: true
        },
        columnDefs: [
            { className: "text-center", targets: [ 4]},
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

    table_transactions.on( 'order.dt search.dt', function () {
        table_transactions.column(0, {}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    table_transaction_medicines.on( 'order.dt search.dt', function () {
        table_transaction_medicines.column(0, {}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    preloadTable();
    loadUserDetails();

    Webcam.set({
        height: 250,
        image_format: 'jpeg',
        jpeg_quality: 90
    });

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

    function loadWebcam() {
        Webcam.set({
            width: 320,
            height: 240,
            dest_width: 640,
            dest_height: 480,
            image_format: 'jpeg',
            jpeg_quality: 90,
            force_flash: false
        });
        Webcam.attach('.camera');
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

    $('.image-gallery').magnificPopup({
        delegate: 'a', 
        type: 'image',
        removalDelay: 300,
        gallery:{
            enabled:true,
        },
        mainClass: 'mfp-with-zoom', 
        zoom: {
            enabled: true, 
            duration: 300,
            easing: 'ease-in-out',
            opener: function(openerElement) {
                return openerElement.is('img') ? openerElement : openerElement.find('img');
            }
        }
    });

    $('.create-upload-image').on('change', function(event) {
        base64Image($(this)).done(function (base64) { 
            $('#create-modal .preview-image').attr('src', base64);
            $('.image_href').attr("href", base64);
            $(".image_to_upload").val(base64);
        });
    });

    $('.edit-upload-image').on('change', function(event) {
        base64Image($(this)).done(function (base64) { 
            $('#edit-modal .preview-image').attr('src', base64);
            $('.image_href').attr("href", base64);
            $(".image_to_upload").val(base64);
        });
    });

    $('.btn-open-camera').on('click', function() {
        $('#camera-modal').modal('show');
        runLoader($('#camera-modal .modal-content'));

        setTimeout(function() {
            Webcam.reset();
            loadWebcam();
            $(".preview-image-captured").hide();
            $('#camera-modal .modal-content').waitMe("hide");
        }, 1000)
    });

    $('.btn-close-modal').on('click', function() {
        $('#camera-modal').modal('hide');
        $(".camera").show();
        Webcam.reset();
    });

    $('.btn-camera-reset ').on('click', function() {
        runLoader($('#camera-modal .modal-content'));
        $(".camera").show();
        $(".preview-image-captured").hide();

        Webcam.reset();
        loadWebcam();

        setTimeout(function() {
            $('#camera-modal .modal-content').waitMe("hide");
        }, 500)
    });

    $('.btn-camera-capture').on('click', function() {
        if($('video').length >= 1) {
            var shutter = new Audio();
            shutter.autoplay = true;
            shutter.src = navigator.userAgent.match(/Firefox/) ? 'shutter.ogg' : '../../public/assets/js/plugin/webcamjs/shutter.mp3';
            shutter.play();

            Webcam.snap( function(data_uri) {
                $(".camera").hide();
                $(".preview-image-captured").show();
                $(".preview-image-captured").attr('src', data_uri);
                $(".preview-image").attr('src', data_uri);

                $('.image_href').attr("href", data_uri);
                $(".image_to_upload").val(data_uri);
            } );
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
            };
    
            let response = await Api.remove(payload);

            if(response.success) {
                deleteMessage(`${module_label} successfully deleted.`);
                preloadTable();
            }
            else {
                serverError();
            }

            closeLoaderModal();
        }
    });

    $(document).on('click', '.btn-create', async function() {
        $(".preview-image").attr('src', default_image);
        $('#create-modal').modal('show');
        initSelect($('#create-modal .select2-list'));
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
            initSelect($('#edit-modal .select2-list'));
            showModalDetails($('#edit-modal'), response.data);
        }
        else {
            serverError();
        }

        $('#edit-modal .modal-content').waitMe("hide");
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
            showModalProfile($('#show-modal'), response?.data?.health_official || {});
            loadTable(table_transactions, response?.data?.transactions || []);
        }
        else {
            serverError();
        }

        $('[data-toggle="tooltip"]').tooltip()
        $('#show-modal .modal-content').waitMe("hide");
    });

    $(document).on('click', '.btn-show-modal-medicines', async function() {

        $('#show-modal-medicines').modal('show');
        runLoader($('#show-modal-medicines .modal-content'));

        let id =  Number($(this).data('id'));
        let type =  $(this).data('type');

        let payload = {
            module: module,
            action: type === 'stockin' ? 'stockin-transaction' : 'stockout-transaction',
            csrf_token: app_csrf_token,
            id: id,
        };
        let response = await Api.show(payload);

        if(response.success) {
            loadTable(table_transaction_medicines, response?.data || []);
        }
        else {
            serverError();
        }

        $('[data-toggle="tooltip"]').tooltip()
        $('#show-modal-medicines .modal-content').waitMe("hide");
    });

    $(document).on('click', '.btn-create-save', async function() {
        if(!validateForm($('#create-form'))) {
            
            return errorMessage(`Fill out all required fields.`);
        }

        let confirm = await confirmationModal(`You want to save this new ${module_label.toLowerCase()}? `,  'No, cancel it!', 'Yes, save it!');

        if(!confirm) {
            return;
        }

        runLoader($('#create-modal .modal-content'));

        let form = new FormData(document.querySelector('#create-form'));
        form.append('module',  module);
        form.append('action', 'store');
        form.append('csrf_token', app_csrf_token);

        let response = await Api.store(serialize(form))

        if(response.success) {
            if(response.data.success) {
                formReset($('#create-form'));
                saveMessage(`${module_label} successfully saved.`);
                preloadTable();
            }
            else {
                errorMessageModal(response.data.message);
            }
        }
        else {
            serverError();
        }
        
        $('#create-modal .modal-content').waitMe("hide");
        $('#create-modal').modal('hide');
    });

    $(document).on('click', '.btn-edit-save', async function() {

        if(!validateForm($('#edit-form'))) {
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

        if(response.success) {
            if(response.data.success) {
                formReset($('#edit-form'));
                updateMessage(`${module_label} successfully updated.`);
                preloadTable();
            }
            else {
                errorMessageModal(response.data.message);
            }
        }
        else {
            serverError();
        }

        $('#edit-modal .modal-content').waitMe("hide");
        $('#edit-modal').modal('hide');
    });
});