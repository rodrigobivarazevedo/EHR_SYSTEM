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


$pdo = new Records();
$dbo = new Database();

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


if ($action === "create_health_record") {
   
    $PatientID = $_POST["PatientID"];
    $diagnosis = $_POST["Diagnosis"];
    $medications = $_POST["Medications"];
    $procedures = $_POST["Procedures"];
    $comments = $_POST["Comments"];
    
    $DateRecorded = date("Y-m-d"); // Format: YYYY-MM-DD

    $result = $pdo->create_health_record($dbo, $PatientID, $DoctorID, $DateRecorded, $diagnosis, $medications, $procedures, $comments);

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
    
    $recordID = $_POST["RecordID"]; 
    $PatientID = $_POST["PatientID"];
    
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
    
    $parameter = $_GET["parameter"];
    $input = $_GET["InputValue"];

    if ($parameter == "RecordID"){
        $result = $pdo->get_health_record($dbo, $input, $DoctorID);

    } else if ($parameter == "PatientID"){
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
        $result = $pdo->get_all_health_records($dbo, $DoctorID, $input);
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
