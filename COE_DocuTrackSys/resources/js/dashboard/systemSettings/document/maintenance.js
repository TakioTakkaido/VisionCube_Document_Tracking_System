$('.maintenanceBtn').on('click', function(event){   
    if ($(this).data('maintenance') == false){
        $('.maintenanceBtn').html('Maintenance Mode: On');
        updateMaintenance(true);
    } else {
        $('.maintenanceBtn').html('Maintenance Mode: Off');
        updateMaintenance(false);
    }
});

function updateMaintenance(maintenance){
    var formData = {
        '_token' : $('#token').val(),
        'maintenance': maintenance
    }
    $.ajax({
        type: "POST",
        url: window.routes.updateMaintenance,
        data: formData,
        success: function (response) {
            window.location.reload();
        }
    });
}