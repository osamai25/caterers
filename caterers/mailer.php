<?php
function sendEmail($to, $subject, $body) {
    $headers = "From: bookings@cateringsystem.com\r\n";
    $headers .= "Reply-To: noreply@cateringsystem.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    return mail($to, $subject, $body, $headers);
}