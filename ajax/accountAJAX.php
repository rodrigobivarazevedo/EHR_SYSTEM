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


if ($action === "reset_password") {


    $oldPassword = $_POST["oldPassword"];
    $newPassword = $_POST["newPassword"];
    
    $result = $pdo->reset_password($dbo, $UserID, $oldPassword, $newPassword);

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