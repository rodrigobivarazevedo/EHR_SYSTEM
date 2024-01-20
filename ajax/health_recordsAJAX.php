<?php
session_start();

$root = $_SERVER["DOCUMENT_ROOT"];
include_once $root . "/EHR_system/db/database.php";
include_once $root . "/EHR_system/db/backend.php";

$action = $_POST["action"];
$UserID = $_SESSION["UserID"];

// Create an instance of the Records class
$pdo = new Records();
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


if ($action === "create_health_record") {
   
    $PatientID = $_POST["PatientID"];
    $diagnosis = $_POST["Diagnosis"];
    $medications = $_POST["Medications"];
    $procedures = $_POST["Procedures"];
    $comments = $_POST["Comments"];
    
    $DateRecorded = date("Y-m-d"); // Format: YYYY-MM-DD

    $result = $pdo->create_health_record($dbo, $PatientID, $doctorID, $DateRecorded, $diagnosis, $medications, $procedures, $comments);

    // Check if the result is an error
    if (isset($result["error"])) {
        // Handle the error, for example, send an appropriate response to the client
        echo json_encode($result);
    } else {
        echo $result;
    }
    exit();
}

if ($action === "update_health_record") {

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

if ($action === "delete_health_record") {
    
    $recordID = $_POST["RecordID"]; // Assuming the parameter is named recordID
    $PatientID = $_POST["PatientID"];

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

    $result = $pdo->delete_health_record($dbo, $recordID);

    // Check if the result is an error
    if (isset($result["error"])) {
        // Handle the error, for example, send an appropriate response to the client
        echo json_encode($result);
    } else {
        echo $result;
    }
    exit();
}

if ($action === "search_health_records") {
    
    $parameter = $_POST["parameter"];
    $input = $_POST["InputValue"];

    if ($parameter == "RecordID"){
        $result = $pdo->get_health_record($dbo, $input, $doctorID);
    } else if ($parameter == "PatientID"){
        $result = $pdo->get_all_health_records($dbo, $doctorID, $input);
    }
 
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
