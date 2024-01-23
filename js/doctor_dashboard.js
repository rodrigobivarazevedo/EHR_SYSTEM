$(document).ready(function() {
    // Use AJAX to fetch user data
    loadAppointments();  
    loadMessages()
    get_all_patients();  
});

function get_all_patients() {
    $.ajax({
        url: "/EHR_system/ajax/doctor_dashboardAJAX.php",
        type: "POST",
        dataType: "json",
        data: { action: "get_all_patients" },
        success: function(response) {
            if (response.message){
                document.getElementById('doctor_patients').textContent = `${response.message}`;
            }else{
                updateCardUI(response)
            }
                
        },
        error: function(xhr) {
            console.log(xhr.responseText);
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
                              <button type="button" class="btn btn-sm btn-outline-secondary" id="view-edit-btn" onclick="editPatient('${patient.PatientID}','${patient.FirstName}', '${patient.LastName}', '${patient.Email}', '${patient.Birthdate}', '${patient.Gender}', '${patient.Address}', '${patient.ContactNumber}')">View/Edit</button>
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

  function editPatient(PatientID, firstName, lastName, email, birthdate, gender, address, contactNumber) {
    // Populate the modal content with the patient information
    document.getElementById('Title').textContent = `${firstName} ${lastName} details`;
    document.getElementById('PatientID_modal').textContent = PatientID;
    document.getElementById('firstname_modal').textContent = firstName;
    document.getElementById('lastname_modal').textContent = lastName;
    document.getElementById('patientEmail_modal').textContent = email;
    document.getElementById('patientBirthdate_modal').textContent = birthdate;
    document.getElementById('patientGender_modal').textContent = gender;
    document.getElementById('patientAddress_modal').textContent = address;
    document.getElementById('patientContactNumber_modal').textContent = contactNumber;

    // Show the Bootstrap modal
    $('#PatientModal').modal('show');
}


    
  // Handle TeleMed button click
  $('#teleMedButton').on('click', function() {
    // Open the message modal
    $('#messageModal').modal('show');
});


// Function to handle sending a message
function sendMessage() {
    // Add your logic to send the message here
    // For example, you can use AJAX to send the data to the server
    const recipient = $('#Doctor_name').val();
    const messageContent = $('#messageContent').val();

    $.ajax({
        url: '/EHR_system/ajax/doctor_dashboardAJAX.php',
        type: 'POST',
        dataType: 'json',
        data: {doctor_name: recipient, content: messageContent, action: "send_message"},
        success: function (sendMessagesData) {
            if(sendMessagesData.success){
                alert(sendMessagesData.success);
                // Close the modal after sending the message
                $('#messageModal').modal('hide');
            } else if (sendMessagesData.message) {
                alert(sendMessagesData.message);
            }
        },
        error: function (error) {
            console.error('Error sending messages:', error);
        }
    });
}

// Function to fetch and display messages
function loadMessages() {
    $.ajax({
        url: '/EHR_system/ajax/doctor_dashboardAJAX.php',
        type: 'POST',
        dataType: 'json',
        data: {action: "get_messages"},
        success: function (messagesData) {
            if (messagesData.message) {
                document.getElementById('messages').textContent = `${messagesData.message}`;
            }else{
                renderMessages(messagesData);
            }
            
        },
        error: function (error) {
            console.error('Error fetching messages:', error);
        }
    });
}

// Function to fetch and display appointments
function loadAppointments() {
    $.ajax({
        url: '/EHR_system/ajax/doctor_dashboardAJAX.php',
        type: 'POST',
        dataType: 'json',
        data: {action: "appointments"},
        success: function (appointmentsData) {
            if (appointmentsData.message) {
                document.getElementById('appointments').textContent = `${appointmentsData.message}`;
            }else{
                renderAppointments(appointmentsData);
            }
            
        },
        error: function (error) {
            console.error('Error fetching appointments:', error);
        }
    });
}



// Helper functions to render data in a tab
function renderAppointments(data) {
    const appointmentsTab = document.getElementById('appointmentsTab');
    const listGroup = appointmentsTab.querySelector('.list-group');
    listGroup.innerHTML = '';

    data.forEach(item => {
        const listItem = document.createElement('li');
        listItem.classList.add('list-group-item');

        const contentDiv = document.createElement('div');

        const dateParagraph = document.createElement('p');
        dateParagraph.textContent = `Date: ${item.AppointmentDate}`; // Update property name
        contentDiv.appendChild(dateParagraph);

        const timeParagraph = document.createElement('p');
        timeParagraph.textContent = `Time: ${item.Timeslot}`; // Update property name
        contentDiv.appendChild(timeParagraph);

        const titleHeading = document.createElement('h6');
        titleHeading.textContent = `PatientID: ${item.PatientID}   ${item.PatientFirstName} ${item.PatientLastName}`; 
        contentDiv.appendChild(titleHeading);

        const doctorParagraph = document.createElement('p');
        doctorParagraph.textContent = `AppointmentID: ${item.AppointmentID}`; // Update property names
        contentDiv.appendChild(doctorParagraph);

        listItem.appendChild(contentDiv);
        listGroup.appendChild(listItem);
    });
}


function renderMessages(data) {
    const messagesTab = document.getElementById('messagesTab');
    const listGroup = messagesTab.querySelector('.list-group');
    listGroup.innerHTML = '';

    data.forEach(item => {
        const listItem = document.createElement('li');
        listItem.classList.add('list-group-item');

        const contentDiv = document.createElement('div');

        const dateParagraph = document.createElement('p');
        dateParagraph.textContent = `Date: ${item.Timestamp}`;
        contentDiv.appendChild(dateParagraph);

        const nameParagraph = document.createElement('h6');
        nameParagraph.textContent = `From: ${item.FirstName} ${item.LastName}`;
        contentDiv.appendChild(nameParagraph);

        
        const contentParagraph = document.createElement('p');
        contentParagraph.textContent = `Content: ${item.Content}`;
        contentDiv.appendChild(contentParagraph);

        

        listItem.appendChild(contentDiv);
        listGroup.appendChild(listItem);
    });
}