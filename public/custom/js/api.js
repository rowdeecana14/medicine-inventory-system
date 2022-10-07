window.Api = {
    all: async function(payload) {
        try {
            let response = await fetch(app_api_url, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                method: 'POST',
                body: JSON.stringify(payload)
            });
            const data = await response.json();

            if([404, 401].includes(response.status)) {
                return { success: false, error: data.message };
            }
            return { success: true, data: data.data };
        }
        catch(error) {
            return { success: false, error: error };
        }
    },

    show: async function(payload) {
        try {
            let response = await fetch(app_api_url, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                method: 'POST',
                body: JSON.stringify(payload)
            });
            const data = await response.json();

            if([404, 401].includes(response.status)) {
                return { success: false, error: data.message };
            }
            return { success: true, data: data.data };
        }
        catch(error) {
            return { success: false, error: error };
        }
    },

    store: async function(payload) {
        try {
            let response = await fetch(app_api_url, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                method: 'POST',
                body: JSON.stringify(payload)
            });
            const data = await response.json();

            if([404, 401].includes(response.status)) {
                return { success: false, error: data.message };
            }

            return { success: true, data: data };
        }
        catch(error) {
            return { success: false, error: error };
        }
    },

    update: async function(payload) {
        try {
            let response = await fetch(app_api_url, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                method: 'POST',
                body: JSON.stringify(payload)
            });
            const data = await response.json();

            if([404, 401].includes(response.status)) {
                return { success: false, error: data.message };
            }

            return { success: true, data: data };
        }
        catch(error) {
            return { success: false, error: error };
        }
    },

    remove: async function(payload) {
        try {
            let response = await fetch(app_api_url, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                method: 'POST',
                body: JSON.stringify(payload)
            });
            const data = await response.json();

            if([404, 401].includes(response.status)) {
                return { success: false, error: data.message };
            }

            return { success: true, data: data };
        }
        catch(error) {
            return { success: false, error: error };
        }
    },
};
   
   