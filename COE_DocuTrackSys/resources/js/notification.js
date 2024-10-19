$('.notification .close').on('click', function(event) {
    event.preventDefault(); // Prevent any default action
    $('#modalAlertContainer').addClass('d-none').removeClass('show'); // Hide the alert
});