$('.notification').on('click', '.reload', function(event){
    event.preventDefault();
    window.location.reload();
})

export function showNotification(header="Notification", message){
    $('.notification .close').trigger('click');
    $('#notifHeader').html('');
    $('.toast-body').html('');
    
    $('#notifHeader').html(header);
    $('.toast-body').html(message);
    $('.notification').toast('show');
}