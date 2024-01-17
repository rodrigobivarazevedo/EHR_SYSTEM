
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
    var contactNumberRegex = /^\d{9}$/;
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

    let username = document.getElementById("username").value;
    let email = document.getElementById("email").value;
    let contactNumber = document.getElementById("contactNumber").value;
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;
    let speciality = document.getElementById("speciality").value;
    let FirstName = document.getElementById("FirstName").value;
    let LastName = document.getElementById("LastName").value;
    let gender = document.getElementById("gender").value;
    let birthdate = document.getElementById("birthdate").value;
    
     
    // Check if any of the required fields are empty
    if (!username || !email || !contactNumber || !password || !confirmPassword || !FirstName || !LastName || !gender || !birthdate || !speciality) {
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
            speciality: speciality,
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
            if (response.message) {
                // Redirect to a success page or handle as needed
                alert(response.message);
                window.location.href = "/EHR_system/ui/MyFastCARE/login.php"; 
            } else {
                // Check for the specific error message
                if (response.error && response.error.includes("Username already exists")) {
                    alert("Username already exists. Please choose a different username.");
                } else {
                    // Display a generic error message for other cases
                    alert(response.error);
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



    
    
    

