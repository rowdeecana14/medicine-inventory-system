let fields = [];
let profile_fields = [];

$(window).on("load",function (){
    // Preloader
    $(".preloader").fadeOut(500);
});

// $('.select-group').select2({
//     theme: "bootstrap",
//     dropdownParent:  $('.modal .modal-content')
// });

// $('.modal .select-group').each(function() {  
//     var $parent = $(this).parent(); 
//     $(this).select2({
//         theme: "bootstrap",  
//         dropdownParent: $parent  
//     });  
// });


$(".modal .select-group").select2({
    theme: "bootstrap"
})
.on("select2:opening", function(){
    $(".modal").removeAttr("tabindex", "-1");
})
.on("select2:close", function(){ 
    $(".modal").attr("tabindex", "-1");
});

$('.datepicker').datetimepicker({
    format: 'MM/DD/YYYY',
});

$('.modal').on("hidden.bs.modal", function (e) { //fire on closing modal box
    if ($('.modal:visible').length) { // check whether parent modal is opend after child modal close
        $('body').addClass('modal-open'); // if open mean length is 1 then add a bootstrap css class to body of the page
    }
});

$(".datepicker").on("dp.change", function (e) {
    $(this).closest('.form-group').removeClass('has-error').addClass('has-success');
});

$(".select-group, .select2-list").on('change', function() {
    let value = $(this).val();

    if(value !== '' & value != null) {
        $(this).closest('.form-group').removeClass('has-error').addClass('has-success');

        let select2_selection = $(this).parent().find('.select2-selection');
        let select2_selection_rendered = $(this).parent().find('.select2-selection__rendered');

        $(select2_selection).attr("style", "border-color: #2f7e32 !important;");
        $(select2_selection_rendered).attr("style", "border-color: #2f7e32 !important;");

        $(this).parent().find('.error').remove();
    }   
});

$('.input, .textarea').on('change', function() {
    $(this).closest('.form-group').removeClass('has-error').addClass('has-success');
});

$('.tags-input').tagsinput({
	tagClass: 'badge-info'
});

$(document).on("keypress", ".number-decimals-only", function(event) {
    let inputValue = event.which;
    let element = $(this);

    if (!((inputValue >= 48 && inputValue <= 57) || inputValue == 46)) {
        event.preventDefault();
    }

    if(inputValue == 46) {
        let count = $(this).val().split('.').length - 1;
        if(count != 0) {
            event.preventDefault();
        }
    }
});

$(document).on("keypress", ".blood-pressure-only", function(event) {
    let inputValue = event.which;

    if (!(inputValue >= 47 && inputValue <= 57)) {
        event.preventDefault();
    }

    if(inputValue == 47) {
        let count = $(this).val().split('/').length - 1;
        if(count != 0) {
            event.preventDefault();
        }
    }
});

