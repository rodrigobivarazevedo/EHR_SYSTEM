// Event listener for search by PatientID
document.getElementById('searchPatientID').addEventListener('input', function () {
    const patientID = document.getElementById('searchPatientIDInput').value;
    if (patientID == ""){
        const content = document.getElementById('records');
        content.innerHTML = '';
        return;
    }
    // Call your AJAX function
    get_health_records(patientID);
});

// Event listener for search by RecordID
document.getElementById('searchRecordID').addEventListener('input', function () {
    const recordID = document.getElementById('searchRecordIDInput').value;
    if (recordID == ""){
        const content = document.getElementById('records');
        content.innerHTML = '';
        return;
    }
    // Call your AJAX function
    get_health_record(recordID);
});


function get_health_record(recordID) {
    $.ajax({
        url: "/EHR_system/ajax/health_recordsAJAX.php",
        type: "POST",
        dataType: "json", // Changed "JSON" to "json"
        data: {InputValue: recordID, parameter: "RecordID", action: "search_health_records" },
        success: function(response) {
            if (response.message) {
                document.getElementById('recordSearchResults').textContent = `${response.message}`;
                const content = document.getElementById('records');
                content.innerHTML = '';
            }else{
                document.getElementById('recordSearchResults').textContent = "Search Results";
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

function get_health_records(patientID) {
    $.ajax({
        url: "/EHR_system/ajax/health_recordsAJAX.php",
        type: "POST",
        dataType: "json",
        data: { InputValue: patientID, parameter: "PatientID", action: "search_health_records" },
        success: function(response) {
            if (response.message) {
                document.getElementById('recordSearchResults').textContent = `${response.message}`;
                const content = document.getElementById('records');
                content.innerHTML = '';
            }else{
                document.getElementById('recordSearchResults').textContent = "Search Results";
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
                        <h5 class="card-title">ID: ${record.RecordID} Date: ${record.DateRecorded}</h5>
                        <p class="card-text">PatientID: ${record.PatientID}</p>
                        <p class="card-text">Diagnosis: ${record.diagnosis}</p>
                      
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="view-edit-btn" onclick="edit_health_record('${record.PatientID}','${record.RecordID}','${record.DateRecorded}','${record.diagnosis}','${record.medications}','${record.procedures}','${record.comments}')">View/Edit</button>

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
    document.getElementById('editTitle').textContent = `Edit RecordID: ${RecordID} from PatientID: ${PatientID}`;
    document.getElementById('PatientID_update').value = PatientID;
    document.getElementById('RecordID_update').value = RecordID;
    document.getElementById('DateRecorded_update').value = DateRecorded;
    document.getElementById('Diagnosis_update').value = Diagnosis;
    document.getElementById('Medications_update').value = Medications;
    document.getElementById('Procedures_update').value = Procedures;
    document.getElementById('Comments_update').value = Comments;
   
   
}


function update_health_record() {
    PatientID = document.getElementById('PatientID_update').value;
    RecordID = document.getElementById('RecordID_update').value;
    DateRecorded = document.getElementById('DateRecorded_update').value;
    Diagnosis = document.getElementById('Diagnosis_update').value;
    Medications = document.getElementById('Medications_update').value;
    Procedures = document.getElementById('Procedures_update').value;
    Comments = document.getElementById('Comments_update').value;

    if (!PatientID || !RecordID || !DateRecorded || !Diagnosis || !Medications || !Procedures || !Comments) {
        // Display an alert if any required field is empty
        alert('Please select a Record.');
        return;
    }

    // Show a confirmation alert
    const confirmUpdate = window.confirm('Are you sure you want to update this record?');

    // Check if the user clicked "OK" in the confirmation alert
    if (confirmUpdate) {
        $.ajax({
            url: "/EHR_system/ajax/health_recordsAJAX.php",
            type: "POST",
            dataType: "json", // Changed "JSON" to "json"
            data: { PatientID : PatientID, RecordID: RecordID, DateRecorded: DateRecorded, Diagnosis: Diagnosis, Medications: Medications, Procedures: Procedures, Comments: Comments, action: "update_health_record" },
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



function createRecord() {
    PatientID = document.getElementById('PatientID_create').value;
    Diagnosis = document.getElementById('Diagnosis_create').value;
    Medications = document.getElementById('Medications_create').value;
    Procedures = document.getElementById('Procedures_create').value;
    Comments = document.getElementById('Comments_create').value;

    if (!PatientID || !Diagnosis || !Medications || !Procedures || !Comments) {
        // Display an alert if any required field is empty
        alert('Please fill in all required fields.');
        return;
    }

    
    // Show a confirmation alert
    const confirmcreate = window.confirm('Are you sure you want to create this record?');

    // Check if the user clicked "OK" in the confirmation alert
    if (confirmcreate) {
        // Event listener for form submission
        create_health_record(PatientID, Diagnosis, Medications, Procedures, Comments);
      
    }
}

function create_health_record(PatientID, Diagnosis, Medications, Procedures, Comments) {
    $.ajax({
        url: "/EHR_system/ajax/health_recordsAJAX.php",
        type: "POST",
        dataType: "json", // Changed "JSON" to "json"
        data: { PatientID : PatientID, Diagnosis: Diagnosis, Medications: Medications, Procedures: Procedures, Comments: Comments, action: "create_health_record" },
        success: function(response) {
            if (response.success){
                alert(response.success);
                // Get the form element and reset it
                var createRecordForm = document.getElementById('createRecordForm');
                createRecordForm.reset();
                // Close the modal
                $('#createRecordModal').modal('hide');
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


function deleteRecord() {
    // Check if all required values are filled
    let PatientID = document.getElementById('DeletePatientID').value;
    let RecordID = document.getElementById('DeleteRecordID').value;
    if (!PatientID) {
        // Display an alert if any required field is empty
        alert('Please enter patient ID.');
        return;
    }
    if (!RecordID) {
        // Display an alert if any required field is empty
        alert('Please enter record ID.');
        return;
    }

    $.ajax({
        url: "/EHR_system/ajax/doctor_patientAJAX.php",
        type: "POST",
        dataType: "json",
        data: { parameter: "PatientID", searchQueryInputValue: PatientID, action: "search_patients" },
        success: function(response) {
            const FirstName = response[0].FirstName; 
            const LastName = response[0].LastName;
            confirmation(FirstName, LastName, PatientID, RecordID);
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            alert("Server request failed. Check the console for details.");
        }
    });
}

function confirmation(firstName, lastName, PatientID, RecordID) {
    const confirmDelete = window.confirm(`Are you sure you want to delete ${firstName} ${lastName} record?`);

    if (confirmDelete) {
        // Trigger the form submission
        delete_health_record(PatientID, RecordID);
    }
}

function delete_health_record(PatientID, RecordID) {
    $.ajax({
        url: "/EHR_system/ajax/doctor_patientAJAX.php",
        type: "POST",
        dataType: "json",
        data: { PatientID: PatientID, RecordID: RecordID, action: "delete_health_record" },
        success: function(response) {
            if (response.success){
                alert(response.success);
                // Close the modal after submission
                var deleteRecordForm = document.getElementById('deleteRecordForm');
                deleteRecordForm.reset();
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








