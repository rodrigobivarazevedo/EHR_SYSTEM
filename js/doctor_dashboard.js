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
            try {
                console.log(response)
                updateCardUI(response)
            } catch (error) {
                console.error('Invalid JSON response:', response);
                alert('Invalid JSON response. Check the console for details.');
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            alert("AJAX request failed. Check the console for details.");
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
                                  <button type="button" class="btn btn-sm btn-outline-secondary" id="view-edit-btn">View/Edit</button>
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


    
  // Handle TeleMed button click
  $('#teleMedButton').on('click', function() {
    // Open the message modal
    $('#messageModal').modal('show');
});


// Function to handle sending a message
function sendMessage() {
    // Add your logic to send the message here
    // For example, you can use AJAX to send the data to the server
    const recipient = $('#Patient_name').val();
    const messageContent = $('#messageContent').val();

    $.ajax({
        url: '/EHR_system/ajax/doctor_dashboardAJAX.php',
        type: 'POST',
        dataType: 'json',
        data: {patient_name: recipient, content: messageContent, action: "send_message"},
        success: function (sendMessagesData) {
            if(sendMessagesData.success){
                alert(sendMessagesData.success);
                // Close the modal after sending the message
                $('#messageModal').modal('hide');
            } else if (sendMessagesData.error) {
                alert(sendMessagesData.error);
                console.log(sendMessagesData.UserID);
                console.log(sendMessagesData.DoctorID);
            }
        },
        error: function (error) {
            console.error('Error fetching messages:', error);
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
            console.log(messagesData)
            renderMessages(messagesData);
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
            console.log(appointmentsData)
            renderAppointments(appointmentsData);
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
        dateParagraph.textContent = `Date: ${item.DATE}`; // Update property name
        contentDiv.appendChild(dateParagraph);

        const timeParagraph = document.createElement('p');
        timeParagraph.textContent = `Time: ${item.StartTime}`; // Update property name
        contentDiv.appendChild(timeParagraph);

        const titleHeading = document.createElement('h6');
        titleHeading.textContent = `Appointment: ${item.ConsultationType}`; // Update property name
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
        nameParagraph.textContent = `From: ${item.username} `;
        contentDiv.appendChild(nameParagraph);

        
        const contentParagraph = document.createElement('p');
        contentParagraph.textContent = `Content: ${item.Content}`;
        contentDiv.appendChild(contentParagraph);

        

        listItem.appendChild(contentDiv);
        listGroup.appendChild(listItem);
    });
}