$(document).on("keypress", ".numbers-only", function(e) {
    let inputValue = event.which;
    if (!(inputValue >= 48 && inputValue <= 57)) {
        event.preventDefault();
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

$(document).on("click", ".logout", async function() {
    try {
        let confirm = await logoutConfirmation();

        if(confirm) {
            let payload = {
                module: "auth",
                action: 'logout',
                csrf_token: app_csrf_token,
            };
            const response = await fetch(app_api_url, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                method: 'POST',
                body: JSON.stringify(payload)
            });
            const data = await response.json();
    
            if(!data.success) {
                serverError(data.message);
            }
    
            if(data.success) {
                window.location.href = `${base_url}`;
            }
        }
    }
    catch(error) {
        // console.log(error)
        serverError("Contact to your administrator");
    }
});

async function  deleteConfirmation() {
    return await swal({
        title: 'Are you sure?',
        text: "You want to delete this record!",
        type: 'warning',
        icon: 'warning',
        timer: 10000,
        buttons:{
            cancel: {
                visible: true,
                text : 'No, cancel!',
                className: 'btn btn-danger'
            },        			
            confirm: {
                text : 'Yes, delete it!',
                className : 'btn btn-secondary'
            }
        }
    });
}

async function  confirmationModal(message_text, cancel_text, confirm_text) {
    return await swal({
        title: 'Are you sure?',
        text: message_text,
        type: 'warning',
        icon: 'warning',
        timer: 10000,
        buttons:{
            cancel: {
                visible: true,
                text : cancel_text,
                className: 'btn btn-danger'
            },        			
            confirm: {
                text : confirm_text,
                className : 'btn btn-secondary'
            }
        }
    });
}

async function  logoutConfirmation() {
    return await swal({
        title: 'Are you sure?',
        text: "You want to logout!",
        type: 'warning',
        icon: 'warning',
        timer: 10000,
        buttons:{
            cancel: {
                visible: true,
                text : 'No, cancel!',
                className: 'btn btn-danger'
            },        			
            confirm: {
                text : 'Yes,  logout it!',
                className : 'btn btn-secondary'
            }
        }
    });
}

function runLoader(el) {
    let fontSize = '';
    let num = 1;
    let effect = 'roundBounce';
    let text = 'Processing ...';

    switch (num) {
        case 1:
            maxSize = '';
            textPos = 'vertical';
            break;
        case 2:
            text = '';
            maxSize = 30;
            textPos = 'vertical';
            break;
        case 3:
            maxSize = 30;
            textPos = 'horizontal';
            fontSize = '18px';
            break;
    }

    el.waitMe({
        effect: effect,
        text: text,
        bg: 'rgba(255,255,255,0.7)',
        color: '#000',
        maxSize: maxSize,
        waitTime: -1,
        source: 'img.svg',
        textPos: textPos,
        fontSize: fontSize,
        onClose: function(el) {}
    });
}

function openLoaderModal() {
    let modal = `
        <div class="modal fade" class="loader-modal" id="loader-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content" style="height: 15rem">
                <div class="modal-body">
                  
                </div>
            </div>
        </div>
    </div>`;

    if($('#loader-modal').length == 0) {
        $('body').append(modal);
    }
    $('#loader-modal').modal('show');

    runLoader($('#loader-modal .modal-content'));
}

function closeLoaderModal() {
    setTimeout(function() {
         $('#loader-modal .modal-body').waitMe("hide");
         $('#loader-modal').modal('hide');
    }, 500);
}

function formReset(element) {
    $(element).parent().find('.select2-selection').attr("style", "border-color: #555 !important;");
    $(element).parent().find('.select2-selection__rendered').attr("style", "border-color: #555 !important;");
    $(element).find('.form-group').removeClass('has-success').removeClass('has-error');
    $(element).find('error').remove();

    $(element).find('.select-group').each(function() {  
        $(this).val(null).trigger('change');
    });

    $(element)[0].reset();
}

function resetTabs(tab_list, type) {
    let modal = type == "create" ? "create-modal" : "edit-modal";
    let total_form = Object.keys(tab_list).length;

    Object.keys(tab_list).forEach(key => {
        let tab = tab_list[key].name;

        setTimeout(function(){
            $(`#${modal} .${tab}`).removeClass('active').removeClass('show');
            $(`#${modal} .${tab}-tab-content`).removeClass('active').removeClass('show');
            
        }, 100);
    });

    setTimeout(function(){
        $(`#${modal} .default-tab`).addClass("active").addClass("show");
        $(`#${modal} .default-tab-content`).addClass("active").addClass("show");
        
    }, 100 * total_form);
}

function saveMessage(message) {
    $.notify({
        title: `${app_code} Message`,
        message: message,
        icon: 'fa fa-bell'
    }, {
        type: 'info',
        placement: {
            from: 'top',
            align: 'right'
        },
        time: 1000,
    });
}

function updateMessage(message) {
    $.notify({
        title: `${app_code} Message`,
        message: message,
        icon: 'fa fa-bell'
    }, {
        type: 'warning',
        placement: {
            from: 'top',
            align: 'right'
        },
        time: 1000,
    });
}

function deleteMessage(message) {
    setTimeout(function() {
        $.notify({
            title: `${app_code} Message`,
            message: message,
            icon: 'fa fa-bell'
        }, {
            type: 'danger',
            placement: {
                from: 'top',
                align: 'right'
            },
            time: 1000,
        });
    }, 1000 );
}

function errorMessage(message) {
    $.notify({
        title: `${app_code} Message`,
        message: message,
        icon: 'fa fa-bell'
    }, {
        type: 'danger',
        placement: {
            from: 'top',
            align: 'right'
        },
        time: 1000,
    });
}

function serverError() {
    swal({
        title: 'System Error',
        text: "Contact to your administrator",
        type: 'warning',
        icon: 'warning',
        timer: 10000,
        buttons:{
            confirm: {
                text : 'Okay',
                className : 'btn btn-secondary'
            }
        }
    });
}

function errorMessageModal(message) {
    const wrapper = document.createElement('div');
    wrapper.innerHTML = message;

    swal({
        title: `${app_code} Message`,
        content:  wrapper,
        icon: 'warning',
        timer: 10000,
        buttons:{
            confirm: {
                text : 'Okay',
                className : 'btn btn-secondary'
            }
        }
    });
}

function serialize (data) {
	let obj = {};
	for (let [key, value] of data) {
		if (obj[key] !== undefined) {
			if (!Array.isArray(obj[key])) {
				obj[key] = [obj[key]];
			}
			obj[key].push(value);
		} else {
			obj[key] = value;
		}
	}
	return obj;
}

function loadTable(datatable, data) {
    // table = element.DataTable();
    // table.clear().draw();
    // table.rows.add(data); 
    // table.columns.adjust().draw();
    // table.responsive.recalc();

    datatable.clear().draw();
    datatable.rows.add(data); 
    datatable.columns.adjust().draw();
}

function showModalDetails(element, data) {

    Object.keys(data).forEach(key => {


        if(fields.includes(key)) {
            if( $(element).find(`select[name='${key}']`).is("select")) {

                if($(element).find(`select[name='${key}']`).hasClass("select2-list")) {

                    let option = new Option(data[key].text, data[key].id, false, false);

                    $(element).find(`select[name='${key}']`).append(option);
                    $(element).find(`select[name='${key}']`).val(data[key].id).trigger("change");
                }
                else {
                    $(element).find(`select[name='${key}']`).select2({ theme: "bootstrap" });
                    $(element).find(`select[name='${key}']`).val(data[key]).trigger("change");
                }
            }
            else if ($(element).find(`input[name='${key}']`).is(':radio')) {
                if(data[key] == "Active") {
                    $("#status_active").prop("checked", true);
                }
                else {
                    $("#status_inactive").prop("checked", true);
                }
            }
            else if($(element).find(`textarea[name='${key}']`).is('textarea')) {
                $(element).find(`textarea[name='${key}']`).text(data[key]).trigger("change");;
            }
            else if($(element).find(`.${key}`).is('img')) {
                $(element).find(`.${key}`).attr("src", data[key]);
                $(element).find('.image_href').attr("href", data[key]);
            }
            else {
                if($(element).find(`input[name='${key}']`).hasClass("input")) {
                     $(element).find(`input[name='${key}']`).val(data[key]).trigger("change");

                    if($(element).find(`input[name='${key}']`).hasClass("tags-input")) {
                        data[key].split(',').forEach(function(item) {
                            $(element).find(`input[name='${key}']`).tagsinput('add', item);
                        });
                    }
                }
                else {
                    $(element).find(`input[name='${key}']`).val(data[key]);
                    $(element).find(`select[name='${key}']`).val(data[key]);
                }
            }
        }
    });
}

function showModalProfile(element, data) {
    Object.keys(data).forEach(key => {
        if(profile_fields.includes(key)) {
            if(!['', null].includes(data[key])) {
                if($(element).find(`.${key}`).is('img')) {
                    $(element).find(`.${key}`).attr("src", data[key]);
                    $(element).find('.image_href').attr("href", data[key]);
                }
                else {
                    $(element).find(`.${key}`).text(data[key]);
                }
            }
        }
    });
}

function initSelect(select) {

    $(select).each(function(index,element){
        $(element).select2({
            theme: "bootstrap",
            ajax: {
                type: "POST",
                url: app_api_url,
                dataType: 'json',
                headers : {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                data: function (params) {
                    var query = {
                        q: params.term,
                        module: $(element).data('module'),
                        action: $(element).data('action'),
                        csrf_token: app_csrf_token
                    }
                    return JSON.stringify(query);
                },
                processResults: function (response) {
                    return {
                        results: response.data
                    };
                }
            },
            // templateResult: (response) => {
            //     console.log(response)
            //     if(response.content) {
            //         return response.content;
            //     }
            //     return response.text;
            // },
            // templateSelection: (response) => {
            //     return response.text;
            // },
            // escapeMarkup: function(markup) {
            //     return markup;
            // }
        })
        .on("select2:opening", function(){
            $(".modal").removeAttr("tabindex", "-1");
        })
        .on("select2:close", function(){ 
            $(".modal").attr("tabindex", "-1");
        });
    });
}

function initTableSelect(select) {
    $(select).each(function(index,element){

        return $(element).select2({
            theme: "bootstrap",
            ajax: {
                type: "POST",
                url: app_api_url,
                dataType: 'json',
                headers : {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                data: function (params) {
                    var query = {
                        q: params.term,
                        module: $(element).data('module'),
                        action: $(element).data('action'),
                        csrf_token: app_csrf_token
                    }
                    return JSON.stringify(query);
                },
                processResults: function (response) {
                    return {
                        results: response.data
                    };
                }
            },
            templateResult: (data) => {
                if($(element).data('type') === 'normal') {
                    return data.text;
                }
                else {
                    let image = '';
                    let extra_text = '';
                    let availabe = 0;

                    if($(element).data('module') === 'health_officials') {
                        image = data.image === null ? app_uploaded_config + $(element).data('image-default') : app_uploaded_health_official_path + data.image;
                        extra_text = `(${data?.occupation || ''})`;
                    }
                    else if($(element).data('module') === 'medicines') {
                        image = data.image === null ? app_uploaded_config + $(element).data('image-default') : app_uploaded_medicine_path + data.image;
                        let stockin = data.stockin === null ? 0:  parseInt(data.stockin);
                        let stockout = data.stockout === null ? 0:  parseInt(data.stockout);
                        let expired = data.expired === null ? 0:  parseInt(data.expired);
                        availabe = (stockin - stockout) - expired;
                        
                        extra_text = `(${availabe} QTY)`;
                    }
                    else if($(element).data('module') === 'patients') {
                        image = data.image === null ? app_uploaded_config + $(element).data('image-default') : app_uploaded_patient_path + data.image;
                        extra_text = `(${data?.gender || ''})`;
                    }


                    if (data.text == 'All') {
                        return `
                            <div class="d-flex">
                                <div class="flex-1 ml-3 pt-1">
                                    <h6 class="text-uppercase fw-bold mb-1">All records</h6>
                                </div>
                            </div>
                        `;
                    }
                    else {
                        return `
                            <div class="d-flex">
                                <div class="avatar">
                                    <img src="${image}" alt="${data.title}" class="avatar-img">
                                </div>
                                <div class="flex-1 ml-3 pt-1">
                                    <h6 class="text-uppercase fw-bold mb-1">${data.title} <span class="text-info fw-bold pl-2 stock-available" data-available="${availabe}">${extra_text}</span></h6>
                                    <span >${data.description}</span>
                                </div>
                            </div>
                        `;
                    }

                    
                }
            },
            templateSelection: (data) => {
                if($(element).data('type') === 'normal') {
                    return data.text;
                }
                else {
                    return data.text;
                }
            },
            escapeMarkup: function(markup) {
                return markup;
            }
        })
        .on("select2:opening", function(){
            $(".modal").removeAttr("tabindex", "-1");
        })
        .on("select2:close", function(){ 
            $(".modal").attr("tabindex", "-1");
        });
    });
}

function appendData(form_data, data) {
    for (let i = 0; i < data.length; i++) {
        form_data.append(data[i].name, data[i].value);
    }

    return form_data;
}

function loadUserDetails() {
    $('.auth-name').text(auth_user.name);
    $('.auth-position').text(auth_user.position);
    $('.auth-image').attr('src', auth_user.image);
}

function base64Image(inputElement) {
    var deferred = $.Deferred();

    var files = inputElement.get(0).files;

    if (files && files[0]) {
        var fr = new FileReader();
        fr.onload = function (e) {
            deferred.resolve(e.target.result);
        };
        fr.readAsDataURL(files[0]);
    } 
    else {
        deferred.resolve(undefined);
    }

    return deferred.promise();
}

function validateForm(form) {
    $(form).validate({
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

function validateFormWithError(form) {
    $(form).validate({
        ignore: 'hidden',
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

function loadPrintWindow(table, title, user) {
    let height = window.outerHeight;
    let width = window.outerWidth;
    let tableHeader = $(table).find('thead').html();
    let tableBody = $(table).find('tbody').html();
    window.newWin = window.open('', 'Print-Window', `width=${width},height=${height}`);
   
    let content = `<!DOCTYPE html>
        <html >
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description" content="">
            <meta name="author" content="">
            <title>Print Records</title>
            <link rel="stylesheet" href="../../public/assets/css/bootstrap.min.css">
            <link rel="stylesheet" href="../../public/custom/css/print.css">
            <script src="../../public/assets/js/plugin/webfont/webfont.min.js"></script>
            <script>
                WebFont.load({
                    google: {"families":["Lato:300,400,700,900"]},
                    custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['../../public/assets/css/fonts.min.css']},
                    active: function() {
                        sessionStorage.fonts = true;
                    }
                });
            </script>
        </head>
        <body >
            <button class="btn-circle-back btn-close"  onclick="window.close()">
                <i class="fas fa-times"></i>
            </button>	
            <button class="btn-circle" onclick="window.print();">
                <i class="fa fa-print"></i>
            </button>
            <div class="content">
                <table>
                    <!-- Start Header -->
                    <thead>
                        <tr>
                            <td>
                                <table class="header-table" > 
                                    <tr class="center">
                                        <td class="header-td-left" style="width: 30%; ">
                                            <img src="../../public/assets/img/config/escalante.png" alt="Escalante City Logo" 
                                                class="header-left-logo"
                                            >
                                            <img src="../../public/assets/img/config/city-health-office.png" alt="City Health Office Logo" 
                                                class="header-left-logo"
                                            >
                                        </td>
                                        <td style="width: 40%;" class="header-title">
                                            <p>Republic of the Philippines</p>
                                            <p>Department of Health</div>
                                            <p>CITY OF ESCALANTE </p>
                                            <p class="hd-title">BARANGAY OLD POBLACION HEALTH CENTER</p>
                                        </td>
                                        <td style="width: 30%;">
                                            <img src="../../public/assets/img/config/old-escalante.png" alt="Old Escalante City Logo" 
                                                class="header-right-logo"
                                            >
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </thead>
                <!-- End Header -->
                    <tr>
                        <td>
                            <div class="title">${title}</div>
                            
                            <!-- Start Print Content -->
                            <table class="print-content">
                                <thead>
                                    ${tableHeader}
                                <thead>
                                <tbody>
                                    ${tableBody}
                                </tbody>
                            </table>
                            <!-- End Print Content -->
                        </td>
                    </tr>
                    <!-- Start Space For Footer -->
                    <tfoot>
                        <tr>
                            <td>
                                <table class="footer-table">
                                    <tr>
                                        <td width="60%"></td>
                                        <td width="40%" class="prepared-label">Prepared By:</td>
                                    </tr>
                                    <tr>
                                        <td width="60%"></td>
                                        <td width="40%" >
                                            <div class="prepared-by">
                                                <div>${user?.name.toUpperCase() || ''}</div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="60%"></td>
                                        <td width="40%" class="prepared-position">${user?.position.toUpperCase() || ''}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tfoot>
                    <!-- End Space For Footer -->
                </table>
            </div>
        </body>
        </html>
    `;

    window.newWin.document.open();
    window.newWin.document.write(content);
    window.newWin.document.close();
}