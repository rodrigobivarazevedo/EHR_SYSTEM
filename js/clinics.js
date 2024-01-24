$(document).ready(function() {
    get_clinics();

});

function get_clinics(selectedSpeciality="",action="get_all") {
    $.ajax({
        url: "/EHR_system/ajax/clinicsAJAX.php",
        type: "GET",
        dataType: "json", 
        data: { speciality: selectedSpeciality, action1: action },
        success: function(response) {
            if (response.message){
                alert(response.message);
            }
            updateCardUI(response);
        },
        error: function(xhr) {
            // Log detailed error information to the console
            console.log(xhr.responseText);
            
            // Display a user-friendly error message
            alert("Server request failed.");
        }
    });
}

    const speciality = document.getElementById('speciality');

    // Event Listeners
    speciality.addEventListener('change', checkAndUpdateCardUI);

    function checkAndUpdateCardUI() {
        const selectedSpeciality = speciality.value;
        
        get_clinics(selectedSpeciality, 'get_clinics');  
    }


    function updateCardUI(data) {
      // Clear existing cards
      $('#content').empty();
  
      // Create and append new cards based on the data from the backend
      data.forEach(clinic => {
          // Split the location string into an array using commas
          const locationParts = clinic.Location.split(',');
  
          // Extract the last part of the array (country)
          const country = locationParts[locationParts.length - 1].trim();
  
          // Join the location parts (excluding the country) with commas
          const locationWithoutCountry = locationParts.slice(0, -1).join(', ');
  
          const card = `
              <div class="col">
                  <div class="card shadow-sm">
                      <div class="card-body">
                          <h5 class="card-title">${clinic.Name}</h5>
                          <p class="card-text">
                              ${locationWithoutCountry},<br>
                              ${country}
                          </p>
                          <div class="d-flex justify-content-between align-items-center">
                              <div class="btn-group">
                                  <button type="button" class="btn btn-sm btn-outline-secondary" onclick="getDirections('${clinic.Location}')">Get Directions</button>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          `;
  
          $('#content').append(card);
      });
  }
  
  
  // Function to open Google Maps with directions
  function getDirections(location) {
      // Replace spaces with '+' for the Google Maps URL
      const formattedLocation = location.replace(/ /g, '+');
      
      // Open Google Maps in a new tab with the specified location
      window.open(`https://www.google.com/maps/dir/?api=1&destination=${formattedLocation}`, '_blank');
  }
  