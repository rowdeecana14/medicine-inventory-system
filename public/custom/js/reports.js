$(document).ready(function(){
    loadUserDetails();

    function loadResult(data) {
        let content = '';

        for (let index = 0; index < data.length; index++) {
            const element = data[index];

            content += `
                <tr>
                    <td>${element.index}</td>
                    <td>${element.or_no}</td>
                    <td>${element.amount}</td>
                    <td>${element.paid_at}</td>
                </tr>
            `;
        }

        if(data.length == 0) {
            content += `
                <tr>
                    <td colspan="4" class="text-center">No records available</td>
                </tr>
            `;
        }

        $('.table tbody').html(content);
    }

    $(".select-field").select2({
        theme: "bootstrap"
    });

    $("#filter-form").validate({
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

    $(document).on('submit', '#filter-form', async function(e) {
        e.preventDefault();
        runLoader($('.filter'));

        let payload = {
            module: 'report',
            action: 'generate',
            csrf_token: app_csrf_token,
            types: $('#types').val(),
            date_from: $('#date_from').val(),
            date_end: $('#date_end').val(),
        };

        let response = await Api.all(payload);

        if(!response.success) {
            serverError();
        }

        $('.filter-result').attr('hidden', false);
        $('.filter-result-title').text(response.data.title);
        $('.btn-excel').attr('download', response.data.title);
        loadResult(response.data.payments);

        $('.filter').waitMe("hide");
    });

    $(document).on('click', '.btn-print', function() {
        let height = window.outerHeight;
        let width = window.outerWidth;
        let html = $("#table-export tbody").html();
        let newWin = window.open('', 'Print-Window', `width=${width},height=${height}`);
        let title = $('.filter-result-title').text();
       
        let content = `<!DOCTYPE html>
            <html >
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta name="description" content="">
                <meta name="author" content="">
                <title>Print Records</title>
                <link href="../../public/assets/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    table, th, td {
                        font-size: 13px; 
                        border: 1px solid black;
                    }
                    h4 { 
                        font-size:12px; 
                    } 
                    h3 { 
                        font-size:18px; 
                        font-weight:bold; 
                    } 
                    hr {
                        border: 0;
                        border-top: 1px solid black;
                    }
                </style>
            </head>
            <body onload="window.print()">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <br>
                         <center><h3 id="title_name">${title}</h3></center>
                        <br>
                        <table class="table">
                            <thead>
                            <th style="border: 1px solid black">#</th>
                              <th width="" style="border: 1px solid black">OR No.</th>
                              <th width="" style="border: 1px solid black">Amount</th>
                              <th width="" style="border: 1px solid black">Date Paid</th>
                            <thead>
                        <tbody style="border: 1px solid black">${html}</tbody>
                        </table>
                    </div>
                </div>
            </div>
            </body>
            </html>
        `;

        newWin.document.open();
        newWin.document.write(content);
        newWin.document.close();

        // setTimeout(function() {
        //     newWin.close();
        // }, 2000);
    });
});