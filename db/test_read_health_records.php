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

$result = $pdo->get_all_health_records($dbo, $doctorID, $patientID);
echo "Results: " . $result . "\n";

?>
