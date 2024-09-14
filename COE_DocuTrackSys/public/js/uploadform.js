 // Get modal element
 const modal = document.getElementById('uploadModal');
 // Get open modal button
 const uploadBtn = document.getElementById('uploadBtn');
 // Get close button
 const closeBtn = document.getElementById('cancelBtn');

//  Get submit button
 const submitBtn = document.getElementById('submitBtn');

//  Get error boxes
 const errors = document.querySelectorAll('.error');

 // Listen for click on upload button
 uploadBtn.addEventListener('click', function() {
     modal.style.display = 'flex';
    //  
 });

//  Listen for click on close button
 closeBtn.addEventListener('click', function() {
    // Close form
    modal.style.display = 'none';

    // Remove error spans
    errors.forEach(function(errorSpan) {
        errorSpan.style.display = 'none';
        errorSpan.textContent = '';
    });
 });

 // Close the modal if user clicks outside of it
 window.addEventListener('click', function(event) {
     if (event.target === modal) {
         modal.style.display = 'none';
     }
 });

document.getElementById('submitBtn').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default form submission

    // Get the form element
    var form = document.getElementById('uploadModalForm');
    var formData = new FormData(form);

    // Create a new XMLHttpRequest
    var xhr = new XMLHttpRequest();
    xhr.open('POST', form.action, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    // Handle the response
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            // Success response
            var response = JSON.parse(xhr.responseText);
            alert(response.success);
            document.getElementById('uploadModal').style.display = 'none'; // Hide the modal or perform other actions
        } else {
            alert("Please check the necessary information in your form, and try again.");
            // Error response
            var errorTexts = JSON.parse(xhr.responseText).errors;
            // Clear previous errors
            errors.forEach(function(errorSpan) {
                errorSpan.style.display = 'none';
                errorSpan.textContent = '';
            });

            // Display new errors
            for (var key in errorTexts) {
                if (errorTexts.hasOwnProperty(key)) {
                    var errorId = key + 'Error';
                    var errorSpan = document.getElementById(errorId);
                    if (errorSpan) {
                        errorSpan.textContent = errorTexts[key][0];
                        errorSpan.style.display = 'block';
                    }
                }
            }
        }
    };

    // Handle request errors
    xhr.onerror = function() {
        console.error('Request failed');
    };

    // Send the request
    xhr.send(formData);
});
