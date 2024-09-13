 // Get modal element
 const modal = document.getElementById('uploadModal');
 // Get open modal button
 const uploadBtn = document.getElementById('uploadBtn');
 // Get close button
 const closeBtn = document.getElementById('cancelBtn');

 const submitBtn = document.getElementById('submitBtn');

 // Listen for click on upload button
 uploadBtn.addEventListener('click', function() {
     modal.style.display = 'flex';
 });

 // Listen for click on close button
 closeBtn.addEventListener('click', function() {
     modal.style.display = 'none';
 });

 // Close the modal if user clicks outside of it
 window.addEventListener('click', function(event) {
     if (event.target === modal) {
         modal.style.display = 'none';
     }
 });

 document.getElementById('submitBtn').addEventListener('click', function(event) {
    let isValid = true;
    
    // Check Document Type
    const docType = document.getElementById('uploadDocType');
    uploadDocType.placeholder = "Enter the type of Document";
    if (docType.value === '') {
        uploadDocType.placeholder = "This field is required!"
        uploadDocType.classList.add('error-input');
    } else {
        uploadDocType.placeholder = "This field is required!"
        uploadDocType.classList.remove('error-input');
    }

    // Check From
    const from = document.getElementById('uploadFrom');
    const fromError = document.getElementById('fromError');
    if (from.value === '') {
        fromError.style.display = 'inline';
        isValid = false;
    } else {
        fromError.style.display = 'none';
    }

    // Check To
    const to = document.getElementById('uploadTo');
    const toError = document.getElementById('toError');
    if (to.value === '') {
        toError.style.display = 'inline';
        isValid = false;
    } else {
        toError.style.display = 'none';
    }

    // Check Subject
    const subject = document.getElementById('uploadSubject');
    const subjectError = document.getElementById('subjectError');
    if (subject.value === '') {
        subjectError.style.display = 'inline';
        isValid = false;
    } else {
        subjectError.style.display = 'none';
    }

    // Check Softcopy
    const softcopy = document.getElementById('softcopy');
    const softcopyError = document.getElementById('softcopyError');
    if (softcopy.value === '') {
        softcopyError.style.display = 'inline';
        isValid = false;
    } else {
        softcopyError.style.display = 'none';
    }

    // Check Category
    const category = document.getElementById('uploadCategory');
    const categoryError = document.getElementById('categoryError');
    if (category.value === '') {
        categoryError.style.display = 'inline';
        isValid = false;
    } else {
        categoryError.style.display = 'none';
    }

    // Check Status
    const status = document.getElementById('uploadStatus');
    const statusError = document.getElementById('statusError');
    if (status.value === '') {
        statusError.style.display = 'inline';
        isValid = false;
    } else {
        statusError.style.display = 'none';
    }

    // Check Assignee
    const assignee = document.getElementById('uploadAssignee');
    const assigneeError = document.getElementById('assigneeError');
    if (assignee.value === '') {
        assigneeError.style.display = 'inline';
        isValid = false;
    } else {
        assigneeError.style.display = 'none';
    }

    // If not valid, prevent form submission
    if (!isValid) {
        alert('Please fill out all required fields.');
        event.preventDefault();
    }
});