<?php
session_start();

$root = $_SERVER["DOCUMENT_ROOT"];
include_once $root . "/EHR_system/db/database.php";
include_once $root . "/EHR_system/db/backend.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST["action"];
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET["action"];
}

$UserID = $_SESSION["UserID"];
$dbo = new Database();
$patients = new Patients();

$statement = $dbo->conn->prepare(
    "SELECT DoctorID FROM doctors WHERE UserID = :UserID"
);
$statement->bindParam(':UserID', $UserID, PDO::PARAM_INT);
$statement->execute();

$Doctor = $statement->fetch(PDO::FETCH_ASSOC);

if (!$Doctor["DoctorID"]) {
    echo json_encode(["message" => "Access denied"]);
    exit(); // Terminate script execution after sending the response
}

$DoctorID = $Doctor["DoctorID"];

if ($action === "create_patient") {
    

    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $birthdate = $_POST["birthdate"];
    $gender = $_POST["gender"];
    $address = $_POST["address"];
    $contactNumber = $_POST["contactNumber"];
    $smoker = $_POST["smoker"];

    $result = $patients->create_patient($dbo, $DoctorID, $firstName, $lastName, $email, $birthdate, $gender, $address, $contactNumber, $smoker);

    // Check if the result is an error
    if (isset($result["error"])) {
        // Handle the error, for example, send an appropriate response to the client
        echo json_encode($result);
    } else {
        echo $result;
    }
    exit();
}


if ($action === "update_patient") {
    
    $PatientID = $_POST["PatientID"];
    $newData = [
        'FirstName' => $_POST["firstName"],
        'LastName' => $_POST["lastName"],
        'Email' => $_POST["email"],
        'Birthdate' => $_POST["birthdate"],
        'Gender' => $_POST["gender"],
        'Address' => $_POST["address"],
        'ContactNumber' => $_POST["contactNumber"],
        'Smoker' => $_POST["smoker"],
    ];
    

    $statement = $dbo->conn->prepare(
        "SELECT DoctorID FROM patients WHERE PatientID = :patientID"
    );
    $statement->bindParam(':patientID', $PatientID, PDO::PARAM_INT);
    $statement->execute();
    
    $Patient_doctor = $statement->fetch(PDO::FETCH_ASSOC);
    $Patient_doctorID = $Patient_doctor["DoctorID"];
    if ($DoctorID !== $Patient_doctorID) {
        echo json_encode(["message" => "Patient is not yours"]);
        exit(); // Terminate script execution after sending the response
    }

    $result = $patients->update_patient($dbo, $PatientID, $newData);

    // Check if the result is an error
    if (isset($result["error"])) {
        // Handle the error, for example, send an appropriate response to the client
        echo json_encode($result);
    } else {
        echo $result;
    }
    exit();
}

if ($action === "delete_patient") {
    
    $PatientID = $_POST["patientID"];

    $statement = $dbo->conn->prepare(
        "SELECT DoctorID FROM patients WHERE PatientID = :patientID"
    );
    $statement->bindParam(':patientID', $PatientID, PDO::PARAM_INT);
    $statement->execute();
    
    $Patient_doctor = $statement->fetch(PDO::FETCH_ASSOC);
    $Patient_doctorID = $Patient_doctor["DoctorID"];
    if ($DoctorID !== $Patient_doctorID) {
        echo json_encode(["message" => "Patient is not yours"]);
        exit(); // Terminate script execution after sending the response
    }

    $result = $patients->delete_patient($dbo, $PatientID, $DoctorID);

    // Check if the result is an error
    if (isset($result["error"])) {
        // Handle the error, for example, send an appropriate response to the client
        echo json_encode($result);
    } else {
        echo $result;
    }
    exit();
}



if ($action === "search_patients") {
    
    $parameter = $_GET["parameter"];
    $input = $_GET["searchQueryInputValue"];

    if ($parameter == "PatientID"){
        $statement = $dbo->conn->prepare(
            "SELECT DoctorID FROM patients WHERE PatientID = :patientID"
        );
        $statement->bindParam(':patientID', $input, PDO::PARAM_INT);
        $statement->execute();
        
        $Patient_doctor = $statement->fetch(PDO::FETCH_ASSOC);
        $Patient_doctorID = $Patient_doctor["DoctorID"];
        if ($DoctorID !== $Patient_doctorID) {
            echo json_encode(["message" => "Patient is not yours"]);
            exit(); // Terminate script execution after sending the response
        }
    }

    $result = $patients->search_patients($dbo, $DoctorID, $parameter, $input);

    // Check if the result is an error
    if (isset($result["error"])) {
        // Handle the error, for example, send an appropriate response to the client
        echo json_encode($result);
    } else {
        echo $result;
    }
    exit();
}


?>