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
   
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $birthdate = $_POST["birthdate"];
    $gender = $_POST["gender"];
    $address = $_POST["address"];
    $contactNumber = $_POST["contactNumber"];

    $result = $pdo->create_health_record($dbo, $doctorID, $firstName, $lastName, $email, $birthdate, $gender, $address, $contactNumber);

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
    
    $newData = [
        'PatientID' => $_POST["PatientID"],
        'DateRecorded' => $_POST["DateRecorded"],
        'RecordID' => $_POST["RecordID"],
        'diagnosis' => $_POST["diagnosis"],
        'medications' => $_POST["medications"],
        'procedures' => $_POST["procedures"],
        'comments' => $_POST["comments"],
    
    ];
    	
    $result = $pdo->update_health_record($dbo, $newData);

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
    
    $recordID = $_POST["recordID"]; // Assuming the parameter is named recordID

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
        $result = $pdo->get_health_record($dbo, $input);
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
