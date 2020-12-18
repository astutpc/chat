import Echo from 'laravel-echo';

require('./bootstrap');

window.io = require('socket.io-client');

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001'
    //transports: ['websocket', 'polling', 'flashsocket'],
});