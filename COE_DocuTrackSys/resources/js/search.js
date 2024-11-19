import { documentPreview } from "./dashboard/tables/document/show";

$('#searchBarText').on('input', function(event){
    event.preventDefault();
        if ($(this).val().length != 0){
            $('#searchSuggestions').show();
        var query = $(this).val();

        $.ajax({
            method: "GET",
            url: window.routes.search,
            data: {"query" : query},
            success: function (data) {
                $('#searchSuggestions').html("");
                var documents = data.documents;
                var suggestions = "";
                if (documents.length != 0){
                    for(var i = 0; i < documents.length; i++){
                        var document = documents[i];
                        suggestions += `
                            <div class="list-group-item py-0" data-id="${document.document_id}">
                                <div class="font-weight-bold pt-2">${document.subject}</div>
                                <p class="mb-0">${document.type} / ${document.category} / ${document.status}</p>
                                <p>${moment(document.document_date).format('MMM. DD, YYYY')}</p>
                            </div>
                        `;
                    }
                    console.log(suggestions);
                    $('#searchSuggestions').html(suggestions);
                } else {
                    $('#searchSuggestions').html(`<div class="list-group-item">No available document/s.</div>`)
                }
                
            },
            error: function (response) {
                console.log(response)
            },
            beforeSend: function(){
                $('#searchSuggestions').html(`<div class="list-group-item">Searching document/s...</div>`)
            }
        });
    }
})

$('#searchBarText').on('blur', function(event){
    console.log('sjoahu');
    setTimeout(function() {
        $('#searchSuggestions').hide();
    }, 300);
});

$('#searchSuggestions').on('click', '.list-group-item' , function(event){
    event.preventDefault();
    event.stopPropagation();
    documentPreview($(this).data('id'));
})