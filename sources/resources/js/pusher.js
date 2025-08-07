

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Pusher from 'pusher-js';
import Echo from 'laravel-echo';

if (import.meta.env.DEV) {
    // Pusher.logToConsole = true;
}

//import.meta.env не полностью не загружается на проде, поэтому просто захардкодим значения

const pusherSettings = {
    wsHost: window.location.hostname,
    wsPort: '6001',
    wssPort: '6001',
    forceTLS: false,
    disableStats: true,
    cluster: 'mt1',
    enabledTransports: ['ws', 'wss'],
    disabledTransports: ['xhr_streaming', 'xhr_polling', 'sockjs'],
}

if (window.location.hostname != '127.0.0.1') {
    pusherSettings.forceTLS = true;
    // pusherSettings.wsPath = '/websockets';
    pusherSettings.wsPort = '443';
    pusherSettings.wssPort = '443';
}

window.Pusher = new Pusher("poisk-metrov-app", pusherSettings);

window.Echo = new Echo({
    broadcaster: 'pusher',
    client: window.Pusher
});
