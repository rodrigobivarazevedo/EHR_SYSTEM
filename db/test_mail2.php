<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoload file
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/SMTP.php';

function send_welcome_email($to, $username) {
    $subject = 'Welcome to MyFastCARE!';

    $message = '<html>
                    <head>
                        <title>Welcome to MyFastCARE</title>
                    </head>
                    <body>
                        <p>Dear ' . $username . ',</p>
                        <p>Welcome to <strong>MyFastCARE</strong>!</p>
                        <p>Thank you for registering with MyFastCARE, a medical EHR platform for doctors, proudly brought to you by FastCARE Corporation.</p>
                        <p>We are excited to have you on board and look forward to providing you with excellent services.</p>
                        <p>Best regards,<br>
                        The FastCARE Team</p>
                    </body>
                </html>';

    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Enable verbose debugging
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;

        // Set mailer to use SMTP
        $mail->isSMTP();

        // Specify SMTP host
        $mail->Host = 'smtp.gmail.com';

        // Enable SMTP authentication
        $mail->SMTPAuth = true;

        // SMTP username
        $mail->Username = 'myfastcaresolutions@gmail.com';

        // SMTP password
        $mail->Password = 'Palomita123@';

        // Enable TLS encryption
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        // TCP port to connect to
        $mail->Port = 587;

        // Set email format to HTML
        $mail->isHTML(true);

        // Set sender and recipient
        $mail->setFrom('myfastcaresolutions@gmail.com', 'FastCARE');
        $mail->addAddress($to, $username);

        // Set email subject and body
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Send the email
        $mail->send();
        
        echo 'Email sent successfully!';
    } catch (Exception $e) {
        echo 'Failed to send email. Error: ', $mail->ErrorInfo;
    }
}

$to = "rodrigobivarazevedo@gmail.com";
$username = "Rodrigo Azevedo";
send_welcome_email($to, $username);
?>
