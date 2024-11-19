// LOGOUT
$('#logoutBtn').on('click', function(event){
    event.preventDefault();
    var formData = new FormData();
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
    }

    $('#logoutBtn').html('Logging Out...');
    $('#logoutBtn').prop('disabled', true);
    $.ajax({    
        method: "POST",
        url: window.routes.logout,
        data: formData,
        success: function (data) {
            console.log('Logged out');
            $('#logoutBtn').html('Log Out');
            $('#logoutBtn').prop('disabled', false);
            window.location.href = data.redirect;
        },
        error: function (data) {
            console.log(data.errors)
        },
        beforeSend: function(){
            $('.loading').show();
        },
        complete: function(){
            $('.loading').hide();
        }
    });
});