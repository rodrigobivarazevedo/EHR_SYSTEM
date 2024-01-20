<?php

// Include your database connection and Patients class
require_once "database.php"; // Change this to your actual database file
require_once "backend.php";

// Create a new instance of the Database class
$dbo = new Database();

// Create a new instance of the Patients class
$pdo = new Records();

$patientID = 4;
$doctorID = 1;
$dateRecorded = "2022-01-01"; // Added semicolon
$diagnosis = "diagnosis 3"; // Added semicolon
$medications = "medications 3"; // Added semicolon
$procedures = "procedure 3"; // Added semicolon
$comments = "comment 3"; // Added semicolon

$result = $pdo->update_health_record($dbo, $recordID, $newData);
echo "New test created: " . $result . "\n";

?>
