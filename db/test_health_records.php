<?php

// Include your database connection and Patients class
require_once "database.php"; // Change this to your actual database file
require_once "backend.php";

// Create a new instance of the Database class
$dbo = new Database();

// Create a new instance of the Patients class
$pdo = new Records();

$recordID = 7;
$newData = [
    'patientID' => 4,
    'doctorID' => 1,
    'dateRecorded' => '2022-01-01',
    'diagnosis' => 'arthroesclosis',
    'medications' => 'brufen',
    'procedures' => 'no procedure',
    'comments' => 'patient has severe back pain',
];

$result = $pdo->update_health_record($dbo, $recordID, $newData);
echo "test updated: " . $result . "\n";

?>
