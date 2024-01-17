<?php
require_once "database.php";

class Appointmentsinfo
{
    
    public function get_appointments($dbo, $DoctorID)
    {
        try {
            
            // Use placeholders in the SQL query
            $statement = $dbo->conn->prepare("SELECT a.AppointmentID as AppointmentID, a.Timeslot as Timeslot, a.AppointmentDate as AppointmentDate, p.PatientID as PatientID, p.FirstName as PatientFirstName, p.LastName as PatientLastName 
            FROM appointments a
            JOIN patients p ON a.PatientID = p.PatientID
            WHERE a.DoctorID = :DoctorID
            ");

            // Bind parameters
            $statement->bindParam(':DoctorID', $DoctorID, PDO::PARAM_INT);
            
            // Execute statement
            $statement->execute();
            // Fetch results
            $appointments = $statement->fetchAll(PDO::FETCH_ASSOC);
    
            echo json_encode($appointments);
    
        } catch (PDOException $e) {
            // Handle exceptions, log errors, or return an error message
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
    




}



class Doctorsinfo
{
    public function get_doctors_info($dbo, $speciality, $clinic)
    {
        try {
            if ($clinic === "") {
                $statement = $dbo->conn->prepare("SELECT d.FirstName, d.LastName, d.Speciality, c.Name as clinic FROM Doctors d
                JOIN doctorclinic dc ON dc.DoctorID = d.DoctorID
                JOIN clinics c ON c.ClinicID = dc.ClinicID
                WHERE d.Speciality = :speciality");

            } else if ($speciality == "") {
                $statement = $dbo->conn->prepare("SELECT d.FirstName, d.LastName, d.Speciality, c.Name as clinic FROM Doctors d
                JOIN doctorclinic dc ON dc.DoctorID = d.DoctorID
                JOIN clinics c ON c.ClinicID = dc.ClinicID
                WHERE c.Name = :clinic");

            } else if ($speciality !== "" && $clinic !==  "") {
                $statement = $dbo->conn->prepare("select d.FirstName, d.LastName, d.Speciality, c.Name as clinic from Doctors d
                join doctorclinic dc on dc.DoctorID = d.DoctorID
                join clinics c on c.clinicID = dc.clinicID
                where c.Name = :clinic AND d.Speciality = :speciality");
            } 

            // Conditionally bind parameters
            if ($speciality !== "") {
                $statement->bindParam(':speciality', $speciality, PDO::PARAM_STR);
            }
            if ($clinic !== "") {
                $statement->bindParam(':clinic', $clinic, PDO::PARAM_STR);
            }

            // Execute statement
            $statement->execute();

            // Fetch results
            $returned_value = $statement->fetchAll(PDO::FETCH_ASSOC);

            // Encode the array as JSON and return it
            return json_encode($returned_value);
        } catch (PDOException $e) {
            // Handle the exception (e.g., log, display an error message)
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    public function check_available_timeslots($dbo, $DoctorID, $ClinicID, $speciality)
    {
        try {
            // Use placeholders in the SQL query
            $statement = $dbo->conn->prepare("SELECT SlotID, DoctorID, ClinicID, DATE, StartTime FROM TimeSlots
              WHERE DoctorID = :DoctorID
              AND speciality = :speciality
              AND ClinicID = :ClinicID
              AND AvailabilityStatus = 'Available'");

            // Bind parameters
            $statement->bindParam(':DoctorID', $DoctorID, PDO::PARAM_INT); 
            $statement->bindParam(':speciality', $speciality, PDO::PARAM_STR);
            $statement->bindParam(':ClinicID', $ClinicID, PDO::PARAM_INT); 
    
            // Execute statement
            $statement->execute();

           // Fetch results
           $returned_value = $statement->fetchAll(PDO::FETCH_ASSOC);

           // Encode the array as JSON and return it
           return json_encode($returned_value);
        } catch (PDOException $e) {
            // Handle exceptions, log errors, or return an error message
            echo json_encode(["error" => "Error inserting appointment: " . $e->getMessage()]);
        }
    }

}


class Clinicsinfo
{
    public function get_clinic_info($dbo, $speciality)
    {
        try {
            if ($speciality !== "") {
                $statement = $dbo->conn->prepare("SELECT c.Name, c.Location FROM clinics c
                JOIN clinicspecialities cs ON cs.ClinicID = c.ClinicID
                JOIN specialities s ON s.SpecialityID = cs.SpecialityID
                WHERE s.name = :speciality;"
                );
            } 

            // Conditionally bind parameters
            
            $statement->bindParam(':speciality', $speciality, PDO::PARAM_STR);
            
           
            // Execute statement
            $statement->execute();

            // Fetch results
            $returned_value = $statement->fetchAll(PDO::FETCH_ASSOC);

            // Encode the array as JSON and return it
            return json_encode($returned_value);
        } catch (PDOException $e) {
            // Handle the exception (e.g., log, display an error message)
            return json_encode(["error" => $e->getMessage()]);
        }
    }

}

class All_Info
{
        public function get_all_info($dbo, $table)
    {
        try {
            // Ensure that $table is a valid table name (to avoid SQL injection)
            $validTables = ["Doctors", "appointmentsinfo", "clinics"]; // Add valid table names as needed

            if (!in_array($table, $validTables)) {
                throw new Exception("Invalid table name");
            }

            if ($table === "Doctors") {
                $statement = $dbo->conn->prepare("SELECT D.FirstName, D.LastName, D.Speciality, C.Name AS clinic FROM Doctors D
                LEFT JOIN DoctorClinic DC ON D.DoctorID = DC.DoctorID
                LEFT JOIN Clinics C ON DC.ClinicID = C.ClinicID");
            } else {
                $statement = $dbo->conn->prepare("SELECT * FROM $table");

            } 

            // Execute statement
            $statement->execute();

            // Fetch results
            $returned_value = $statement->fetchAll(PDO::FETCH_ASSOC);

            // Encode the array as JSON and return it
            return json_encode($returned_value);
        } catch (PDOException $e) {
            // Handle the exception (e.g., log, display an error message)
            return json_encode(["error" => $e->getMessage()]);
        } catch (Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

}


class DoctorScheduler {
    
        public function createDoctorSchedule($dbo, $DoctorID, $ClinicID, $startDate, $endDate, $workingDays, $startTime, $endTime, $speciality)
    {
        try {
            $interval = new DateInterval('PT30M'); // 30 minutes interval

            $currentDate = clone $startDate;

            while ($currentDate <= $endDate) {
                $currentDayOfWeek = $currentDate->format('N'); // 1 (Monday) to 7 (Sunday)

                // Check if the current day is a working day (Monday) (Wednesday) (Friday)
                if (in_array($currentDayOfWeek, $workingDays)) {
                    $currentTime = clone $currentDate;
                    $currentTime->setTime($startTime->format('H'), $startTime->format('i'));

                    // Use a separate clone for setting the end time
                    $endTimeClone = clone $currentDate;
                    $endTimeClone->setTime($endTime->format('H'), $endTime->format('i'));

                    while ($currentTime < $endTimeClone) {
                        $currentDateTime = $currentTime->format('Y-m-d H:i:s');
                        $endTimeDateTime = $currentTime->add($interval)->format('Y-m-d H:i:s');

                        // Insert time slot with default availability
                        $insertStatement = $dbo->conn->prepare("INSERT INTO TimeSlots (DoctorID, ClinicID, Date, StartTime, EndTime, AvailabilityStatus, speciality) VALUES (:DoctorID, :ClinicID, :Date, :StartTime, :EndTime, 'Available', :speciality)");
                        $insertStatement->bindParam(':DoctorID', $DoctorID, PDO::PARAM_INT);
                        $insertStatement->bindParam(':ClinicID', $ClinicID, PDO::PARAM_INT);
                        $insertStatement->bindParam(':Date', $currentDateTime, PDO::PARAM_STR);
                        $insertStatement->bindParam(':StartTime', $currentDateTime, PDO::PARAM_STR);
                        $insertStatement->bindParam(':EndTime', $endTimeDateTime, PDO::PARAM_STR);
                        $insertStatement->bindParam(':speciality', $speciality, PDO::PARAM_STR);
                        $insertStatement->execute();
                    }
                }

                $currentDate->add(new DateInterval('P1D')); // Move to the next day
            }

            return json_encode(["message" => "Doctor schedule created successfully"]);
        } catch (PDOException $e) {
            return json_encode(["error" => "Error creating doctor schedule: " . $e->getMessage()]);
        } catch (Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

}


class Users{

        public function create_user($dbo, $Username, $Password, $Email, $ContactNumber, $FirstName, $LastName, $gender, $birthdate) {
        try {
            // Check if any of the required values are null
            if (empty($Username) || empty($Password) || empty($Email) || empty($ContactNumber)) {
                return json_encode(["error" => "All required fields must be provided."]);
            }

            $hashed_password = password_hash($Password, PASSWORD_DEFAULT);
            $statement = $dbo->conn->prepare("INSERT INTO users (Username, Password, Email, ContactNumber) 
                VALUES (:Username, :hashed_password, :Email, :ContactNumber)");

            $statement->bindParam(':Username', $Username, PDO::PARAM_STR);
            $statement->bindParam(':hashed_password', $hashed_password, PDO::PARAM_STR);
            $statement->bindParam(':Email', $Email, PDO::PARAM_STR);
            $statement->bindParam(':ContactNumber', $ContactNumber, PDO::PARAM_STR);

            // Execute statement
            $statement->execute();

            // Get the UserID of the newly created user
            $userID = $dbo->conn->lastInsertId();

            // Return success message or any other information
            return json_encode(["message" => "Registration successful,", "patient" => $patients]);
        } catch (PDOException $e) {
            // Check for unique constraint violation
            if ($e->getCode() == 23000 && strpos($e->getMessage(), 'unique_username') !== false) {
                // Return a custom error message for duplicate username
                return json_encode(["error" => "Username already exists. Please choose a different username."]);
            } else {
                // Handle other exceptions or return a generic error message
                return json_encode(["error" => "An error occurred during registration. Please try again."]);
            }
        }
    }

    


        public function check_user($dbo, $UsernameOrEmail) {
            try {
                
                // Check if the username or email exists
                $checkUserStatement = $dbo->conn->prepare("SELECT UserID FROM users WHERE Username = :UsernameOrEmail OR Email = :UsernameOrEmail");
                $checkUserStatement->bindParam(':UsernameOrEmail', $UsernameOrEmail, PDO::PARAM_STR);
                $checkUserStatement->execute();
        
                $user = $checkUserStatement->fetch(PDO::FETCH_ASSOC);
        
                return json_encode($user);

            } catch (PDOException $e) {
                // Handle the exception (e.g., log, display an error message)
                return json_encode(["error" => $e->getMessage()]);
            }
        }

        public function login($dbo, $UsernameOrEmail, $password) {
            try {
                // Check if the username or email exists
                $checkUserStatement = $dbo->conn->prepare("SELECT * FROM users WHERE Username = :UsernameOrEmail OR Email = :UsernameOrEmail");
                $checkUserStatement->bindParam(':UsernameOrEmail', $UsernameOrEmail, PDO::PARAM_STR);
                $checkUserStatement->execute();
        
                $user = $checkUserStatement->fetch(PDO::FETCH_ASSOC);
        
                if ($user) {
                    // User exists, now verify the password
                    $hashed_password = $user['Password'];
        
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, user authenticated successfully
                        return json_encode(["message" => "Login successful", "UserID" => $user["UserID"], "username" => $user["Username"], "role" => $user["Role"]]);
                    } else {
                        // Incorrect password
                        return json_encode(["error" => "Invalid login credentials"]);
                    }
                } else {
                    // User not found
                    return json_encode(["error" => "Invalid login credentials"]);
                }
            } catch (PDOException $e) {
                // Handle the exception (e.g., log, display an error message)
                return json_encode(["error" => $e->getMessage()]);
            }
        }
        

}

class History{

        public function medication($dbo, $UserID)
    {
        try {
            // Check if the username or email exists
            $statement = $dbo->conn->prepare("SELECT * FROM medicationprescriptions WHERE UserID = :UserID");
            $statement->bindParam(':UserID', $UserID, PDO::PARAM_STR);
            $statement->execute();

            $medications = $statement->fetchAll(PDO::FETCH_ASSOC);

            if ($medications) {
                return json_encode($medications);
            } else {
                // No medications found
                return json_encode(["message" => "No medications found for the user"]);
            }
        } catch (PDOException $e) {
            // Handle the exception (e.g., log, display an error message)
            return json_encode(["error" => $e->getMessage()]);
        }
    }


}



class Messages
{
        public function send_message($dbo, $senderID, $receiverID, $content)
    {
        try {
            
            $sender = $this->userExists($dbo, $senderID);
            $receiver = $this->userExists($dbo, $receiverID); // Add a semicolon at the end

            // Check if sender and receiver IDs exist in the Users table
            if (!$sender || !$receiver) {
                return json_encode(["error" => "Sender or receiver not found", "UserID" =>$sender, "DoctorID" =>$receiver]);
            }

            $statement = $dbo->conn->prepare(
                "INSERT INTO Messages (SenderID, ReceiverID, Content) VALUES (:senderID, :receiverID, :content)"
            );
            $statement->bindParam(':senderID', $senderID, PDO::PARAM_INT);
            $statement->bindParam(':receiverID', $receiverID, PDO::PARAM_INT);
            $statement->bindParam(':content', $content, PDO::PARAM_STR);

            $result = $statement->execute();

            if ($result) {
                return json_encode(["success" => "Message sent successfully to {$receiver['Username']}"]);
            } else {
                return json_encode(["error" => "Failed to send the message"]);
            }
        } catch (PDOException $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    // Helper method to check if a user exists in the Users table
    private function userExists($dbo, $userID)
    {
        $statement = $dbo->conn->prepare("SELECT UserID, Username FROM Users WHERE UserID = :userID");
        $statement->bindParam(':userID', $userID, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }


    public function get_messages($dbo, $userID, $action)
    {
        try {
            // Check if the user ID exists in the Users table
            if (!$this->userExists($dbo, $userID)) {
                return json_encode(["error" => "User not found"]);
            }
            if ($action == "user"){

            $statement = $dbo->conn->prepare(
                "SELECT m.Content, m.Timestamp, d.FirstName, d.LastName FROM Messages m JOIN Doctors d ON m.SenderID = d.UserID WHERE m.ReceiverID = :userID"
            );
            $statement->bindParam(':userID', $userID, PDO::PARAM_INT);
            } elseif ($action == "doctor"){
                $statement = $dbo->conn->prepare(
                    "SELECT m.Content, m.Timestamp, u.username FROM Messages m JOIN Users u ON m.SenderID = u.UserID WHERE m.ReceiverID = :userID"
                );
                $statement->bindParam(':userID', $userID, PDO::PARAM_INT);
            }

            $statement->execute();

            $messages = $statement->fetchAll(PDO::FETCH_ASSOC);

    

            if ($messages) {
                return json_encode($messages);
            } else {
                return json_encode(["message" => "No messages found for the user"]);
            }
        } catch (PDOException $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }
}


class Patients

{
    public function create_patient($dbo, $doctorID, $firstName, $lastName, $email, $birthdate, $gender, $address, $contactNumber)
    {
        try {
            // Check if doctorID exists in the Doctors table
            $doctorExists = $this->doctorExists($dbo, $doctorID);
            if (!$doctorExists) {
                return json_encode(["error" => "Doctor not found"]);
            }

            $statement = $dbo->conn->prepare(
                "INSERT INTO Patients (DoctorID, FirstName, LastName, Email, Birthdate, Gender, Address, ContactNumber) 
                VALUES (:doctorID, :firstName, :lastName, :email, :birthdate, :gender, :address, :contactNumber)"
            );
            $statement->bindParam(':doctorID', $doctorID, PDO::PARAM_INT);
            $statement->bindParam(':firstName', $firstName, PDO::PARAM_STR);
            $statement->bindParam(':lastName', $lastName, PDO::PARAM_STR);
            $statement->bindParam(':email', $email, PDO::PARAM_STR);
            $statement->bindParam(':birthdate', $birthdate, PDO::PARAM_STR);
            $statement->bindParam(':gender', $gender, PDO::PARAM_STR);
            $statement->bindParam(':address', $address, PDO::PARAM_STR);
            $statement->bindParam(':contactNumber', $contactNumber, PDO::PARAM_STR);

            $result = $statement->execute();

            if ($result) {
                return json_encode(["success" => "Patient added successfully"]);
            } else {
                return json_encode(["error" => "Failed to add patient"]);
            }
        } catch (PDOException $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }
    


    public function get_all_patients($dbo, $doctorID)
    {
        try {
            // Check if the doctor ID exists in the Doctors table
            if (!$this->doctorExists($dbo, $doctorID)) {
                return json_encode(["error" => "Doctor not found"]);
            }

            $statement = $dbo->conn->prepare(
                "SELECT * FROM Patients WHERE DoctorID = :doctorID"
            );
            $statement->bindParam(':doctorID', $doctorID, PDO::PARAM_INT);
            $statement->execute();

            $patients = $statement->fetchAll(PDO::FETCH_ASSOC);

            if ($patients) {
                return json_encode($patients);
            } else {
                return json_encode(["message" => "No patients found for the doctor"]);
            }
        } catch (PDOException $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    public function search_patients($dbo, $doctorID, $parameter, $input)
    {
        try {
            // Check if the doctor ID exists in the Doctors table
            if (!$this->doctorExists($dbo, $doctorID)) {
                return json_encode(["error" => "Doctor not found"]);
            }
            $ID = False;
            $firstorlastname=False;
            // Construct the SQL query dynamically based on the selected parameter
            $query = "SELECT * FROM Patients WHERE DoctorID = :doctorID AND (";
            switch ($parameter) {
                case 'FirstName':
                    $query .= "FirstName LIKE :searchParameter OR LastName LIKE :searchParameter";
                    $firstorlastname=True;
                    break;

                case 'name':
                    $nameArray = explode(' ', $input);
                    // Check if the array has at least two elements
                    if (count($nameArray) >= 2) {
                        // Now $nameArray will contain two elements, the first and last name
                        $FirstName = $nameArray[0];
                        $LastName = $nameArray[1];
                        $query .= "FirstName LIKE :searchParameterFirstName AND LastName LIKE :searchParameterLastName";
                    }
                    break;

                case 'contactNumber':
                    $query .= "ContactNumber LIKE :searchParameter";
                    break;
                case 'email':
                    $query .= "Email LIKE :searchParameter";
                    break;
                case 'PatientID':
                    $query .= "PatientID = :searchParameter";
                    $ID = True;
                    break;
                default:
                    return json_encode(["error" => "Invalid search parameter"]);
            }
            $query .= ")";

            // Prepare and execute the SQL query
            $statement = $dbo->conn->prepare($query);

            $statement->bindParam(':doctorID', $doctorID, PDO::PARAM_INT);
            if (isset($FirstName) && isset($LastName)) {
                $searchParameterFirstName =  '%' . $FirstName . '%';
                $searchParameterLastName = '%' . $LastName . '%';
                $statement->bindParam(':searchParameterFirstName', $searchParameterFirstName, PDO::PARAM_STR);
                $statement->bindParam(':searchParameterLastName', $searchParameterLastName, PDO::PARAM_STR);
            
            } elseif ($firstorlastname){
                $searchParameter = '%' . $input;
                $statement->bindParam(':searchParameter', $searchParameter, PDO::PARAM_STR);

            }elseif ($ID){
                $searchParameter = $input;
                $statement->bindParam(':searchParameter', $searchParameter, PDO::PARAM_STR);

            } else {
                // Dynamically bind the search parameter based on the selected parameter
                $searchParameter = '%' . $input . '%';
                $statement->bindParam(':searchParameter', $searchParameter, PDO::PARAM_STR);
            }
            
            $statement->execute();
            $patients = $statement->fetchAll(PDO::FETCH_ASSOC);

            if ($patients) {
                return json_encode($patients);
            } else {
                return json_encode(["message" => "No patients found for the doctor"]);
            }
        } catch (PDOException $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    

    // Helper method to check if a doctor exists in the Doctors table
    private function patientExists($dbo, $Email, $ContactNumber)
    {
        $statement = $dbo->conn->prepare("SELECT PatientID, UserID FROM patients WHERE Email = :Email and ContactNumber = :ContactNumber");
        $statement->bindParam(':Email', $Email, PDO::PARAM_STR);
        $statement->bindParam(':ContactNumber', $ContactNumber, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result !== false;
    }
    // Helper method to check if a doctor exists in the Doctors table
    private function doctorExists($dbo, $doctorID)
    {
        $statement = $dbo->conn->prepare("SELECT DoctorID FROM Doctors WHERE DoctorID = :doctorID");
        $statement->bindParam(':doctorID', $doctorID, PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result !== false;
    }

    public function update_patient($dbo, $patientID, $newData)
    {
        try {
            
            // Construct the UPDATE query based on the provided data
            $updateQuery = "UPDATE patients SET ";
            foreach ($newData as $key => $value) {
                $updateQuery .= "$key = :$key, ";
            }
            $updateQuery = rtrim($updateQuery, ", "); // Remove the trailing comma and space
            $updateQuery .= " WHERE PatientID = :patientID";

            $statement = $dbo->conn->prepare($updateQuery);

            // Bind parameters
            $statement->bindParam(':patientID', $patientID, PDO::PARAM_INT);
            foreach ($newData as $key => &$value) {
                // Use bindValue to bind the actual variable, not just its value
                $statement->bindValue(":$key", $value);
            }

            $result = $statement->execute();
            if ($result) {
                return json_encode(["success" => "Patient updated successfully", "return" => $result]);
            } else {
                $errorInfo = $statement->errorInfo();
                return json_encode(["error" => "Failed to update the patient. Error: " . $errorInfo[2]]);
            }

        } catch (PDOException $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }


    public function delete_patient($dbo, $patientID, $doctorID)
    {
        try {
            // Check if the doctor ID exists in the Doctors table
            if (!$this->doctorExists($dbo, $doctorID)) {
                return json_encode(["error" => "Doctor not found"]);
            }

            $statement = $dbo->conn->prepare(
                "DELETE FROM Patients WHERE PatientID = :patientID"
            );
            $statement->bindParam(':patientID', $patientID, PDO::PARAM_INT);
            $result = $statement->execute();

            if ($result) {
                return json_encode(["success" => "Patient deleted successfully"]);
            } else {
                return json_encode(["error" => "Failed to delete the patient"]);
            }
        } catch (PDOException $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    

}



?>