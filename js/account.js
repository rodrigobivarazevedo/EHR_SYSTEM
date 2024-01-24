$(document).ready(function() {
    get_personal_details();

});

function get_personal_details() {
    $.ajax({
        url: "/EHR_system/ajax/accountAJAX.php",
        type: "GET",
        dataType: "json", 
        data: { action: "get_personal_details" },
        success: function(response) {
            if (response.message){
                alert(response.message);
            }
            Personaldetails(response);
        },
        error: function(xhr) {
            // Log detailed error information to the console
            console.log(xhr.responseText);
            
            // Display a user-friendly error message
            alert("Server request failed.");
        }
    });
}



function Personaldetails(response) {
    // Populate the form fields with the personal details information
    document.getElementById('username_update').value = response.Username;
    document.getElementById('email_update').value = response.Email;
    document.getElementById('contact_update').value = response.ContactNumber;
}


function update_personal_details() {
    let username = document.getElementById('username_update').value;
    let email = document.getElementById('email_update').value;
    let contact = document.getElementById('contact_update').value;

    if (!username || !email || !contact) {
        // Display an alert if any required field is empty
        alert('Please fill out all fields.');
        return;
    }

    // Show a confirmation alert
    const confirmUpdate = window.confirm('Are you sure you want to update personal details?');

    // Check if the user clicked "OK" in the confirmation alert
    if (confirmUpdate) {
        $.ajax({
            url: "/EHR_system/ajax/accountAJAX.php",
            type: "POST",
            dataType: "json", // Changed "JSON" to "json"
            data: { email: email, username: username, contact: contact, action: "update_personal_details" },
            success: function(response) {
                if (response.success){
                    alert(response.success);
                    location.reload();
                } else if(response.message){
                    alert(response.message);
                }
                
            },
            error: function(xhr) {
                // Log detailed error information to the console
                console.log(xhr.responseText);
                
                // Display a user-friendly error message
                alert("Server request failed.");
            }
        });
    }
}

function reset_password() {
    // Get form values
    var oldPassword = document.getElementById('old_password').value;
    var newPassword = document.getElementById('new_password').value;
    var confirmPassword = document.getElementById('confirm_password').value;

    // Validate password fields
    if (oldPassword === '' || newPassword === '' || confirmPassword === '') {
        alert('Please fill in all password fields.');
        return;
    }

    // Check if new password and confirm password match
    if (newPassword !== confirmPassword) {
        alert('New password and confirm password do not match.');
        return;
    }

    // Show a confirmation alert
    const confirmReset = window.confirm('Are you sure you want reset password?');

    // Check if the user clicked "OK" in the confirmation alert
    if (confirmReset) {
        $.ajax({
            url: "/EHR_system/ajax/accountAJAX.php",
            type: "POST",
            dataType: "json", // Changed "JSON" to "json"
            data: { oldPassword: oldPassword, newPassword: newPassword, action: "reset_password" },
            success: function(response) {
                if (response.success){
                    alert(response.success);
                    var resetPasswordForm = document.getElementById('resetPasswordForm');
                    resetPasswordForm.reset();
                    location.reload();
                } else if(response.message){
                    alert(response.message);
                }
                
            },
            error: function(xhr) {
                // Log detailed error information to the console
                console.log(xhr.responseText);
                
                // Display a user-friendly error message
                alert("Server request failed.");
            }
        });
    }
}



function confirmSignOut() {
    // Display a confirmation dialog
    var confirmed = confirm("Are you sure you want to sign out?");
    
    // If the user clicks "OK," redirect to signout.php
    if (confirmed) {
        window.location.href = "/EHR_system/ajax/signout.php";
    }
}