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
            if ($appointments){
                echo json_encode($appointments);
            } else{
                echo json_encode(["message" => "No appointments found"]);
            }
    
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
            if ($returned_value){
                // Encode the array as JSON and return it
                return json_encode($returned_value);
            } else {
                echo json_encode(["message" => "Server problems, comeback again"]);
            }

            
        } catch (PDOException $e) {
            // Handle the exception (e.g., log, display an error message)
            return json_encode(["error" => $e->getMessage()]);
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

            if ($returned_value){
                // Encode the array as JSON and return it
                return json_encode($returned_value);
            } else {
                echo json_encode(["message" => "Server problems, comeback again"]);
            }
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
            $validTables = ["Doctors", "clinics"]; // Add valid table names as needed

            if (!in_array($table, $validTables)) {
                throw new Exception("Invalid table name");
            }

            if ($table === "Doctors") {
                $statement = $dbo->conn->prepare("SELECT D.FirstName, D.LastName, D.Speciality, C.Name AS clinic FROM Doctors D
                LEFT JOIN DoctorClinic DC ON D.DoctorID = DC.DoctorID
                LEFT JOIN Clinics C ON DC.ClinicID = C.ClinicID");
            } else if ($table === "clinics"){
                $statement = $dbo->conn->prepare("SELECT * FROM $table");

            } 

            // Execute statement
            $statement->execute();

            // Fetch results
            $returned_value = $statement->fetchAll(PDO::FETCH_ASSOC);

            if ($returned_value){
                // Encode the array as JSON and return it
                return json_encode($returned_value);
            } else {
                echo json_encode(["message" => "Server problems, comeback again"]);
            }
        } catch (PDOException $e) {
            // Handle the exception (e.g., log, display an error message)
            return json_encode(["error" => $e->getMessage()]);
        } catch (Exception $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

}


class Users{

    public function create_user($dbo, $Username, $Password, $Email, $ContactNumber, $FirstName, $LastName, $gender, $birthdate, $speciality) {
        try {
            $checkUserQuery = "SELECT username FROM users WHERE Username = :Username";
    
            $UserExists = $dbo->conn->prepare($checkUserQuery);
            $UserExists->bindParam(':Username', $Username, PDO::PARAM_STR);
            // Execute statement
            $UserExists->execute();

            // Check the number of rows returned by the SELECT query
            if ($UserExists->rowCount() > 0) {
                // Return a custom error message for duplicate username
                return json_encode(["message" => "Username already exists"]);
            }


            $hashed_password = password_hash($Password, PASSWORD_DEFAULT);
            // Insert into users table
            $insertUserQuery = "INSERT INTO users (Username, Password, Email, ContactNumber) 
                VALUES (:Username, :hashed_password, :Email, :ContactNumber)";
    
            $statement = $dbo->conn->prepare($insertUserQuery);
            $statement->bindParam(':Username', $Username, PDO::PARAM_STR);
            $statement->bindParam(':hashed_password', $hashed_password, PDO::PARAM_STR);
            $statement->bindParam(':Email', $Email, PDO::PARAM_STR);
            $statement->bindParam(':ContactNumber', $ContactNumber, PDO::PARAM_STR);
    
            // Execute statement
            $statement->execute();
    
            // Get the UserID of the newly created user
            $userID = $dbo->conn->lastInsertId();
    
            // Insert into doctors table
            $insertDoctorQuery = "INSERT INTO doctors (FirstName, LastName, Speciality, UserID, gender, birthdate) 
                VALUES (:FirstName, :LastName, :Speciality, :UserID, :gender, :birthdate)";
    
            $statement = $dbo->conn->prepare($insertDoctorQuery);
            $statement->bindParam(':FirstName', $FirstName, PDO::PARAM_STR);
            $statement->bindParam(':LastName', $LastName, PDO::PARAM_STR);
            $statement->bindParam(':Speciality', $speciality, PDO::PARAM_STR);
            $statement->bindParam(':UserID', $userID, PDO::PARAM_INT);
            $statement->bindParam(':gender', $gender, PDO::PARAM_STR);
            $statement->bindParam(':birthdate', $birthdate, PDO::PARAM_STR);
    
            // Execute statement
            $statement->execute();
    
            // Return success message or any other information
            return json_encode(["success" => "Registration successful"]);
        } catch (PDOException $e) {
            // Check for unique constraint violation
            if ($e->getCode() == 23000 && strpos($e->getMessage(), 'unique_username') !== false) {
                // Return a custom error message for duplicate username
                return json_encode(["message" => "Username already exists"]);
            } else {
                // Handle other exceptions or return a generic error message
                return json_encode(["message" => "An error occurred during registration. Please try again."]);
            }
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
                        return json_encode(["success" => "Login successful", "UserID" => $user["UserID"], "username" => $user["Username"], "role" => $user["Role"]]);
                    } else {
                        // Incorrect password
                        return json_encode(["message" => "Invalid login credentials"]);
                    }
                } else {
                    // User not found
                    return json_encode(["message" => "Invalid login credentials"]);
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
                return json_encode(["message" => "Sender or receiver not found"]);
            }

            $statement = $dbo->conn->prepare(
                "INSERT INTO Messages (SenderID, ReceiverID, Content) VALUES (:senderID, :receiverID, :content)"
            );
            $statement->bindParam(':senderID', $senderID, PDO::PARAM_INT);
            $statement->bindParam(':receiverID', $receiverID, PDO::PARAM_INT);
            $statement->bindParam(':content', $content, PDO::PARAM_STR);

            $result = $statement->execute();

            if ($result) {
                return json_encode(["success" => "Message sent successfully to {$receiver['FirstName']} {$receiver['LastName']}"]);
            } else {
                return json_encode(["message" => "Failed to send the message"]);
            }
        } catch (PDOException $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    // Helper method to check if a user exists in the Users table
    private function userExists($dbo, $userID)
    {
        $statement = $dbo->conn->prepare(
            "SELECT u.UserID, d.FirstName, d.LastName FROM Users u
            JOIN doctors d ON d.UserID = u.UserID
            WHERE u.UserID = :userID");
        $statement->bindParam(':userID', $userID, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }


    public function get_messages($dbo, $userID)
    {
        try {
            // Check if the user ID exists in the Users table
            if (!$this->userExists($dbo, $userID)) {
                return json_encode(["message" => "User not found"]);
            }
            
            $statement = $dbo->conn->prepare(
                "SELECT m.Content, m.Timestamp, d.FirstName, d.LastName FROM messages m JOIN doctors d ON m.SenderID = d.UserID WHERE m.ReceiverID = :userID"
            );
            $statement->bindParam(':userID', $userID, PDO::PARAM_INT);
            $statement->execute();
            $messages = $statement->fetchAll(PDO::FETCH_ASSOC);

            if ($messages) {
                return json_encode($messages);
            } else {
                return json_encode(["message" => "No messages found"]);
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
                return json_encode(["message" => "Doctor not found"]);
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
                return json_encode(["message" => "Failed to add patient"]);
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
                return json_encode(["message" => "Doctor not found"]);
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
                return json_encode(["message" => "No patients found"]);
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
                return json_encode(["message" => "Doctor not found"]);
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
                    return json_encode(["message" => "Invalid search parameter"]);
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
                return json_encode(["message" => "No patients found"]);
            }
        } catch (PDOException $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
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
                return json_encode(["success" => "Patient updated successfully"]);
            } else {
                $errorInfo = $statement->errorInfo();
                return json_encode(["message" => "Failed to update the patient. Error: " . $errorInfo[2]]);
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
                return json_encode(["message" => "Failed to delete the patient"]);
            }
        } catch (PDOException $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    

}



?>