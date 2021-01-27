var acceptedNotification;
var acceptedFunctionNotification;
var urlSite;

$(document).ready(function() {
    urlSite = location.href;
    if (urlSite.match('ACTION=guardias$')) {
        return;
    }

    if (typeof Cookies.get('PM') === 'undefined') {
        $('#notification-pagos').show();
    } else {
        acceptedNotification = Cookies.get('PM');
    }

    if (typeof Cookies.get('FA') === 'undefined') {
        $('#notification-function').show();
    } else {
        acceptedFunctionNotification = Cookies.get('FA');
    }
});

$(document).on('click', '#notification-accept', function() {
    acceptedNotification = Cookies.set('PM', 1);
    $('#notification-pagos').fadeOut();
});

$(document).on('click', '#notification-function-accept', function() {
    acceptedFunctionNotification = Cookies.set('FA', 1);
    $('#notification-function').fadeOut();
});