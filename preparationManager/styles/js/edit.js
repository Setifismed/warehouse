// Wait for the document to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get all edit buttons with the appropriate modal trigger attribute
    const editButtons = document.querySelectorAll('button[data-bs-toggle="modal"][data-bs-target="#editMemberModal"]');

    // Add click event listeners to all edit buttons
    editButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Get the employee ID from the button's data attribute
            const employeeId = this.getAttribute('data-id');

            // Fetch the employee data using AJAX
            fetchEmployeeData(employeeId);
        });
    });

    // Function to fetch employee data via AJAX
    function fetchEmployeeData(employeeId) {
        // Create a new XMLHttpRequest object
        const xhr = new XMLHttpRequest();

        // Configure the request
        xhr.open('POST', 'get_employee_data.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Set up the callback function
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    // Parse the JSON response
                    const employee = JSON.parse(xhr.responseText);

                    // Populate the modal with employee data
                    populateEditModal(employee);
                } catch (e) {
                    console.error('Error parsing employee data:', e);
                }
            } else {
                console.error('Request failed. Status:', xhr.status);
            }
        };

        // Send the request with the employee ID
        xhr.send('id=' + employeeId);
    }

    // Function to populate the edit modal with employee data
    function populateEditModal(employee) {
        // Set the employee ID in the hidden field
        document.getElementById('memberId').value = employee.id;

        // Set the employee first name
        document.getElementById('memberFirstName').value = employee.firstname;

        // Set the employee last name
        document.getElementById('memberLastName').value = employee.lastname;

        // Set the employee account type
        document.getElementById('memberAccountType').value = employee.type;

        // Show/hide the zone container based on account type
        const editZoneContainer = document.getElementById('editZoneContainer');
        if (employee.type === 'preparateur') {
            editZoneContainer.style.display = 'block';
            // Set the employee zone
            document.getElementById('memberZone').value = employee.zone || 'zone1';
        } else {
            editZoneContainer.style.display = 'none';
        }

        // Password field is left empty as it should only be filled if the user wants to change it
    }

    // Account type change handler for showing/hiding zone selector
    document.getElementById('memberAccountType').addEventListener('change', function() {
        const editZoneContainer = document.getElementById('editZoneContainer');
        if (this.value === 'preparateur') {
            editZoneContainer.style.display = 'block';
        } else {
            editZoneContainer.style.display = 'none';
        }
    });

    // Also implement the same functionality for the add member form
    document.getElementById('newMemberAccountType').addEventListener('change', function() {
        const zoneContainer = document.getElementById('zoneContainer');
        if (this.value === 'preparateur') {
            zoneContainer.style.display = 'block';
        } else {
            zoneContainer.style.display = 'none';
        }
    });

    // Implement password toggle functionality
    const passwordToggles = document.querySelectorAll('.password-toggle');
    passwordToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.classList.remove('bi-eye');
                this.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                this.classList.remove('bi-eye-slash');
                this.classList.add('bi-eye');
            }
        });
    });
});