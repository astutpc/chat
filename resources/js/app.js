import Echo from 'laravel-echo';

require('./bootstrap');

   
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ":" + window.laravel_echo_port
});