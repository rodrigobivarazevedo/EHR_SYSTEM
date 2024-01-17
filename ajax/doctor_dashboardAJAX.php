<?php
session_start();

$root = $_SERVER["DOCUMENT_ROOT"];
include_once $root . "/EHR_system/db/database.php";
include_once $root . "/EHR_system/db/backend.php";

$action = $_POST["action"];


if ($action === "get_all_patients") {
    $UserID = $_SESSION["UserID"];

    $dbo = new Database();
    $patients = new Patients();

    $statement = $dbo->conn->prepare(
        "SELECT DoctorID FROM doctors WHERE UserID = :UserID"
    );
    $statement->bindParam(':UserID', $UserID, PDO::PARAM_INT);
    $statement->execute();

    $DoctorID = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$DoctorID) {
        echo json_encode(["error" => "Doctor not found"]);
        exit(); // Terminate script execution after sending the response
    }

    // Test get_patients function
    $doctorID = $DoctorID["DoctorID"];

    $result = $patients->get_all_patients($dbo, $doctorID);

    // Check if the result is an error
    if (isset($result["error"])) {
        // Handle the error, for example, send an appropriate response to the client
        echo json_encode($result);
    } else {
        echo $result;
    }
    exit();
}



if ($action === "appointments") {
    $UserID = $_SESSION["UserID"];
    $dbo = new Database();
    $pdo = new Appointmentsinfo();

    $statement = $dbo->conn->prepare(
        "SELECT DoctorID FROM doctors WHERE UserID = :UserID"
    );
    $statement->bindParam(':UserID', $UserID, PDO::PARAM_INT);
 
    $statement->execute();
    
    $Doctor = $statement->fetch(PDO::FETCH_ASSOC);
    $DoctorID = $Doctor["DoctorID"];
    $result = $pdo->get_appointments($dbo, $DoctorID);

    // Check if the result is an error
    if (isset($result["error"])) {
        // Handle the error, for example, send an appropriate response to the client
        echo json_encode($result);
    } else {
        echo $result;
    }
    exit();
}


if ($action === "get_messages") {
    $UserID = $_SESSION["UserID"];
    
    $dbo = new Database();
    $pdo = new Messages();

    $result = $pdo->get_messages($dbo, $UserID, $action = "doctor");

    // Check if the result is an error
    if (isset($result["error"])) {
        // Handle the error, for example, send an appropriate response to the client
        echo json_encode($result);
    } else {
        echo $result;
    }
    exit();
}

if ($action === "send_message") {
    $UserID = $_SESSION["UserID"];
    $patient_name = $_POST["patient_name"];
    $content = $_POST["content"];

    $dbo = new Database();
    $pdo = new Messages();

    
    $nameArray = explode(' ', $patient_name);

    // Check if the array has at least two elements
    if (!count($nameArray) >= 1) {
        
        // Handle the case where the name is not in the expected format
        echo json_encode(["error" => "Invalid name format"]);
        exit(); // Terminate script execution after sending the response
    }

    $statement = $dbo->conn->prepare(
        "SELECT UserID FROM users WHERE Username = :username"
    );
    $statement->bindParam(':username', $patient_name, PDO::PARAM_STR);
    
    $statement->execute();
    
    $userID = $statement->fetch(PDO::FETCH_ASSOC);
    
    if (!$userID) {
        echo json_encode(["error" => "Patient not found"]);
        exit(); // Terminate script execution after sending the response
    }

    
    $result = $pdo->send_message($dbo, $UserID, $userID["UserID"], $content);

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