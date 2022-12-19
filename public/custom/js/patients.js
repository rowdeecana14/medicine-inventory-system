
$(document).ready(function(){
    fields = [ 
        'image_profile', 'first_name', 'middle_name', 'last_name', 'national_id', 'philhealth_member', 'birth_date', 
        'email', 'contact_no',  'street_building_house', 'weight', 'height', 'status',
        'civil_status_id', 'person_disability_id', 'gender_id', 'position_id', 'citizenship_id',
        'baranggay_id', 'purok_id', 'blood_type_id', 'educational_attainment_id'
    ];
    profile_fields = [
        'image_profile', 'fullname', 'national_id', 'philhealth_member', 'birth_date', 'email', 'contact_no', 'address', 'weight', 'height',
        'status', 'created_at', 'updated_at', 'updated_by', 'created_by', 'age',
        'civil_status', 'person_disability', 'gender', 'position', 'citizenship', 'blood_type', 'educational_attainment'
    ];
    let id = null;
    let module_label = 'Patient Informations';
    let module = 'patients';
    let default_image = "../../public/assets/img/config/default.png";
    let action_type = '';
    let tab_list = {
        1: {
            "name": "basic_info",
            'validation': () => basicInfoValidation()
        },
        2: {
            "name": "other_info",
            'validation': () => otherInfoValidation()
        },
    };
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
            { "data": "national_id" },
            { "data": "age" },
            { "data": "gender" },
            { "data": "philhealth_member" },
            { "data": "pwd" },
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
            { "data": "receiver" },
            { "data": "remarks" },
            { "data": "dispenced_at" },
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
            { "data": "dosage" },
        ]
    });
    let table_medications = $('.table-medications').DataTable( {
        dom: 'lBfrtip',
        paging:  true,
        buttons: [
            'colvis'
        ],
        search: {
            return: true
        },
        columnDefs: [
            { className: "text-center", targets: [ 5]},
            { targets: [2, 7], visible: false }
        ],
        columns: [
            { "data": "index" },
            { "data": "image" },
            { "data": "transaction_no" },
            { "data": "name" },
            { "data": "description" },
            { "data": "quantity" },
            { "data": "dosage" },
            { "data": "dispencer" },
            { "data": "dispenced_at" },
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

    table_medications.on( 'order.dt search.dt', function () {
        table_medications.column(0, {}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    loadUserDetails();
    preloadTable();

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

    function getTabIndex(tab_name) {
        let index = null;

        Object.keys(tab_list).forEach(key => {
            const tab = tab_list[key];

            if(tab_name == tab.name) {
                index = key;
            }
        });

        return Number(index);
    }

    function tabsValidation(current_tab_index, selected_tab_index) {
        if(current_tab_index < selected_tab_index) {
           return tab_list[current_tab_index].validation();
        }

        return true;
    }

    function tabsNavigation(current_tab, selected_tab) {
        let length = Object.keys(tab_list).length;
        let selected_tab_index = getTabIndex(selected_tab);

        if(action_type == "create") {
            $('#create-modal .tablist .current').removeClass('current');
            $(`#create-modal .${selected_tab}`).addClass('current');
        }
        else {
            $('#edit-modal .tablist .current').removeClass('current');
            $(`#edit-modal .${selected_tab}`).addClass('current');
        }
    }

    function basicInfoValidation() {
        let form = action_type == "create" ? "#basic_info_form" : "#basic_info_form_edit";

        $(form).validate({
            ignore: 'hidden',
            errorPlacement: $.noop,
            highlight: function(element) {
               
                if($(element).is('select') ) {
                    let select2_selection = $(element).parent().find('.select2-selection');
                    let select2_selection_rendered = $(element).parent().find('.select2-selection__rendered');
    
                    $(select2_selection).attr("style", "border-color: #F25961 !important;");
                    $(select2_selection_rendered).attr("style", "border-color: #F25961 !important;");
                }
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                $(".error").remove("error");
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
    
                if($(element).is('select') ) {
                    let select2_selection = $(element).parent().find('.select2-selection');
                    let select2_selection_rendered = $(element).parent().find('.select2-selection__rendered');
    
                    $(select2_selection).attr("style", "border-color: #2f7e32 !important;");
                    $(select2_selection_rendered).attr("style", "border-color: #2f7e32 !important;");
                }
            },
           
            success: function(element) {
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                $(element).remove();
            },
        });

        if(!$(form).valid()) {
            $(form).validate().focusInvalid();
            return false;
        }

        return true;
    }

    function otherInfoValidation() {
        let form = action_type == "create" ? "#other_info_form" : "#other_info_form_edit";

        $(form).validate({
            ignore: 'hidden',
            errorPlacement: $.noop,
            highlight: function(element) {
               
                if($(element).is('select') ) {
                    let select2_selection = $(element).parent().find('.select2-selection');
                    let select2_selection_rendered = $(element).parent().find('.select2-selection__rendered');
    
                    $(select2_selection).attr("style", "border-color: #F25961 !important;");
                    $(select2_selection_rendered).attr("style", "border-color: #F25961 !important;");
                }
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                $(".error").remove("error");
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
    
                if($(element).is('select') ) {
                    let select2_selection = $(element).parent().find('.select2-selection');
                    let select2_selection_rendered = $(element).parent().find('.select2-selection__rendered');
    
                    $(select2_selection).attr("style", "border-color: #2f7e32 !important;");
                    $(select2_selection_rendered).attr("style", "border-color: #2f7e32 !important;");
                }
            },
           
            success: function(element) {
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                $(element).remove();
            },
        });

        if(!$(form).valid()) {
            $(form).validate().focusInvalid();
            return false;
        }

        return true;
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

    $('.table-lists tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table_list.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
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

    $(document).on('click', '.btn-create', function() {
        action_type = "create";
        $(".preview-image").attr('src', default_image);
        $('#create-modal').modal('show');
        initSelect($('#create-modal .select2-list'));
    });

    $(document).on('click', '.btn-edit', async function() {
        action_type = "edit";
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
            $('.tags-input').tagsinput({
                tagClass: 'badge-info'
            });
        }
        else {
            serverError();
        }

        $('#edit-modal .modal-content').waitMe("hide");
    });

    $(document).on('click', '.btn-show-modal-medicines', async function() {

        $('#show-modal-medicines').modal('show');
        runLoader($('#show-modal-medicines .modal-content'));

        id =  Number($(this).data('id'));
        let payload = {
            module: module,
            action: 'transaction',
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
            showModalProfile($('#show-modal'), response?.data?.patient || {});
            loadTable(table_transactions, response?.data?.transactions || []);
            loadTable(table_medications, response?.data?.medicines || []);
        }
        else {
            serverError();
        }

        $('[data-toggle="tooltip"]').tooltip()
        $('#show-modal .modal-content').waitMe("hide");
    });

    $('.btn-save-edit').on('click', async function() {
        let total_form = Object.keys(tab_list).length;
        let total_form_validated = 0;

        Object.keys(tab_list).forEach(key => {
            if(tab_list[key].validation()) {
                total_form_validated += 1;
            }
        });

        if(total_form != total_form_validated) {
            errorMessage(`Fill out all required fields.`);
            return;
        }

        let confirm = await confirmationModal(`You want to update this new ${module_label.toLowerCase()}? `,  'No, cancel it!', 'Yes, save it!');

        if(!confirm) {
            return;
        }

        runLoader($('#edit-modal .modal-content'));

        let form = new FormData(document.querySelector('#basic_info_form_edit'));
        let other_info =  $('#other_info_form_edit').serializeArray();

        form = appendData(form, other_info);
        form.append('module',  module);
        form.append('action', 'update');
        form.append('csrf_token', app_csrf_token);
        form.append('id', id);

        let response = await Api.store(serialize(form))

        if(response.success) {
            if(response.data.success) {
                formReset($('#basic_info_form_edit'));
                formReset($('#other_info_form_edit'));
                resetTabs(tab_list, action_type);

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

    $(document).on('click', '.tab-link', function() {
        let modal = action_type == "create" ? "create-modal" : "edit-modal";
        let current_tab = $(`#${modal} .tablist .current`).attr('data-tab');
        let selected_tab = $(this).find('a').attr('data-tab');

        let current_tab_index = getTabIndex(current_tab);
        let selected_tab_index = getTabIndex(selected_tab);

        if(tabsValidation(current_tab_index, selected_tab_index)) {
            tabsNavigation(current_tab, selected_tab);
        }
        else {
            $(`#${modal} .${selected_tab}`).removeClass('active').removeClass('show');
            setTimeout(function() {
                $(`#${modal} .${selected_tab}-tab-content`).removeClass('active').removeClass('show');

                $(`#${modal} .${current_tab}`).addClass("active").addClass("show");
                $(`#${modal} .${current_tab}-tab-content`).addClass("active").addClass("show");
            }, 300);
        }
    });

    $('.btn-save-create').on('click', async function() {
        let total_form = Object.keys(tab_list).length;
        let total_form_validated = 0;

        Object.keys(tab_list).forEach(key => {
            if(tab_list[key].validation()) {
                total_form_validated += 1;
            }
        });

        if(total_form != total_form_validated) {
            errorMessage(`Fill out all required fields.`);
            return;
        }

        let confirm = await confirmationModal(`You want to save this new ${module_label.toLowerCase()}? `,  'No, cancel it!', 'Yes, save it!');

        if(!confirm) {
            return;
        }

        runLoader($('#create-modal .modal-content'));

        let form = new FormData(document.querySelector('#basic_info_form'));
        let other_info =  $('#other_info_form').serializeArray();

        form = appendData(form, other_info);
        form.append('module',  module);
        form.append('action', 'store');
        form.append('csrf_token', app_csrf_token);

        let response = await Api.store(serialize(form))

        if(response.success) {
            if(response.data.success) {
                formReset($('#basic_info_form'));
                formReset($('#other_info_form'));
                resetTabs(tab_list, action_type);
                
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
});