// LOGOUT
$('#logoutBtn').on('click', function(event){
    event.preventDefault();
    var formData = new FormData();
    formData = {
        '_token' : $('#token').val(),
    }

    $.ajax({    
        method: "POST",
        url: window.routes.logout,
        data: formData,
        success: function (data) {
            console.log('Logged out');
            window.location.href = data.redirect;
        },
        error: function (data) {
            console.log(data.errors)
        }
    });
});