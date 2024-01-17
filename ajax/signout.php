<?php
// signout.php

// Start or resume the existing session
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page or any other desired page
header("Location: /EHR_system/ui/MyFastCARE/login.php");
exit();
?>