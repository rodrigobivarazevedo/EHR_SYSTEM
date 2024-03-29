<?php
$root = $_SERVER["DOCUMENT_ROOT"];
include_once $root . "/EHR_system/db/database.php";
include_once $root . "/EHR_system/db/backend.php";
$dbo = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize token from the query string
    $token = isset($_GET['token']) ? htmlspecialchars($_GET['token']) : null;

    if ($token) {
        // Check if the token exists in the database
        $checkTokenStatement = $dbo->conn->prepare("SELECT email, created_at FROM password_resets WHERE token = :token");
        $checkTokenStatement->bindParam(':token', $token, PDO::PARAM_STR);
        $checkTokenStatement->execute();
        $tokenInfo = $checkTokenStatement->fetch(PDO::FETCH_ASSOC);

        if ($tokenInfo) {
            // Check if the token is still valid (e.g., not expired)
            $expiryTime = strtotime('+1 hour', $tokenInfo['created_at']); // Assuming the token is valid for 1 hour

            if (time() <= $expiryTime) {
                // Token is valid, allow the user to reset the password
                $email = $tokenInfo['email'];
                include_once "reset_password_form.php"; // Include the form for password reset
            } else {
                header("Location: error.php?message=Token has expired. Please initiate the password reset process again.");
                exit;

            }
        } else {
            header("Location: error.php?message=Invalid Token. Please initiate the password reset process again.");
            exit;

        }
    } else {
        header("Location: error.php?message=Invalid Token. Please initiate the password reset process again.");
        exit;

    }
}
?>


<?php
$root = $_SERVER["DOCUMENT_ROOT"];
include_once $root . "/EHR_system/db/database.php";
include_once $root . "/EHR_system/db/backend.php";
$dbo = new Database();

// Initialize the message variable
$message = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $token = isset($_GET['token']) ? htmlspecialchars($_GET['token']) : null;
    $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : null;
    $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : null;

    // Check if passwords match
    if ($newPassword !== $confirmPassword) {
        $message = "Passwords do not match.";
        exit;
    }

    // Validate token
    if (!$token) {
        $message = "Invalid token.";
        exit;
    }

    // Check if the token exists in the database
    $checkTokenStatement = $dbo->conn->prepare("SELECT email, created_at FROM password_resets WHERE token = :token");
    $checkTokenStatement->bindParam(':token', $token, PDO::PARAM_STR);
    $checkTokenStatement->execute();
    $tokenInfo = $checkTokenStatement->fetch(PDO::FETCH_ASSOC);

    if ($tokenInfo) {
        // Check if the token is still valid (not expired)
        $expiryTime = strtotime('+1 hour', $tokenInfo['created_at']); // token is valid for 1 hour

        if (time() <= $expiryTime) {
            // Token is valid, update the user's password
            $email = $tokenInfo['email'];

            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the user's password in the database
            $updatePasswordStatement = $dbo->conn->prepare("UPDATE users SET Password = :hashedPassword WHERE Email = :email");
            $updatePasswordStatement->bindParam(':hashedPassword', $hashedPassword, PDO::PARAM_STR);
            $updatePasswordStatement->bindParam(':email', $email, PDO::PARAM_STR);
            $updatePasswordStatement->execute();

            // Delete the used token from the password_resets table
            $deleteTokenStatement = $dbo->conn->prepare("DELETE FROM password_resets WHERE token = :token");
            $deleteTokenStatement->bindParam(':token', $token, PDO::PARAM_STR);
            $deleteTokenStatement->execute();

            $message = "Password reset successful. You can now log in with your new password.";
        } else {
            $message = "Token has expired. Please initiate the password reset process again.";
        }
    } else {
        $message = "Invalid token. Please initiate the password reset process again.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <!-- Include Bootstrap CSS file -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <!-- Include Bootstrap JS file with Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="mb-4 text-center">MyFastCARE</h1>

            <div class="card">
                <div class="card-header">
                    Reset Password
                </div>
                <div class="card-body">
                    <hr class="mt-4">

                    <form id="resetPasswordForm" class="mt-3" method="post" action="">
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password:</label>
                            <input type="password" class="form-control" name="new_password" placeholder="Enter new password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password:</label>
                            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm new password" required>
                        </div>
                        <!-- Add a hidden input for the token -->
                        <input type="hidden" name="token" value="<?php echo isset($_POST['token']) ? htmlspecialchars($_POST['token']) : ''; ?>">
                        <button type="submit" class="btn btn-primary mt-3">Reset Password</button>
                    </form>

                    <!-- Display the message in a centered container -->
                    <div class="text-center mt-3">
                        <?php if ($message): ?>
                            <div class="alert <?php echo (strpos($message, 'successful') !== false) ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>