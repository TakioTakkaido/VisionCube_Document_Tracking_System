export function getDocumentStatistics() {
    $.ajax({
        method: 'GET',
        url: window.routes.getDocumentStatistics,
        success: function(response) {
            $('#incomingBadge').html(response.incoming);
            $('#outgoingBadge').html(response.outgoing);
            $('#archivedBadge').html(response.archived);
        },
    });
}