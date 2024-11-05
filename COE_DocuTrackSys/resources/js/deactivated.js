$('#deactivatedBackBtn').on('click', function(event){
    event.preventDefault();
    var formData = new FormData();
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
    }

    $('#deactivatedBackBtn').prop('disabled', true);
    $('#deactivatedBackBtn').html(`<i class='bx bx-arrow-back' style="font-size: 17px;"></i>Returning to Homepage...`);
    $.ajax({    
        method: "POST",
        url: window.routes.logout,
        data: formData,
        success: function (data) {
            $('#deactivatedBackBtn').prop('disabled', false);

            window.location.href = data.redirect;
        },
        error: function (data) {
            console.log(data.errors)
        }
    });
});