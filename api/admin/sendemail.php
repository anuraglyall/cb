<?php
// Recipient email address
$to = "aaravktech@gmail.com";

// Subject of the email
$subject = "Dinesh k";

// Message body
$message = "Dinesh k.";

// Additional headers
$headers = "From: info@64facetscrm.com" . "\r\n" .
    "Reply-To: info@64facetscrm.com" . "\r\n" .
    "X-Mailer: PHP/" . phpversion();

// Send the email
$mailSent = mail($to, $subject, $message, $headers);

// Check if the email was sent successfully
if ($mailSent) {
    echo "Email sent successfully.";
} else {
    echo "Email sending failed.";
}
?>