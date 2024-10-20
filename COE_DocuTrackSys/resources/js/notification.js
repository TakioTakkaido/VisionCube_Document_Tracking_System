$('.notification .close').on('click', function(event) {
    event.preventDefault(); // Prevent any default action
    $('#modalAlertContainer').addClass('d-none').removeClass('show'); // Hide the alert
});

export function showNotification(message){
    $('.notification .close').trigger('click');
    $('#modalAlertContainer').removeClass('d-none').addClass('show');
    $('#notifMessage').html(message);
}