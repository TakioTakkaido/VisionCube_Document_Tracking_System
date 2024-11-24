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

$('#about').on('click', function(event){
    // Disable side panel except home tbn
    event.preventDefault();
    $('.home-btn').removeClass('active');
    $('.side-panel-section a').removeClass('active');
    $(this).addClass('active');

    if (!$('.topPanelInfo').hasClass('active')){
        $('.homepage').removeClass('active');
        $('.about').addClass('active');
        $('.mission').removeClass('active');
        $('.vision').removeClass('active');
        $('.contactUs').removeClass('active');

        $('.dashboard-table').removeClass('active');
        $('.homepage').removeClass('active');
        $('.profile-settings').removeClass('active');
        $('.account-settings').removeClass('active');
        $('.document-settings').removeClass('active');
        $('.attachmentUpload-settings').removeClass('active');
        $('.sysInfo-settings').removeClass('active');
        $('.system-settings').removeClass('active'); 
    }
});

$('#mission').on('click', function(event){
    // Disable side panel except home tbn
    event.preventDefault();
    $('.home-btn').removeClass('active');
    $('.side-panel-section a').removeClass('active');
    $(this).addClass('active');

    if (!$('.topPanelInfo').hasClass('active')){
        $('.homepage').removeClass('active');
        $('.dashboard-table').removeClass('active');
        $('.about').removeClass('active');
        $('.mission').addClass('active');
        $('.vision').removeClass('active');
        $('.contactUs').removeClass('active');

        $('.profile-settings').removeClass('active');
        $('.account-settings').removeClass('active');
        $('.document-settings').removeClass('active');
        $('.attachmentUpload-settings').removeClass('active');
        $('.sysInfo-settings').removeClass('active');
        $('.system-settings').removeClass('active'); 
    }
});

$('#vision').on('click', function(event){
    // Disable side panel except home tbn
    event.preventDefault();
    $('.home-btn').removeClass('active');
    $('.side-panel-section a').removeClass('active');
    $(this).addClass('active');

    if (!$('.topPanelInfo').hasClass('active')){
        $('.homepage').removeClass('active');
        $('.about').removeClass('active');
        $('.mission').removeClass('active');
        $('.vision').addClass('active');
        $('.contactUs').removeClass('active');
        
        $('.dashboard-table').removeClass('active');
        $('.homepage').removeClass('active');
        $('.profile-settings').removeClass('active');
        $('.account-settings').removeClass('active');
        $('.document-settings').removeClass('active');
        $('.attachmentUpload-settings').removeClass('active');
        $('.sysInfo-settings').removeClass('active');
        $('.system-settings').removeClass('active'); 
    }
});

$('#contact').on('click', function(event){
    // Disable side panel except home tbn
    event.preventDefault();
    $('.home-btn').removeClass('active');
    $('.side-panel-section a').removeClass('active');
    $(this).addClass('active');

    if (!$('.topPanelInfo').hasClass('active')){
        $('.homepage').removeClass('active');
        $('.about').removeClass('active');
        $('.mission').removeClass('active');
        $('.vision').removeClass('active');
        $('.contactUs').addClass('active');

        $('.dashboard-table').removeClass('active');
        $('.homepage').removeClass('active');
        $('.profile-settings').removeClass('active');
        $('.account-settings').removeClass('active');
        $('.document-settings').removeClass('active');
        $('.attachmentUpload-settings').removeClass('active');
        $('.sysInfo-settings').removeClass('active');
        $('.system-settings').removeClass('active'); 
    }
});


