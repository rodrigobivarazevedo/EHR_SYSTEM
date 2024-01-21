<?php
session_start();

$root = $_SERVER["DOCUMENT_ROOT"];
include_once $root . "/EHR_system/db/database.php";
include_once $root . "/EHR_system/db/backend.php";

$action = $_POST["action"];
$UserID = $_SESSION["UserID"];

// Create an instance of the Records class
$pdo = new Users();
$dbo = new Database();

$statement = $dbo->conn->prepare(
    "SELECT DoctorID FROM doctors WHERE UserID = :UserID"
);
$statement->bindParam(':UserID', $UserID, PDO::PARAM_INT);
$statement->execute();

$Doctor = $statement->fetch(PDO::FETCH_ASSOC);

if (!$Doctor) {
    echo json_encode(["message" => "Access denied"]);
    exit(); // Terminate script execution after sending the response
}

// Test get_patients function
$doctorID = $Doctor["DoctorID"];

if ($action === "get_personal_details") {
    
    $result = $pdo->get_user_info($dbo, $UserID);

    // Check if the result is an error
    if (isset($result["error"])) {
        // Handle the error, for example, send an appropriate response to the client
        echo json_encode($result);
    } else {
        echo $result;
    }
    exit();
}

if ($action === "update_personal_details") {
   
    $email = $_POST["email"];
    $username = $_POST["username"];
    $contact = $_POST["contact"];
  
    $result = $pdo->update_user($dbo, $UserID, $email, $username, $contact);

    // Check if the result is an error
    if (isset($result["error"])) {
        // Handle the error, for example, send an appropriate response to the client
        echo json_encode($result);
    } else {
        echo $result;
    }
    exit();
}


if ($action === "update_password") {

    $PatientID = $_POST["PatientID"];
    $RecordID = $_POST["RecordID"];
    $newData = [
        'DateRecorded' => $_POST["DateRecorded"],
        'diagnosis' => $_POST["Diagnosis"],
        'medications' => $_POST["Medications"],
        'procedures' => $_POST["Procedures"],
        'comments' => $_POST["Comments"],
    
    ];

    $statement = $dbo->conn->prepare(
        "SELECT DoctorID FROM patients WHERE PatientID = :PatientID"
    );
    $statement->bindParam(':PatientID', $PatientID, PDO::PARAM_INT);
    $statement->execute();
    
    $isDoctorPatient = $statement->fetch(PDO::FETCH_ASSOC);
    
    if (!$isDoctorPatient) {
        echo json_encode(["message" => "Access denied, patient not yours"]);
        exit(); // Terminate script execution after sending the response
    }
   
    $result = $pdo->update_health_record($dbo, $RecordID, $newData);

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