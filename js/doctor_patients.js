function updatePlaceholder() {
    const searchField = document.getElementById('searchField').value;
    const searchQueryInput = document.getElementById('searchQuery');

    // Update the placeholder based on the selected search field and gender
    switch (searchField) {
        case 'name':
            searchQueryInput.placeholder = `Enter Patient's first and last name`;
            break;
        case 'email':
            searchQueryInput.placeholder = `Enter Patient's email`;
            break;
        case 'contactNumber':
            searchQueryInput.placeholder = `Enter Patient's contact number`;
            break;
        // Add more cases as needed
        default:
            searchQueryInput.placeholder = 'Enter search query';
    }
}


// Event listener for search patient by ID
document.getElementById('searchQueryID').addEventListener('input', function () {
    const patientID = document.getElementById('searchQueryID_input').value;
    if (patientID == ""){
        const content = document.getElementById('patients');
        content.innerHTML = '';
        return;
    }
    // Call your AJAX function
    get_patients("PatientID", patientID);
});


function searchPatients() {
    const searchQueryInputValue = document.getElementById('searchQuery').value;
    const parameter = document.getElementById('searchField').value;
    let isSingleWord = false;

    // Check if the value is a single word (no whitespaces)
    if (parameter == "") {
        isSingleWord = !/\s/.test(searchQueryInputValue);
    }

    // If it's a single word, consider it as the first name
    if (isSingleWord) {
        get_patients("FirstName", searchQueryInputValue);
    } else {
        // Otherwise, use the original parameter and value
        get_patients(parameter, searchQueryInputValue);
    }
}



