<?php

$root = $_SERVER["DOCUMENT_ROOT"];
include_once $root . "/EHR_system/db/database.php";
include_once $root . "/EHR_system/db/backend.php";

$speciality = $_GET["speciality"];
$action = $_GET["action1"];

if ($action === "get_all") {
    $dbo = new Database();
    $pdo = new All_Info();

    $result = $pdo->get_all_info($dbo, "clinics");

    // Check if the result is an error
    if (isset($result["error"])) {
        // Handle the error, for example, send an appropriate response to the client
        echo json_encode($result);
    } else {
        echo $result;
    }
    exit();
}

if ($action === "get_clinics") {
    $dbo = new Database();
    $pdo = new Clinicsinfo();

    $result = $pdo->get_clinic_info($dbo, $speciality);

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