<?php
session_start(); // Start the session
$root = $_SERVER["DOCUMENT_ROOT"];
include_once $root . "/EHR_system/db/database.php";
include_once $root . "/EHR_system/db/backend.php";

$action = $_POST["action"];

if ($action === "login") {
    
    $UsernameOrEmail = isset($_POST["UsernameOrEmail"]) ? $_POST["UsernameOrEmail"] : null;
    $password = isset($_POST["password"]) ? $_POST["password"] : null;

    $dbo = new Database();
    $pdo = new Users();

    $result = $pdo->login($dbo, $UsernameOrEmail, $password);

    // Check if the result is an error
    if (isset($result["error"])) {
        // Handle the error, for example, send an appropriate response to the client
        echo $result;
        exit();
    }

    $data = json_decode($result, true);
    // Check if the login was successful
    if (isset($data['UserID'])) {
        // Store user information in session variables
        $_SESSION["UserID"] = $data['UserID'];
        $_SESSION["Username"] = $data["username"];
        $_SESSION["Role"] = $data["role"];

    }

    echo $result;

    exit();
}


if ($action === "register") {
    $dbo = new Database();
    $pdo = new Users();

    // Validate and sanitize inputs
    $username = isset($_POST["username"]) ? filter_var($_POST["username"], FILTER_SANITIZE_STRING) : null;
    $password = isset($_POST["password"]) ? filter_var($_POST["password"], FILTER_SANITIZE_STRING) : null;
    $email = isset($_POST["email"]) ? filter_var($_POST["email"], FILTER_SANITIZE_EMAIL) : null;
    $contactNumber = isset($_POST["contactNumber"]) ? filter_var($_POST["contactNumber"], FILTER_SANITIZE_STRING) : null;
    $speciality = isset($_POST["speciality"]) ? filter_var($_POST["speciality"], FILTER_SANITIZE_STRING) : null;
    $FirstName = isset($_POST["FirstName"]) ? filter_var($_POST["FirstName"], FILTER_SANITIZE_STRING) : null;
    $LastName = isset($_POST["LastName"]) ? filter_var($_POST["LastName"], FILTER_SANITIZE_STRING) : null;
    $gender = isset($_POST["gender"]) ? filter_var($_POST["gender"], FILTER_SANITIZE_STRING) : null;
    $birthdate = isset($_POST["birthdate"]) ? filter_var($_POST["birthdate"], FILTER_SANITIZE_STRING) : null;


    // Check if any of the required values are null
    if (empty($username) || empty($password) || empty($email) || empty($contactNumber) || empty($speciality)) {
        echo json_encode(["error" => "All required fields must be provided."]);
        exit();
    }

    $result = $pdo->create_user($dbo, $username, $password, $email, $contactNumber, $FirstName, $LastName, $gender, $birthdate, $speciality);

    echo $result;

    exit();
}



?>