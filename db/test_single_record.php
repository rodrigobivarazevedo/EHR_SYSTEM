<?php

// Include your database connection and Patients class
require_once "database.php"; // Change this to your actual database file
require_once "backend.php";

// Create a new instance of the Database class
$dbo = new Database();

// Create a new instance of the Patients class
$pdo = new Records();

$recordID = 6;

$result = $pdo->delete_health_record($dbo, $recordID);
echo "Results: " . $result . "\n";

?>