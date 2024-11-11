$('.notification').on('click', '.reload', function(event){
    event.preventDefault();
    window.location.reload();
})

export function showNotification(message){
    $('#notifHeader').html('');
    
    $('#notifMessage').html(message);
    $('.notification').toast('show');
}