<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Collect and sanitize form data
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

        // Validate input
        if (empty($name) || empty($email) || empty($message)) {
            throw new Exception("Please fill in all required fields.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        // Set recipient email address
        $to = "mordecaimwambe8@gmail.com";

        // Set email headers
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "MIME-Version: 1.0\r\n";

        // Compose email body
        $email_body = "You have received a new message from your website contact form.\n\n";
        $email_body .= "Name: $name\n";
        $email_body .= "Email: $email\n";
        $email_body .= "Subject: $subject\n\n";
        $email_body .= "Message:\n$message\n";

        // Send email
        if (mail($to, $subject, $email_body, $headers)) {
            // Redirect to thank-you page
            header('Location: thank-you.html');
            exit;
        } else {
            throw new Exception("Failed to send email. Please try again later.");
        }
    } catch (Exception $e) {
        // Log the error and show user-friendly message
        error_log("Contact form error: " . $e->getMessage());
        header('Location: contact.html?error=1');
        exit;
    }
} else {
    // If not a POST request, redirect back to contact page
    header('Location: contact.html');
    exit;
}
?>
