$('.maintenanceBtn').on('click', function(event){   
    $('.loading').show();
    if ($(this).data('maintenance') == false){
        $('#maintenanceAccountBtn').html('Maintenance Mode: On');
        $('#maintenanceDocumentBtn').html('Maintenance Mode: On');
        updateMaintenance(true);
    } else {
        $('#maintenanceAccountBtn').html('Maintenance Mode: Off');
        $('#maintenanceDocumentBtn').html('Maintenance Mode: Off');
        updateMaintenance(false);
    }
});

function updateMaintenance(maintenance){
    var formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'maintenance': maintenance
    }
    $.ajax({
        type: "POST",
        url: window.routes.updateMaintenance,
        data: formData,
        success: function (response) {
            window.location.reload();
        },
        complete: function(){
            $('.loading').hide();
        }
    });
}