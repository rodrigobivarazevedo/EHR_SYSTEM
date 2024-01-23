<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['UserID'])) {
    // Redirect to the login page
    header('Location: /EHR_SYSTEM/ui/MyFastCARE/login.php');
    exit(); // Make sure to stop execution after the redirect
}
?>


<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
    <title>FastCARE</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="MyFastCARE_styles.css">
      
</head>
<body >   
    
    
    
    
    
    
    <header>
        <div class="px-3 py-2 text-bg-dark">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center my-2 my-lg-0 me-lg-auto text-white text-decoration-none">
                <h3>MyFastCARE</h3>
            </a>

            <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
                <li>
                <a href="doctor_portal.php" class="nav-link text-white">
                    <svg class="bi d-block mx-auto mb-1" width="24" height="24"><use xlink:href="#home"></use></svg>
                    Dashboard
                </a>
                </li>
                <li>
                <a href="patients.php" class="nav-link text-secondary">
                    <svg class="bi d-block mx-auto mb-1" width="24" height="24"><use xlink:href="#speedometer2"></use></svg>
                    Patients 
                </a>
                </li>
                <li>
                <a href="health_records.php" class="nav-link text-white">
                    <svg class="bi d-block mx-auto mb-1" width="24" height="24"><use xlink:href="#table"></use></svg>
                    Health Records 
                </a>
                </li>
                <li>
                <a href="profile.php" class="nav-link text-white">
                    <svg class="bi d-block mx-auto mb-1" width="24" height="24"><use xlink:href="#grid"></use></svg>
                    Profile 
                </a>
                </li>
            </ul>
            </div>
        </div>
        </div>
        <div class="px-3 py-2 border-bottom mb-3">
        

            <div class="text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete a Patient</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Add New Patient</button>

            </div>
        </div>
    </header>


    <main>

  <section class="py-5 text-center container">
    <div class="container">

        <h1>Patient editor</h1>

        <div class="findcaredropdowns">
            
            <div>
                <select id="searchField" class="form-control-file dropdown_item" onchange="updatePlaceholder()">
                    <option disabled selected value="">Select search parameter...</option>
                    <option value="name">Full Name</option>
                    <option value="email">Email</option>
                    <option value="contactNumber">Contact Number</option>
                    <!-- Add more options based on your needs -->
                </select>
            </div>
        </div>
            <div class="input-group mt-3">
                <form class="col-12 col-lg-auto mb-2 mb-lg-0 me-lg-auto" id="searchQueryID" role="search">
                <input type="search" class="form-control" id="searchQueryID_input"placeholder="Search by patientID..." aria-label="Search">
                </form>
            </div>
            <!-- Updated Input Group with Dynamic Placeholder -->
            <div class="col-md-6 mt-3"> <!-- Adjust the column width as needed -->
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchQuery" placeholder="Search by first or last name">
                            <button class="btn btn-primary" type="button" onclick="searchPatients()">Search</button>
                        </div>
            </div>

    </div>
  </section>


        <div class="album py-5 bg-light">
            <div class="container">

            <div class="row">


                <div class="col-md-7">
                        <div class="container p-4">
                            <h3 id="patientSearchResults" class="mb-4">Search Results</h3>
                                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3" id="patients">
                                <!-- Cards will be updated dynamically  -->
                                
                                </div>
                        </div>
                                    
                </div>


                <div class="col-md-5">
                
                    <div class="container p-4">
                    <h3 class="mb-4" id="editTitle">Edit Patient</h3>
                            <!-- Patient update Form -->
                            <form id="updatePatientForm" class="mt-3">
                                <div class="mb-3">
                                    <label for="PatientID_update" class="form-label">Patient ID:</label>
                                    <input type="text" class="form-control" id="PatientID_update" placeholder="Enter patient ID" required>
                                </div>
                                <div class="mb-3">
                                    <label for="firstname_update" class="form-label">First Name:</label>
                                    <input type="text" class="form-control" id="firstname_update" placeholder="Enter patient first name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="lastname_update" class="form-label">Last Name:</label>
                                    <input type="text" class="form-control" id="lastname_update" placeholder="Enter patient last name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="patientEmail_update" class="form-label">Patient Email:</label>
                                    <input type="email" class="form-control" id="patientEmail_update" placeholder="Enter patient email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="patientBirthdate_update" class="form-label">Date of Birth:</label>
                                    <input type="date" class="form-control" id="patientBirthdate_update" required>
                                </div>
                                <div class="mb-3">
                                    <label for="patientGender_update" class="form-label">Gender:</label>
                                    <select id="patientGender_update" class="form-select" required>
                                        <option disabled selected value="">Select gender...</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label d-block">Patient smokes?</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="smokingCheckbox_update" name="smokingCheckbox_update" value="Yes" onclick="handleCheckboxClickUpdate(this)">
                                        <label class="form-check-label" for="smokingCheckbox_update">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="noSmokingCheckbox_update" name="smokingCheckbox_update" value="No" onclick="handleCheckboxClickUpdate(this)">
                                        <label class="form-check-label" for="noSmokingCheckbox_update">No</label>
                                    </div>
                                    <input type="hidden" id="smoker_update" name="smoker_update">
                                </div>

                                <div class="mb-3">
                                    <label for="patientAddress_update" class="form-label">Address:</label>
                                    <input type="text" class="form-control" id="patientAddress_update" placeholder="Enter patient address">
                                </div>
                                <div class="mb-3">
                                    <label for="patientContactNumber_update" class="form-label">Contact Number:</label>
                                    <input type="tel" class="form-control" id="patientContactNumber_update" placeholder="Enter contact number">
                                </div>
                            </form>
                            <button type="button" class="btn btn-primary mt-3" onclick="update_patient()">Update Patient</button>
                    </div>
                </div>
    
            </div>
            </div>
        </div>
            
    <!-- Create Patient Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="createModalLabel">Create a New Patient</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <!-- Patient Creation Form -->
            <form id="createPatientForm" class="mt-3">
                <div class="mb-3">
                    <label for="firstname_create" class="form-label">First Name:</label>
                    <input type="text" class="form-control" id="firstname_create" placeholder="Enter patient first name" required>
                </div>
                <div class="mb-3">
                    <label for="lastname_create" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" id="lastname_create" placeholder="Enter patient last name" required>
                </div>
                <div class="mb-3">
                    <label for="patientEmail_create" class="form-label">Patient Email:</label>
                    <input type="email" class="form-control" id="patientEmail_create" placeholder="Enter patient email" required>
                </div>
                <div class="mb-3">
                    <label for="patientBirthdate_create" class="form-label">Date of Birth:</label>
                    <input type="date" class="form-control" id="patientBirthdate_create" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gender:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="patientGender_update" id="maleRadio_update" value="Male" required>
                        <label class="form-check-label" for="maleRadio_update">Male</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="patientGender_update" id="femaleRadio_update" value="Female" required>
                        <label class="form-check-label" for="femaleRadio_update">Female</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="patientGender_update" id="otherRadio_update" value="Other" required>
                        <label class="form-check-label" for="otherRadio_update">Other</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Patient smokes?</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="smokingCheckbox" name="smokingCheckbox" value="yes" onclick="handleCheckboxClick(this)">
                        <label class="form-check-label" for="smokingCheckbox">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="noSmokingCheckbox" name="smokingCheckbox" value="no" onclick="handleCheckboxClick(this)">
                        <label class="form-check-label" for="noSmokingCheckbox">No</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="patientAddress_create" class="form-label">Address:</label>
                    <input type="text" class="form-control" id="patientAddress_create" placeholder="Enter patient address">
                </div>
                <div class="mb-3">
                    <label for="patientContactNumber_create" class="form-label">Contact Number:</label>
                    <input type="tel" class="form-control" id="patientContactNumber_create" placeholder="Enter contact number">
                </div>
    
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-secondary" onclick="createPatient()">Create Patient</button>
      </div>
    </div>
  </div>
</div>

    <!-- Delete Patient Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="deleteModalLabel">Delete a Patient</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <!-- Patient Creation Form -->
            <form id="deletePatientForm" class="mt-3">
                <div class="mb-3">
                    <label for="patientID" class="form-label">Patient ID:</label>
                    <input type="text" class="form-control" id="patientID" placeholder="Enter patient ID" required>
                </div>
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-secondary" onclick="deletePatient()">Delete Patient</button>
      </div>
    </div>
  </div>
</div>


</main>

<script>



</script>


<script src="/EHR_system/global/jquery.js"></script>
<script src="/EHR_system/js/doctor_patients.js"></script>


</body>

</html>