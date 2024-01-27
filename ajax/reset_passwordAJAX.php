<?php
$root = $_SERVER["DOCUMENT_ROOT"];
include_once $root . "/EHR_system/db/database.php";
require_once $root . "/EHR_system/db/mailer.php"; 
$dbo = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = $_POST['email']; 


    // Check if the email exists in your database
    $checkEmailStatement = $dbo->conn->prepare("SELECT UserID FROM users WHERE Email = :email");
    $checkEmailStatement->bindParam(':email', $email, PDO::PARAM_STR);
    $checkEmailStatement->execute();
    $user = $checkEmailStatement->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // User found, initiate password reset
        $token = bin2hex(random_bytes(32)); // Generate a random token
        $timestamp = time(); // Get the current timestamp

        // Store the token and timestamp in the database
        $storeTokenStatement = $dbo->conn->prepare("INSERT INTO password_resets (email, token, created_at) VALUES (:email, :token, :created_at)");
        $storeTokenStatement->bindParam(':email', $email, PDO::PARAM_STR);
        $storeTokenStatement->bindParam(':token', $token, PDO::PARAM_STR);
        $storeTokenStatement->bindParam(':created_at', $timestamp, PDO::PARAM_INT);
        $storeTokenStatement->execute();

        // Send the password reset email
        sendPasswordResetEmail($email, $token);

        echo json_encode(["message" => "Password reset initiated. Check your email for instructions."]);
    } else {
        echo json_encode(["message" => "User not found. Please check your email address."]);
      
    }
}

?>
