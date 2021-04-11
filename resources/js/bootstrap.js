window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

if (localStorage.getItem("token_")) {
    window.axios.defaults.headers.common['Authorization'] = localStorage.getItem("token_");
}

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

//Pusher.log = function (message) { window.console.log(message); }

window.Echo = new Echo({
    // broadcaster: 'pusher',
    // key: process.env.MIX_PUSHER_APP_KEY,
    // cluster: process.env.MIX_PUSHER_APP_CLUSTER,
   // forceTLS: true,
    broadcaster: 'pusher',
    key: 'local',
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: false,
    wsHost: 'aerolink-messenger.xyz',
    wsPort: 6001,
    disableStats: true,
    encrypted: true,
    auth: {
        headers: {
            Authorization: localStorage.getItem("token_")
        },
    },
});

