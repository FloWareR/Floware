<?php
 // Optional admin address for confirmation

// Example of using environment variables in PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Function to send email
function sendEmail($emailData, $nameData, $messageData) {

$dotenv = Dotenv::createImmutable(dirname(__DIR__ . '\\'));
$dotenv->load();
$smtpHost = $_ENV['SMTP_HOST'];
$smtpPort = $_ENV['SMTP_PORT'];
$smtpUser = $_ENV['SMTP_USER'];
$smtpPassword = $_ENV['SMTP_PASSWORD'];

    if (!filter_var($emailData, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo 'Invalid email format';
        exit();
    }

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF; // Set to SMTP::DEBUG_SERVER for detailed logs
        $mail->isSMTP();
        $mail->Host       = $smtpHost;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUser;
        $mail->Password   = $smtpPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $smtpPort;

        // Set the sender and recipient
        $mail->setFrom($smtpUser, 'Floware Studio');
        $mail->addAddress($emailData, $nameData); // Send email to the dynamic recipient address
        $mail->addReplyTo($smtpUser, 'Floware Studio'); // Optional, set reply-to address
        // Optional: Send a copy to an admin/confirmation address

        // Content
        $mail->isHTML(true);                                     
        $mail->Subject = "Thanks for your subscription!, $emailData!";
        $mail->Body    = "<p><strong>Message:</strong><br>{$messageData},</p>";

        $mail->send();

        http_response_code(200); 
        return 'Message has been sent';
    } catch (Exception $e) {
        http_response_code(500);
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
