$(document).ready(function(){

    var d = new Date();
    $("#date").text(d.toDateString());
    var myVar = setInterval(myTimer, 1000);

    function myTimer() {
      var d = new Date();
      $("#timer").text(d.toLocaleTimeString());
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

    function serverError(message) {
        swal({
            title: `${app_code} Message`,
            text: message,
            type: 'warning',
            icon: 'warning',
            timer: 10000,
            buttons:{
                confirm: {
                    text : 'Okay',
                    className : 'btn btn-primary'
                }
            }
        });
    }

    function formValidate() {
        let form = "#login-form";

        $(form).validate({
            errorPlacement: $.noop,
        });

        if(!$(form).valid()) {
            $(form).validate().focusInvalid();
            return false;
        }

        return true;
    }
       
    $(document).on('submit', '#login-form', async function(e) {
        try {
            runLoader($('body'));
            e.preventDefault();

            if(formValidate()) {
                let payload = {
                    module: "auth",
                    action: 'login',
                    csrf_token: app_csrf_token,
                    username: $('#username').val(),
                    password: $('#password').val(),
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
                    window.location.href = `${base_url}/views/dashboard/`;
                }
            }
            else {
                serverError("Username and password is required.");
            }

            $('body').waitMe("hide");
        }
        catch(error) {
            console.log(error)
            serverError("Contact to your administrator");
        }
    });
});