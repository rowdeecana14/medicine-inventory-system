$(document).ready(function(){
    let module = 'dashboard';

    loadWidgets();
    loadListings();
    loadPieGraph();
    loadLineGraph();

    loadWelcomeMessage();

    function loadWelcomeMessage() {
        if(login_count == 1) {

            $('.auth-name').text(auth_user.name);
            $('.auth-position').text(auth_user.position);
            $('.auth-image').attr('src', auth_user.image);

            saveMessage(`Hello ${auth_user.fname}, welcome to ${app_title.toLowerCase()}.`);
        }
        else {
            loadUserDetails();
        }
    }

    async function loadWidgets() {
        let payload = {
            module: module,
            action: 'widgets',
            csrf_token: app_csrf_token,
        };

        let response = await Api.all(payload);

        if(response.success) {
            Object.keys(response.data).forEach(key => {
                if($(`.${key}`).length) {
                    $(`.${key}`).text(response.data[key]);
                }
            });
        }
        else {
            serverError();
        }
    }

    async function loadListings() {
        let payload = {
            module: module,
            action: 'listings',
            csrf_token: app_csrf_token,
        };

        let response = await Api.all(payload);

        if(response.success) {
            loadListingsHtml(response.data);
        }
        else {
            serverError();
        }
    }

    function loadListingsHtml(data) {
        let low_stock = '';
        let expiring_stock = '';


        for(let index = 0; index < data.low_stocks.length; index++) {
            let medicine = data?.low_stocks[index] || {};
            low_stock += `
                <div class="d-flex">
                    <div class="avatar">
                        <img src="${medicine?.image}" alt="${medicine.name}" class="avatar-img rounded-circle" style="padding: 2px;
                        border: 1px solid #aaa;">
                    </div>
                    <div class="flex-1 pt-1 ml-2">
                        <h6 class="fw-bold mb-1">${medicine.name} <span class="text-primary pl-3">(${medicine.category})</span></h6>
                        <small class="text-muted">${medicine.description}</small>
                    </div>
                    <div class="d-flex ml-auto align-items-center">
                        <h3 class="text-danger fw-bold" style="font-size: 25px">${medicine.quantity}</h3>
                    </div>
                </div>
                <div class="separator-dashed"></div>
            `;
        }

        for(let index = 0; index < data.expiring_stocks.length; index++) {
            let medicine = data?.expiring_stocks[index] || {};
            expiring_stock += `
                <div class="d-flex">
                    <div class="avatar">
                        <img src="${medicine?.image}" alt="${medicine.name}" class="avatar-img rounded-circle" style="padding: 2px;
                        border: 1px solid #aaa;">
                    </div>
                    <div class="flex-1 pt-1 ml-2">
                        <h6 class="fw-bold mb-1">${medicine.name} <span class="text-danger pl-3">(${medicine.expired_at})</span></h6>
                        <small class="text-muted">${medicine.description}</small>
                    </div>
                    <div class="d-flex ml-auto align-items-center">
                        <h3 class="text-danger fw-bold" style="font-size: 25px">${medicine.quantity}</h3>
                    </div>
                </div>
                <div class="separator-dashed"></div>
            `;
        }


        $('.low-stocks').html(low_stock);
        $('.expiring-stocks').html(expiring_stock);
    }

    async function loadPieGraph() {
        let payload = {
            module: module,
            action: 'pie-graphs',
            csrf_token: app_csrf_token,
        };

        let response = await Api.all(payload);

        if(response.success) {
            loadPieGraphHtml(response.data);
        }
        else {
            serverError();
        }
    }

    function loadPieGraphHtml(data) {
        let available = data?.available_stocks;
        let avaialble_canvas = document.getElementById('available-stocks').getContext('2d');

        let expired = data?.expired_stocks;
        let expired_canvas = document.getElementById('expired-stocks').getContext('2d');

        let available_graph = new Chart(avaialble_canvas || [], {
            type: 'pie',
            data: {
                datasets: [{
                    data: available?.values || [],
                    backgroundColor: available?.colors || [],
                    borderWidth: 0
                }],
                labels: available?.labels || []
            },
            options : {
                responsive: true, 
                maintainAspectRatio: false,
                legend: {
                    position : 'bottom',
                    labels : {
                        fontColor: 'rgb(154, 154, 154)',
                        fontSize: 11,
                        usePointStyle : true,
                        padding: 20
                    }
                },
                pieceLabel: {
                    render: 'percentage',
                    fontColor: 'white',
                    fontSize: 14,
                },
                tooltips: false,
                layout: {
                    padding: {
                        left: 20,
                        right: 20,
                        top: 20,
                        bottom: 20
                    }
                }
            }
        });

        let expired_graph = new Chart(expired_canvas || [], {
            type: 'pie',
            data: {
                datasets: [{
                    data: expired?.values || [],
                    backgroundColor: expired?.colors || [],
                    borderWidth: 0
                }],
                labels: expired?.labels || []
            },
            options : {
                responsive: true, 
                maintainAspectRatio: false,
                legend: {
                    position : 'bottom',
                    labels : {
                        fontColor: 'rgb(154, 154, 154)',
                        fontSize: 11,
                        usePointStyle : true,
                        padding: 20
                    }
                },
                pieceLabel: {
                    render: 'percentage',
                    fontColor: 'white',
                    fontSize: 14,
                },
                tooltips: false,
                layout: {
                    padding: {
                        left: 20,
                        right: 20,
                        top: 20,
                        bottom: 20
                    }
                }
            }
        });
    }

    async function loadLineGraph() {
        let payload = {
            module: module,
            action: 'line-graphs',
            csrf_token: app_csrf_token,
        };

        let response = await Api.all(payload);

        if(response.success) {
            loadLineGraphHtml(response.data);
        }
        else {
            serverError();
        }
    }

    function loadLineGraphHtml(data) {
        let recieved = data?.monthly_received;
        let dispensed = data?.monthly_dispensed;
        let expired = data?.monthly_expired;
        let movement_canvas = document.getElementById('stock-movements').getContext('2d');

        var movement_graph = new Chart(movement_canvas, {
            type: 'line',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                datasets: [{
                    label: "Stock Received",
                    borderColor: "#1d7af3",
                    pointBorderColor: "#FFF",
                    pointBackgroundColor: "#1d7af3",
                    pointBorderWidth: 2,
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 1,
                    pointRadius: 4,
                    backgroundColor: 'transparent',
                    fill: true,
                    borderWidth: 2,
                    data: recieved
                },
                {
                    label: "Stock Dispensed",
                    borderColor: "#59d05d",
                    pointBorderColor: "#FFF",
                    pointBackgroundColor: "#59d05d",
                    pointBorderWidth: 2,
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 1,
                    pointRadius: 4,
                    backgroundColor: 'transparent',
                    fill: true,
                    borderWidth: 2,
                    data: dispensed
                },
                {
                    label: "Stock Expired",
                    borderColor: "#6f42c1 ",
                    pointBorderColor: "#FFF",
                    pointBackgroundColor: "#f3545d",
                    pointBorderWidth: 2,
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 1,
                    pointRadius: 4,
                    backgroundColor: 'transparent',
                    fill: true,
                    borderWidth: 2,
                    data: expired
                },
            ]
            },
            options : {
                responsive: true, 
                maintainAspectRatio: false,
                legend: {
                    position: 'bottom',
                    labels : {
                        padding: 10,
                        fontColor: '#1d7af3',
                    }
                },
                tooltips: {
                    bodySpacing: 4,
                    mode:"nearest",
                    intersect: 0,
                    position:"nearest",
                    xPadding:10,
                    yPadding:10,
                    caretPadding:10
                },
                layout:{
                    padding:{left:15,right:15,top:15,bottom:15}
                }
            }
        });
    }

});