<?php
require 'vendor/autoload.php'; // Include the Composer autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_welcome_email($to, $FirstName, $LastName) {
        

            $mail = new PHPMailer(true);

            try {

            // Set the mail server details
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // SMTP server address
            $mail->SMTPAuth = true;
            $mail->Username = 'myfastcaresolutions@gmail.com'; // SMTP username
            $mail->Password = 'ells ijzk izde byyu'; // SMTP password
            $mail->SMTPSecure = 'tls'; 
            $mail->Port = 587; //  port for server

            // Set the sender and recipient email addresses
            $mail->setFrom('myfastcaresolutions@gmail.com', 'FastCARE');
            $mail->addAddress($to, $FirstName . ' ' . $LastName);

            //Attachments
            //$mail->addAttachment('../images/logo.png', 'logo.png');         //attachments
           
            $subject = 'Welcome to MyFastCARE!';

            $message = '<html>
                            <head>
                                <title>Welcome to MyFastCARE!</title>
                            </head>
                            <body>
                                <p>Dear ' . $FirstName . ' ' . $LastName . ',</p>
                                <p>Welcome to <strong>MyFastCARE</strong>!</p>
                                <p>Thank you for registering with MyFastCARE, a medical EHR platform for doctors, proudly brought to you by FastCARE Corporation.</p>
                                <p>We are excited to have you on board and look forward to providing you with excellent services.</p>
                                <p>Best regards,<br>
                                The FastCARE Team</p>
                            </body>
                        </html>';

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;
           

                // Send the email
                $mail->send();
            } catch (Exception $e) {
            }
        
        }
        
function sendPasswordResetEmail($to, $token) {
            $mail = new PHPMailer(true);

            try {

            // Set the mail server details
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // SMTP server address
            $mail->SMTPAuth = true;
            $mail->Username = 'myfastcaresolutions@gmail.com'; // SMTP username
            $mail->Password = 'ells ijzk izde byyu'; // SMTP password
            $mail->SMTPSecure = 'tls'; 
            $mail->Port = 587; 

            // Set the sender and recipient email addresses
            $mail->setFrom('myfastcaresolutions@gmail.com', 'FastCARE');
            $mail->addAddress($to);

            //Attachments
            //$mail->addAttachment('../images/logo.png', 'logo.png');

            $subject = 'Password Reset MyFastCARE';

            $message = 'Click the following link to reset your password: http://localhost/EHR_system/ui/reset_password.php?token=' . $token;

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;
           

                // Send the email
                $mail->send();
            } catch (Exception $e) {
                // Log the exception or handle it in a way that fits your application
                error_log('Error sending password reset email: ' . $e->getMessage());
            }
        
        }

?>
