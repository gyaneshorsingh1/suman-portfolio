<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $name = filter_var(trim($_POST["name"]), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = filter_var(trim($_POST["phone"]), FILTER_SANITIZE_STRING);
    $subject = filter_var(trim($_POST["subject"]), FILTER_SANITIZE_STRING);
    $message = filter_var(trim($_POST["message"]), FILTER_SANITIZE_STRING);

    // Validate inputs
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($subject)) {
        $errors[] = "Subject is required";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }

    // If no validation errors, proceed with sending email
    if (empty($errors)) {
        // Create PHPMailer instance
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'sumanjaiswal186@gmail.com'; // Your Gmail address
            $mail->Password = 'dhukkbfuqvkexfow'; // Your Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('sumanjaiswal186@gmail.com', $name);
            $mail->addAddress('sumanjaiswal186@gmail.com', 'Suman Jaiswal');
            $mail->addReplyTo($email, $name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = "New Contact Form Submission: $subject";
            $mail->Body = "<h3>New Contact Form Submission</h3>";
            $mail->Body .= "<p><strong>Name:</strong> $name</p>";
            $mail->Body .= "<p><strong>Email:</strong> $email</p>";
            $mail->Body .= "<p><strong>Phone:</strong> $phone</p>";
            $mail->Body .= "<p><strong>Subject:</strong> $subject</p>";
            $mail->Body .= "<p><strong>Message:</strong><br>$message</p>";
            $mail->AltBody = "You have received a new message from your portfolio website.\n\n";
            $mail->AltBody .= "Name: $name\n";
            $mail->AltBody .= "Email: $email\n";
            $mail->AltBody .= "Phone: $phone\n";
            $mail->AltBody .= "Subject: $subject\n";
            $mail->AltBody .= "Message:\n$message\n";

            // Send email
            $mail->send();
            $response = array(
                'status' => 'success',
                'message' => 'Your message has been sent. Thank you!'
            );
        } catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => 'Failed to send message. Error: ' . $mail->ErrorInfo
            );
        }
    } else {
        // Validation errors
        $response = array(
            'status' => 'error',
            'message' => implode('<br>', $errors)
        );
    }

    // Return JSON response for AJAX
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
} else {
    // Invalid request method
    $response = array(
        'status' => 'error',
        'message' => 'Invalid request method.'
    );
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>