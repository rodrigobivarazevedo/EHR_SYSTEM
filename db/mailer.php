<?php
require 'vendor/autoload.php'; // Include the Composer autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_welcome_email($to, $FirstName, $LastName) {
        

            $mail = new PHPMailer(true);

            try {

            // Set the mail server details
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server address
            $mail->SMTPAuth = true;
            $mail->Username = 'myfastcaresolutions@gmail.com'; // Replace with your SMTP username
            $mail->Password = 'ells ijzk izde byyu'; // Replace with your SMTP password
            $mail->SMTPSecure = 'tls'; // Use 'tls' or 'ssl' depending on your server configuration
            $mail->Port = 587; // Use the appropriate port for your server

            // Set the sender and recipient email addresses
            $mail->setFrom('myfastcaresolutions@gmail.com', 'FastCARE');
            $mail->addAddress($to, $FirstName . ' ' . $LastName);

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
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
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                // Send the email
                $mail->send();
            } catch (Exception $e) {
            }
        
        }

?>