<?php


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
            
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: FastCARE <myfastcaresolutions@gmail.com>' . "\r\n" .
                    'Reply-To: myfastcaresolutions@gmail.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
        
            // Use the mail function to send the email
            mail($to, $subject, $message, $headers);
        }

$to = "rodrigobivarazevedo@gmail.com";
$username = "Rodrigo Azevedo";
send_welcome_email($to, $username);
?>
