
function login() {
    var UsernameOrEmail = document.getElementById("UsernameOrEmail").value;
    var password = document.getElementById("password").value;

    $.ajax({
        url: "/EHR_system/ajax/loginAJAX.php",
        type: "POST",
        dataType: "json", 
        data: { UsernameOrEmail: UsernameOrEmail, password: password, action: "login"},
        success: function(response) {
            // Check if the login was successful
            if (response.UserID && response.message ==="Login successful") {
                // Redirect to patient_portal.php with UserID as a query parameter
                const redirectURL = `/EHR_system/ui/MyFastCARE/doctor_portal.php`;
                window.location.href = redirectURL;
            } else {
                // Handle unsuccessful login (e.g., display an error message)
                console.log(response.error || response.message);
                alert("Login failed. Please check your credentials.");
            }
        },
        error: function(xhr) {
            // Log detailed error information to the console
            console.log(xhr.responseText);
            
            // Display a user-friendly error message
            alert("AJAX request failed. Check the console for details.");
        }
    });
} 


// Function to validate email
function isValidEmail(email) {
    // Use a regular expression for basic email validation
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Function to validate contact number
function isValidContactNumber(contactNumber) {
    // Use a regular expression for basic contact number validation
    var contactNumberRegex = /^\d{10}$/;
    return contactNumberRegex.test(contactNumber);
}

    
function register() {
    // Prevent multiple submissions
    var submitButton = document.getElementById("registerButton");

    // Check if the button is already disabled
    if (submitButton.disabled) {
        return;
    }

    submitButton.disabled = true;

    var username = document.getElementById("username").value;
    var email = document.getElementById("email").value;
    var contactNumber = document.getElementById("contactNumber").value;
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmPassword").value;
    
    var FirstName = document.getElementById("FirstName").value;
    var LastName = document.getElementById("LastName").value;
    var gender = document.getElementById("gender").value;
    var birthdate = document.getElementById("birthdate").value;
    
     
    // Check if any of the required fields are empty
    if (!username || !email || !contactNumber || !password || !confirmPassword || !FirstName || !LastName || !gender || !birthdate) {
        alert("All required fields must be provided.");
        submitButton.disabled = false;  // Re-enable the submit button
        return;
    }

    // Check if passwords match
    if (confirmPassword !== password) {
        alert("Passwords do not match. Please enter matching passwords.");
        submitButton.disabled = false;  // Re-enable the submit button
        return;
    }

    // Check if the email is valid
    if (!isValidEmail(email)) {
        alert("Invalid email address. Please enter a valid email.");
        submitButton.disabled = false;  // Re-enable the submit button
        return;
    }

    // Check if the contact number is valid
    if (!isValidContactNumber(contactNumber)) {
        alert("Invalid contact number. Please enter a valid number.");
        submitButton.disabled = false;  // Re-enable the submit button
        return;
    }

    // Perform AJAX call for registration
    $.ajax({
        url: "/EHR_system/ajax/loginAJAX.php",  // Adjust the URL for registration
        type: "POST",
        dataType: "json",
        data: {
            username: username,
            email: email,
            contactNumber: contactNumber,
            password: password,
            FirstName: FirstName, 
            LastName: LastName, 
            gender: gender, 
            birthdate: birthdate,      
            action: "register"  // Adjust the action for registration
        },
        success: function (response) {
            // Check if the registration was successful
            if (response.message === "Registration successful") {
                // Redirect to a success page or handle as needed
                alert("Registration successful!");
                window.location.href = "/EHR_system/ui/index.php";  // Adjust the redirection URL
            } else {
                // Handle unsuccessful registration
                console.log(response.error || response.message);

                // Check for the specific error message
                if (response.error && response.error.includes("Username already exists")) {
                    alert("Username already exists. Please choose a different username.");
                } else {
                    // Display a generic error message for other cases
                    alert("Registration failed. Please check your information and try again.");
                }
            }
        },
        error: function (xhr) {
            // Log detailed error information to the console
            console.log(xhr.responseText);

            // Display a user-friendly error message
            alert("AJAX request failed. Check the console for details.");
        },
        complete: function () {
            // Re-enable the submit button after the AJAX request completes
            submitButton.disabled = false;
        }
    });
}



    
    
    