function get_patients(parameter, searchQueryInputValue) {
    $.ajax({
        url: "/EHR_system/ajax/doctor_patientAJAX.php",
        type: "POST",
        dataType: "json", // Changed "JSON" to "json"
        data: { parameter: parameter, searchQueryInputValue: searchQueryInputValue, action: "search_patients" },
        success: function(response) {
            if (response.message) {
                document.getElementById('patientSearchResults').textContent = `${response.message}`;
                const content = document.getElementById('patients');
                content.innerHTML = '';
            }else{
                document.getElementById('patientSearchResults').textContent = "Search Results";
                updateCardUI(response);
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

function updateCardUI(data) {
    // Clear existing cards
    const content = document.getElementById('patients');
    content.innerHTML = '';
    // Create and append new cards based on the data from the backend
    data.forEach(patient => {
        const card = `
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">${patient.FirstName} ${patient.LastName}</h5>
                        <p class="card-text">ID: ${patient.PatientID}, Email: ${patient.Email}</p>
                        <p class="card-text">Contact Number: ${patient.ContactNumber}</p>
                      
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="view-edit-btn" onclick="editPatient('${patient.PatientID}','${patient.FirstName}', '${patient.LastName}', '${patient.Email}', '${patient.Birthdate}', '${patient.Gender}', '${patient.Address}', '${patient.ContactNumber}', '${patient.Smoker}')">View/Edit</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const cardElement = document.createElement('div');
        cardElement.innerHTML = card;      
        content.appendChild(cardElement);
    });
}

function editPatient(PatientID,firstName, lastName, email, birthdate, gender, address, contactNumber, smoker) {
    // Populate the form fields with the patient information
    document.getElementById('editTitle').textContent = `Edit ${firstName} ${lastName} details`;
    document.getElementById('PatientID_update').value = PatientID;
    document.getElementById('firstname_update').value = firstName;
    document.getElementById('lastname_update').value = lastName;
    document.getElementById('patientEmail_update').value = email;
    document.getElementById('patientBirthdate_update').value = birthdate;
    document.getElementById('patientGender_update').value = gender;
    document.getElementById('patientAddress_update').value = address;
    document.getElementById('patientContactNumber_update').value = contactNumber;

    // Handle the smoker checkbox
    var smokingCheckbox = document.getElementById('smokingCheckbox_update');
    var noSmokingCheckbox = document.getElementById('noSmokingCheckbox_update');
    
    // Check the smoker value and update checkboxes accordingly
    if (smoker === 'Yes') {
        smokingCheckbox.checked = true;
        noSmokingCheckbox.checked = false;
    } else {
        smokingCheckbox.checked = false;
        noSmokingCheckbox.checked = true;
    }

    // Optionally, you can perform additional actions related to editing a patient
}

function handleCheckboxClickUpdate(clickedCheckbox) {
    // Get the other checkbox
    
    const otherCheckbox = clickedCheckbox.id === 'smokingCheckbox_update' ? document.getElementById('noSmokingCheckbox_update') : document.getElementById('smokingCheckbox_update');
    // Uncheck the other checkbox
    otherCheckbox.checked = false;
}

function update_patient() {
    let PatientID = document.getElementById('PatientID_update').value;
    let firstName = document.getElementById('firstname_update').value;
    let lastName = document.getElementById('lastname_update').value;
    let email = document.getElementById('patientEmail_update').value;
    let birthdate = document.getElementById('patientBirthdate_update').value;
    let gender = document.getElementById('patientGender_update').value;
    let address = document.getElementById('patientAddress_update').value;
    let contactNumber = document.getElementById('patientContactNumber_update').value;
    let smokingCheckbox = document.getElementById('smokingCheckbox_update');
    let isSmoker = smokingCheckbox.checked ? smokingCheckbox.value : 'no';

    if (!PatientID || !firstName || !lastName || !email || !birthdate || !gender || !address || !contactNumber || !isSmoker) {
        // Display an alert if any required field is empty
        alert('Please select a Patient or make sure no fields are empty');
        return;
    }

    // Show a confirmation alert
    const confirmUpdate = window.confirm('Are you sure you want to update this patient?');

    // Check if the user clicked "OK" in the confirmation alert
    if (confirmUpdate) {
        $.ajax({
            url: "/EHR_system/ajax/doctor_patientAJAX.php",
            type: "POST",
            dataType: "json", // Changed "JSON" to "json"
            data: { PatientID: PatientID, firstName: firstName, lastName: lastName, email: email, birthdate: birthdate, gender: gender, address: address, contactNumber: contactNumber,smoker: isSmoker, action: "update_patient" },
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


function handleCheckboxClick(clickedCheckbox) {
    // Get the other checkbox
    
    const otherCheckbox = clickedCheckbox.id === 'smokingCheckbox' ? document.getElementById('noSmokingCheckbox') : document.getElementById('smokingCheckbox');
    // Uncheck the other checkbox
    otherCheckbox.checked = false;
}

function createPatient() {
    // Check if all required values are filled
    firstName = document.getElementById('firstname_create').value;
    lastName = document.getElementById('lastname_create').value;
    email = document.getElementById('patientEmail_create').value;
    birthdate = document.getElementById('patientBirthdate_create').value;
    gender = document.querySelector('input[name="patientGender_update"]:checked').value;
    address = document.getElementById('patientAddress_create').value;
    contactNumber = document.getElementById('patientContactNumber_create').value;
    // Get the value of the checked checkbox (smoking)
    let smokingCheckbox = document.getElementById('smokingCheckbox');
    let isSmoker = smokingCheckbox.checked ? smokingCheckbox.value : 'No';

    if (!firstName || !lastName || !email || !birthdate || !gender || !address || !contactNumber || !isSmoker) {
        // Display an alert if any required field is empty
        alert('Please fill in all required fields.');
        return;
    }

    // Show a confirmation alert
    const confirmCreate = window.confirm('Are you sure you want to create this patient?');

    // Check if the user clicked "OK" in the confirmation alert
    if (confirmCreate) {    
        create_patient(firstName, lastName, email, birthdate, gender, address, contactNumber, isSmoker);     
    }
}

function create_patient(firstName, lastName, email, birthdate, gender, address, contactNumber, isSmoker) {
    $.ajax({
        url: "/EHR_system/ajax/doctor_patientAJAX.php",
        type: "POST",
        dataType: "json", // Changed "JSON" to "json"
        data: { firstName: firstName, lastName: lastName, email: email, birthdate: birthdate, gender: gender, address: address, contactNumber: contactNumber, smoker: isSmoker, action: "create_patient" },
        beforeSend: function() {
            // Add any code to run before the request is sent (optional)
        },
        success: function(response) {
            if (response.success){
                alert(response.success);
                // Get the form element and reset it
                var createPatientForm = document.getElementById('createPatientForm');
                createPatientForm.reset();
                // Close the modal
                $('#createModal').modal('hide');
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


function deletePatient() {
    // Check if all required values are filled
    const patientID = document.getElementById('patientID').value;
    if (!patientID) {
        // Display an alert if any required field is empty
        alert('Please enter patient ID.');
        return;
    }

    $.ajax({
        url: "/EHR_system/ajax/doctor_patientAJAX.php",
        type: "POST",
        dataType: "json",
        data: { parameter: "PatientID", searchQueryInputValue: patientID, action: "search_patients" },
        success: function(response) {
            const FirstName = response[0].FirstName; 
            const LastName = response[0].LastName;
            confirmation(FirstName, LastName, patientID);
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            alert("Server request failed.");
        }
    });
}

function confirmation(firstName, lastName, patientID) {
    const confirmDelete = window.confirm(`Are you sure you want to delete ${firstName} ${lastName}?`);

    if (confirmDelete) {
        // Trigger the form submission
        delete_patient_ajax(patientID);
    }
}


function delete_patient_ajax(patientID) {
    $.ajax({
        url: "/EHR_system/ajax/doctor_patientAJAX.php",
        type: "POST",
        dataType: "json",
        data: { patientID: patientID, action: "delete_patient" },
        success: function(response) {
            if (response.success){
                alert(response.success);
                // Close the modal after submission
                $('#deleteModal').modal('hide');
            } else if (response.message){
                alert(response.message);
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            alert("Server request failed.");
        }
    });
}









