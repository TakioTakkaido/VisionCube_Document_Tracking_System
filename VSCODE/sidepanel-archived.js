document.getElementById('archived-button').addEventListener('click', function() {
    var dropdown = document.getElementById('archived-dropdown');
    var icon = this.querySelector('i'); // Select the icon inside the archived button

    // Toggle the dropdown visibility
    if (dropdown.style.display === "none" || dropdown.style.display === "") {
        dropdown.style.display = "block"; // Show the dropdown
    } else {
        dropdown.style.display = "none"; // Hide the dropdown
    }

    // Toggle the icon between bx-archive-in and bx-archive-out
    if (icon.classList.contains('bx-archive-in')) {
        icon.classList.remove('bx-archive-in');
        icon.classList.add('bx-archive-out');
    } else {
        icon.classList.remove('bx-archive-out');
        icon.classList.add('bx-archive-in');
    }
});