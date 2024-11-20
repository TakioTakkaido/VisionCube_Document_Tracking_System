$('#generateReportDayBtn').on('click', function(event){
    if ($('#analyticsDay').val().length !== 0){
        $('#reportDate').val($('#analyticsDay').val());
    } else {
        $('#reportDate').val($('#analyticsDay').data('value'));
    }

    $('#generateReport').modal('show');
});

$('#generateReportWeekBtn').on('click', function(event){
    if ($('#analyticsWeek').val().length !== 0){
        $('#reportDate').val($('#analyticsWeek').val());
    } else {
        $('#reportDate').val($('#analyticsWeek').data('value'));
    }

    $('#generateReport').modal('show');
});

$('#generateReportMonthBtn').on('click', function(event){
    if ($('#analyticsMonth').val().length !== 0){
        $('#reportDate').val($('#analyticsMonth').val());
    } else {
        $('#reportDate').val($('#analyticsMonth').data('value'));
    }
    $('#generateReport').modal('show');
});

$('#generateReportYearBtn').on('click', function(event){
    if ($('#analyticsYear').val().length !== 0){
        $('#reportDate').val($('#analyticsYear').val());
    } else {
        $('#reportDate').val($('#analyticsYear').data('value'));
    }
    $('#generateReport').modal('show');
});