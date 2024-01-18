
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
            if (response.UserID && response.success ==="Login successful") {
                // Redirect to patient_portal.php with UserID as a query parameter
                const redirectURL = `/EHR_system/ui/MyFastCARE/doctor_portal.php`;
                window.location.href = redirectURL;
            } else if (response.message) {
                // Handle unsuccessful login (e.g., display an error message)
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

function isStrongPassword(password) {
    // You can define your own criteria for a strong password, e.g., minimum length, mix of uppercase, lowercase, numbers, and special characters.
    const minLength = 8;
    const hasUppercase = /[A-Z]/.test(password);
    const hasLowercase = /[a-z]/.test(password);
    const hasNumbers = /\d/.test(password);
    const hasSpecialCharacters = /[!@#$%^&*()_\-+=<>?]/.test(password);

    return password.length >= minLength && hasUppercase && hasLowercase && hasNumbers && hasSpecialCharacters;
}

function generateStrongPassword() {
    const length = 8; // Adjust the desired password length
    const uppercaseChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    const lowercaseChars = "abcdefghijklmnopqrstuvwxyz";
    const numberChars = "0123456789";
    const specialChars = "!@#$%^&*()_-+=<>?";

    const getRandomChar = (charset) => {
        const randomIndex = Math.floor(Math.random() * charset.length);
        return charset.charAt(randomIndex);
    };

    // Ensure at least one character from each character set
    let password =
        getRandomChar(uppercaseChars) +
        getRandomChar(lowercaseChars) +
        getRandomChar(numberChars) +
        getRandomChar(specialChars);

    // Fill the remaining characters with a mix of all character sets
    for (let i = password.length; i < length; i++) {
        const allChars = uppercaseChars + lowercaseChars + numberChars + specialChars;
        password += getRandomChar(allChars);
    }

    // Shuffle the characters in the password
    const shuffledPassword = password.split("").sort(() => Math.random() - 0.5).join("");
    return shuffledPassword;
}



function suggestStrongPassword() {
    const passwordInput = document.getElementById("password");
    const suggestedPassword = generateStrongPassword();

    const passwordSuggestionDiv = document.getElementById("passwordSuggestion");
    // Display the suggested password in the HTML element
    passwordSuggestionDiv.innerHTML = `<p>For a strong password, consider using:</p><p><strong>${suggestedPassword}</strong></p>`;
    // Prevent multiple submissions
    var submitButton = document.getElementById("registerButton");
    submitButton.disabled = false;
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

    // Check if passwords match
    if (confirmPassword !== password) {
        alert("Passwords do not match. Please enter matching passwords.");
        submitButton.disabled = false;  // Re-enable the submit button
        return;
    }
    // Check the strength of the password
    if (!isStrongPassword(password)) {
        if (confirm("The password is not very strong. Do you want to generate a strong password?")) {
            suggestStrongPassword();
        } 
        document.getElementById("password").value = "";
        submitButton.disabled = false;
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
            if (response.success) {
                // Redirect to a success page or handle as needed
                alert(response.success);
                window.location.href = "/EHR_system/ui/MyFastCARE/login.php"; 
            } else if(response.message) {
                // Redirect to a success page or handle as needed
                alert(response.message);
            }
        },
        error: function (xhr) {
            // Log detailed error information to the console
            console.log(xhr.responseText);

            // Display a user-friendly error message
            alert("Server request failed.");
        },
        complete: function () {
            // Re-enable the submit button after the AJAX request completes
            submitButton.disabled = false;
        }
    });
}



    
    
    

