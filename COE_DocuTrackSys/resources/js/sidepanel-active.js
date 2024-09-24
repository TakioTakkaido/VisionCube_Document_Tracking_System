$(document).ready( function () {
    $('docuTable').DataTable();
} );

// SUGGESTION ON WHAT TO DO, FEEL FREE TO CHANGE THIS, NEEDS HTML TO DISPLAY THE DOCUMENTS IN THE DASHBOARD
// Show incoming documents
document.getElementById('incoming-button').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default link behavior

    $('#docuTable').DataTable({
        ajax: {
            url: window.dashboardRoutes.showIncoming,
            dataSrc: 'incomingDocuments'
        },
        columns: [
            {data: 'type'},
            {data: 'status'},
            {   
                data: 'id',
                render: function(data){
                    return '<a href="' + window.dashboardRoutes.downloadUrl.replace(':id', data) + '">Download File</a>';
                }
            },
            {data: 'sender'},
            {data: 'recipient'},
            {data: 'subject'},
            {data: 'assignee'},
            {data: 'category'}
        ],
        destroy: true,
        pagination: true,
    });   
});
// Show outgoing documents
document.getElementById('outgoing-button').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default link behavior

    $('#docuTable').DataTable({
        ajax: {
            url: window.dashboardRoutes.showOutgoing,
            dataSrc: 'outgoingDocuments'
        },
        columns: [
            {data: 'type'},
            {data: 'status'},
            {
                data: 'id',
                render: function(data){
                    return '<a href="' + window.dashboardRoutes.downloadUrl.replace(':id', data) + '">Download File</a>';
                }
            },
            {data: 'sender'},
            {data: 'recipient'},
            {data: 'subject'},
            {data: 'assignee'},
            {data: 'category'}
        ],
        destroy: true
    });
});

// READ!!
// Documents follow has these variables
//     'type',
//     'status',
//     'owner_id', DONT DISPLAY
//     'file', NOT SHOWN YET JUST SAY ITS NULL
//     'sender',
//     'recipient',
//     'subject',
//     'assignee',
//     'category'