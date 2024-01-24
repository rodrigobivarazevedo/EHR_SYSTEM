function get_doctorsInfo(selectedspeciality="", selectedclinic="",action="get_all") {
    $.ajax({
        url: "/EHR_system/ajax/doctorsAJAX.php",
        type: "POST",
        dataType: "json", // Changed "JSON" to "json"
        data: { speciality: selectedspeciality, clinic: selectedclinic, action1: action },
        success: function(response) {
            if (response.message) {
              document.getElementById('doctors_results').textContent = `${response.message}`;
              let content = document.getElementById('content');
              content.innerHTML = '';
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

    const clinic = document.getElementById('clinic');
    const speciality = document.getElementById('speciality');

    // Event Listeners
    speciality.addEventListener('change', checkAndUpdateCardUI);
    clinic.addEventListener('change', checkAndUpdateCardUI);

    function checkAndUpdateCardUI() {
        const selectedspeciality = speciality.value;
        const selectedclinic = clinic.value;

        get_doctorsInfo(selectedspeciality, selectedclinic, 'get_doctors');

        
    }

    function updateCardUI(data) {
      // Clear existing cards
      const content = document.getElementById('content');
      content.innerHTML = '';
  
      // Create and append new cards based on the data from the backend
      data.forEach(doctor => {
        const card = `
        <div class="col" >
                <div class="card shadow-sm">
                  <div class="card-body">
                    <h5 class="card-title">${doctor.FirstName} ${doctor.LastName}</h5>
                      <p class="card-text">${doctor.Speciality}, ${doctor.clinic}</p>
                    <div class="d-flex justify-content-between align-items-center">
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


$(document).ready(
  function() {
  get_doctorsInfo();  

});

    
