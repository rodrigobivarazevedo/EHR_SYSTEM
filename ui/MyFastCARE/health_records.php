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
    <meta name="description" content="FastCARE, implementing digital solutions for doctors in healthcare">
    <meta name="author" content="Rodrigo Azevedo, Kelly Khalil">
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
                <a href="patients.php" class="nav-link text-white">
                    <svg class="bi d-block mx-auto mb-1" width="24" height="24"><use xlink:href="#speedometer2"></use></svg>
                    Patients 
                </a>
                </li>
                <li>
                <a href="health_records.php" class="nav-link text-secondary">
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
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete Record</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRecordModal">Add New Record</button>

            </div>
            
        
        </div>
    </header>


    <main>

    <section class="py-5 text-center container">
    <div class="container">

        <h1>HealthRecords editor</h1>

        <div class="findcaredropdowns">
            
        </div>
            <div class="input-group mt-3">
                <form class="col-12 col-lg-auto mb-2 mb-lg-0 me-lg-auto" id="searchPatientID" role="search">
                <input type="search" class="form-control" id="searchPatientIDInput"placeholder="Search by patientID..." aria-label="Search">
                </form>
                
            </div>
            <div class="input-group mt-3">
                <form class="col-12 col-lg-auto mb-2 mb-lg-0 me-lg-auto" id="searchRecordID" role="search">
                <input type="search" class="form-control" id="searchRecordIDInput"placeholder="Search by RecordID..." aria-label="Search">
                </form>
                
            </div>

    </div>
  </section>


        <div class="album py-5 bg-light">

        <div class="container">

            <div class="row">


                <div class="col-md-7">
                        <div class="container p-4">
                            <h3 id="recordSearchResults" class="mb-4">Search Results</h3>
                                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3" id="records">
                                <!-- Cards will be updated dynamically  -->
                                
                                </div>
                        </div>
                                    
                </div>


                <div class="col-md-5">
                
                    <div class="container p-4">
                    <h3 class="mb-4" id="editTitle">Edit Record</h3>
                            <!-- Record edit Form -->
                            <form id="updateRecordForm" class="mt-3">
                                <div class="mb-3">
                                    <label for="RecordID_update" class="form-label">Record ID:</label>
                                    <input type="text" class="form-control" id="RecordID_update" placeholder="Enter record ID" required>
                                </div>
                                <div class="mb-3">
                                    <label for="PatientID_update" class="form-label">Patient ID:</label>
                                    <input type="text" class="form-control" id="PatientID_update" placeholder="Enter patient ID" required>
                                </div>
                                <!-- Additional Health Record Information -->
                                <div class="mb-3">
                                    <label for="DateRecorded_update" class="form-label">Date Recorded:</label>
                                    <input type="date" class="form-control" id="DateRecorded_update" required>
                                </div>
                                <div class="mb-3">
                                    <label for="Diagnosis_update" class="form-label">Diagnosis:</label>
                                    <textarea class="form-control" id="Diagnosis_update" placeholder="Enter diagnosis" rows="4"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="Medications_update" class="form-label">Medications:</label>
                                    <textarea class="form-control" id="Medications_update" placeholder="Enter medications"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="Procedures_update" class="form-label">Procedures:</label>
                                    <textarea class="form-control" id="Procedures_update" placeholder="Enter procedures"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="Comments_update" class="form-label">Comments:</label>
                                    <textarea class="form-control" id="Comments_update" placeholder="Enter comments" rows="4"></textarea>
                                </div>
                                <!-- Add more health record fields as needed -->
                            </form>

                            <button type="button" class="btn btn-primary mt-3" onclick="update_health_record()">Update Record</button>
                    </div>
                </div>
    
            </div>
            </div>
        </div>
            
    <!-- Create Record Modal -->
<div class="modal fade" id="createRecordModal" tabindex="-1" aria-labelledby="createRecordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="createRecordModalLabel">Create a New Health Record</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <!-- Record creation Form -->
            <form id="createRecordForm" class="mt-3">
                <div class="mb-3">
                    <label for="PatientID_create" class="form-label">Patient ID:</label>
                    <input type="text" class="form-control" id="PatientID_create" placeholder="Enter patient ID" required>
                </div>
                <div class="mb-3">
                    <label for="Diagnosis_create" class="form-label">Diagnosis:</label>
                    <textarea class="form-control" id="Diagnosis_create" placeholder="Enter diagnosis" rows="4"></textarea>
                </div>
                <div class="mb-3">
                    <label for="Medications_create" class="form-label">Medications:</label>
                    <textarea class="form-control" id="Medications_create" placeholder="Enter medications"></textarea>
                </div>
                <div class="mb-3">
                    <label for="Procedures_create" class="form-label">Procedures:</label>
                    <textarea class="form-control" id="Procedures_create" placeholder="Enter procedures"></textarea>
                </div>
                <div class="mb-3">
                    <label for="Comments_create" class="form-label">Comments:</label>
                    <textarea class="form-control" id="Comments_create" placeholder="Enter comments" rows="4"></textarea>
                </div>
                <!-- Add more health record fields as needed -->
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-secondary" onclick="createRecord()">Create Record</button>
      </div>
    </div>
  </div>
</div>

    <!-- Delete Record Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="deleteModalLabel">Delete a Patient</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <!-- Record Delete Form -->
            <form id="deleteRecordForm" class="mt-3">
                <div class="mb-3">
                    <label for="DeletePatientID" class="form-label">Patient ID:</label>
                    <input type="text" class="form-control" id="DeletePatientID" placeholder="Enter patient ID" required>
                </div>
                <div class="mb-3">
                    <label for="DeleteRecordID" class="form-label">Record ID:</label>
                    <input type="text" class="form-control" id="DeleteRecordID" placeholder="Enter record ID" required>
                </div>
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-secondary" onclick="deleteRecord()">Delete Record</button>
      </div>
    </div>
  </div>
</div>


</main>

<script>



</script>


<script src="/EHR_system/global/jquery.js"></script>
<script src="/EHR_system/js/doctor_health_records.js"></script>


</body>

</html>
