// SUGGESTION ON WHAT TO DO, FEEL FREE TO CHANGE THIS, NEEDS HTML TO DISPLAY THE DOCUMENTS IN THE DASHBOARD
// Show incoming documents
document.getElementById('incoming-button').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default link behavior
    
    var xhr = new XMLHttpRequest();
    xhr.open('GET', window.dashboardRoutes.showIncoming, true); // Replace with your route

    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            var response = JSON.parse(xhr.responseText);
            // Here, you can handle the response, for example, update the document list dynamically
            console.log(response.incomingDocuments); // Check the response first
            // Display the incoming documents
            var incomingDocuments = response.incomingDocuments;
             
            // Put necessary functions to display the incoming documents in dashboard
            
        } else {
            // Handle error
            console.error('Error loading documents');
        }
    };

    xhr.onerror = function() {
        console.error('Request failed');
    };

    xhr.send();
});
// Show outgoing documents
document.getElementById('outgoing-button').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default link behavior
    
    var xhr = new XMLHttpRequest();
    xhr.open("GET", window.dashboardRoutes.showOutgoing, true); // Replace with your route

    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            var response = JSON.parse(xhr.responseText);
            // Here, you can handle the response, for example, update the document list dynamically
            console.log(response.outgoingDocuments); // Check the response first
            // Display the incoming documents
            var outgoingDocuments = response.outgoingDocuments;
             
            // Put necessary functions to display the outgoing documents in dashboard
            
        } else {
            // Handle error
            console.error('Error loading documents');
        }
    };

    xhr.onerror = function() {
        console.error('Request failed');
    };

    xhr.send();
});