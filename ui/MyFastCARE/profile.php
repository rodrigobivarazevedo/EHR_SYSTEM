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
                <a href="patients.php" class="nav-link text-white">
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
                <a href="profile.php" class="nav-link text-secondary">
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
            <!-- Sign Out Button -->
            <button type="button" onclick="confirmSignOut();" class="btn btn-light text-dark me-2">Sign Out</button>
            </div>
        </div>
    </header>


    <div class="container mt-3">
        <h2>Account Details</h2>
    </br>
        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" id="accountTabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#personalDataTab">Personal Data</a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#securityTab">Security</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#dataProtectionTab">Data Protection</a>
            </li>
        </ul>
        
        <div class="tab-content">
            <!-- Personal Data Tab Content -->
            <div id="personalDataTab" class="tab-pane fade show active mt-3">
            
                <div class="album py-5 bg-light">

                        <div class="col-md-5">
                        
                            <div class="container p-4">
                            <h3 class="mb-4" id="editTitle">Personal Data</h3>
                                    <!-- Patient Creation Form -->
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
                                            <label for="patientAddress_update" class="form-label">Address:</label>
                                            <input type="text" class="form-control" id="patientAddress_update" placeholder="Enter patient address">
                                        </div>
                                        <div class="mb-3">
                                            <label for="patientContactNumber_update" class="form-label">Contact Number:</label>
                                            <input type="tel" class="form-control" id="patientContactNumber_update" placeholder="Enter contact number">
                                        </div>
                                    </form>
                                    <button type="button" class="btn btn-primary mt-3" onclick="update_patient()">Update Details</button>
                            </div>
                        </div>
                </div> 
            </div>
            
       
            <!-- Security Tab Content -->
            <div id="securityTab" class="tab-pane fade mt-3">
            
                <div class="album py-5 bg-light">

                    <div class="col-md-5">

                        <div class="container p-4">
                        <h3 class="mb-4" id="editTitle">Login Data</h3>
                                <!-- Patient Creation Form -->
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
                                        <label for="patientAddress_update" class="form-label">Address:</label>
                                        <input type="text" class="form-control" id="patientAddress_update" placeholder="Enter patient address">
                                    </div>
                                    <div class="mb-3">
                                        <label for="patientContactNumber_update" class="form-label">Contact Number:</label>
                                        <input type="tel" class="form-control" id="patientContactNumber_update" placeholder="Enter contact number">
                                    </div>
                                </form>
                                <button type="button" class="btn btn-primary mt-3" onclick="update_patient()">Update Details</button>
                        </div>
                    </div>
                </div> 
                
            </div>
            
            <!-- Data Protection Tab Content -->
            <div id="dataProtectionTab" class="tab-pane fade mt-3">
                <form>
                    <!-- Data protection fields here -->
                </form>
            
                <p>Privacy policy: [Privacy policy link]</p>
                <p>Preferences: [Preferences information]</p>
            </div>
        </div>
    </div>

    
    

    



  <!-- JavaScript links for bootstrap functions -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="/EHR_system/js/account.js"></script>
  <script src="/EHR_system/global/jquery.js"></script>
  
</body>

</html>