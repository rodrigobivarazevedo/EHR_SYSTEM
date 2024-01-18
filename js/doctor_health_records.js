// Event listener for search patient by ID
document.getElementById('searchQueryID').addEventListener('input', function () {
    const patientID = document.getElementById('searchQueryID_input').value;
    console.log(patientID)
    // Call your AJAX function
    get_health_record(patientID);
});


function get_health_record(patientID) {
    $.ajax({
        url: "/EHR_system/ajax/health_recordstAJAX.php",
        type: "POST",
        dataType: "json", // Changed "JSON" to "json"
        data: { patientID: patientID, action: "search_health_records" },
        success: function(response) {
            if (response.message) {
                alert(response.message);
            }else{
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
    const content = document.getElementById('records');
    content.innerHTML = '';
    // Create and append new cards based on the data from the backend
    data.forEach(record => {
        const card = `
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">ID: ${record.RecordID} ${record.DateRecorded}</h5>
                        <p class="card-text">Patient: ${record.PatientID}</p>
                        <p class="card-text">Diagnosis: ${record.Diagnosis}</p>
                      
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="view-edit-btn" onclick="edit_health_record("${record.PatientID}",'${record.RecordID}', '${record.DateRecorded}', '${record.Diagnosis}', '${record.Medications}', '${record.Procedures}', '${record.Comments}')">View/Edit</button>
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


function edit_health_record(PatientID, RecordID, DateRecorded, Diagnosis, Medications, Procedures, Comments) {
    // Populate the form fields with the patient information
    document.getElementById('editTitle').value = `Edit PatientID: ${PatientID}, Record: ${RecordID}`;
    document.getElementById('PatientID').value = PatientID;
    document.getElementById('firstname').value = firstName;
    document.getElementById('lastname').value = lastName;
    document.getElementById('patientEmail').value = email;
    document.getElementById('patientBirthdate').value = birthdate;
    document.getElementById('patientGender').value = gender;
    document.getElementById('patientAddress').value = address;
    document.getElementById('patientContactNumber').value = contactNumber;

    // Optionally, you can perform additional actions related to editing a patient
}


function update_health_record() {
    PatientID = document.getElementById('PatientID_update').value;
    firstName = document.getElementById('firstname_update').value;
    lastName = document.getElementById('lastname_update').value;
    email = document.getElementById('patientEmail_update').value;
    birthdate = document.getElementById('patientBirthdate_update').value;
    gender = document.getElementById('patientGender_update').value;
    address = document.getElementById('patientAddress_update').value;
    contactNumber = document.getElementById('patientContactNumber_update').value;
    if (!firstName || !lastName || !email || !birthdate || !gender || !address || !contactNumber) {
        // Display an alert if any required field is empty
        alert('Please select a Patient.');
        return;
    }

    // Show a confirmation alert
    const confirmResult = window.confirm('Are you sure you want to update this patient?');

    // Check if the user clicked "OK" in the confirmation alert
    if (confirmResult) {
        $.ajax({
            url: "/EHR_system/ajax/doctor_patientAJAX.php",
            type: "POST",
            dataType: "json", // Changed "JSON" to "json"
            data: { firstName: firstName, lastName: lastName, email: email, birthdate: birthdate, gender: gender, address: address, contactNumber: contactNumber, action: "update_patient" },
            success: function(response) {
                alert(response);
                
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



function create_health_record() {
    // Check if all required values are filled
    firstName = document.getElementById('firstname_create').value;
    lastName = document.getElementById('lastname_create').value;
    email = document.getElementById('patientEmail_create').value;
    birthdate = document.getElementById('patientBirthdate_create').value;
    gender = document.getElementById('patientGender_create').value;
    address = document.getElementById('patientAddress_create').value;
    contactNumber = document.getElementById('patientContactNumber_create').value;

    if (!firstName || !lastName || !email || !birthdate || !gender || !address || !contactNumber) {
        // Display an alert if any required field is empty
        alert('Please fill in all required fields.');
        return;
    }

    // Show a confirmation alert
    const confirmResult = window.confirm('Are you sure you want to create this patient?');

    // Check if the user clicked "OK" in the confirmation alert
    if (confirmResult) {
        // Event listener for form submission
        document.getElementById('createPatientForm').addEventListener('submit', function (event) {
            event.preventDefault();
            // Implement your logic for creating a patient
            
            const address = document.getElementById('patientAddress').value;
            const contactNumber = document.getElementById('patientContactNumber').value;
            // Add more patient data properties as needed

            post_patient(firstName, lastName, email, birthdate, gender, address, contactNumber);
            console.log('Creating patient:', patientData);
            // You may submit the form via AJAX or perform other actions

            // Close the modal after submission
            $('#createPatientModal').modal('hide');
        });
    }
}

function post_health_record(firstName, lastName, email, birthdate, gender, address, contactNumber) {
    $.ajax({
        url: "/EHR_system/ajax/doctor_patientAJAX.php",
        type: "POST",
        dataType: "json", // Changed "JSON" to "json"
        data: { firstName: firstName, lastName: lastName, email: email, birthdate: birthdate, gender: gender, address: address, contactNumber: contactNumber, action: "create_patient" },
        
        success: function(response) {
            if (response.message) {
                alert(response.message);
            }else{
                alert(response);
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


function delete_health_record() {
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
            alert("AJAX request failed. Check the console for details.");
        }
    });
}

function confirmation(firstName, lastName, patientID) {
    const confirmResult = window.confirm(`Are you sure you want to delete ${firstName} ${lastName}?`);

    if (confirmResult) {
        // Trigger the form submission
        delete_patient_ajax(patientID);
    }
}

// Event listener for form submission
document.getElementById('deletePatientForm').addEventListener('submit', function (event) {
    event.preventDefault();
    // Handle form submission if needed
});

function delete_health_record_ajax(patientID) {
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
            alert("AJAX request failed. Check the console for details.");
        }
    });
}








