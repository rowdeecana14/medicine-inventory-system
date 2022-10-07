$(document).ready(function(){
    let module_label = 'User Profile';
    let module = 'users';

    loadUserDetails();
    loadForm();

    Webcam.set({
        height: 250,
        image_format: 'jpeg',
        jpeg_quality: 90
    });

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

    function loadForm() {
        $('.image_href').attr('href', auth_user.image);
        $('.preview-image').attr('src', auth_user.image);
        $('.preview-image').attr('src', auth_user.image);
        $('.username').val(auth_user.username);
    }

    function accountValidation() {
        let form = "#account-form";

        $(form).validate({
            ignore: 'hidden',
            rules: {
                password : {
                    minlength : 5
                },
                confirm_password : {
                    minlength : 5,
                    equalTo : "#password"
                },
            },
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
            $('.preview-image').attr('src', base64);
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

    $('.btn-save-avatar').on('click', async function() {
        runLoader($('.card-body'));

        if($('.image_to_upload').val() == '') {
            errorMessage('Upload or choose profile first.');
        }
        else {
            let payload = {
                module: module,
                action: 'update_profile',
                image_to_upload: $('.image_to_upload').val(),
                csrf_token: app_csrf_token,
            };
    
            let response = await Api.store(payload);
    
            if(response.success) {
                updateMessage(`${module_label} successfully updated.`);
            }
            else {
                serverError();
            }
        }

        $('.card-body').waitMe("hide");
    });

    $('#account-form').on('submit', async function(e) {
        e.preventDefault();
        runLoader($('.card-body'));

        if(accountValidation()) {
            let form = new FormData(document.querySelector('#account-form'));
            form.append('module',  module);
            form.append('action', 'update_account');
            form.append('csrf_token', app_csrf_token);
    
            let response = await Api.update(serialize(form))

            if(response.success) {
                if(response.data.success) {
                    formReset($('#account-form'));
                    updateMessage(`${module_label} successfully updated.`);
                }
                else {
                    errorMessage(response.data.message);
                }
            }
            else {
                serverError();
            }
        }

        $('.card-body').waitMe("hide");
    });

});