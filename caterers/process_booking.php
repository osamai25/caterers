<?php
require 'db_config.php';
require 'mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'client_name' => $_POST['name'],
        'email' => $_POST['email'],
        'event_date' => $_POST['event_date'],
        'guests' => $_POST['guests'],
        'message' => $_POST['message']
    ];

    try {
        // Check date availability
        $stmt = $pdo->prepare("SELECT id FROM bookings WHERE event_date = ?");
        $stmt->execute([$data['event_date']]);
        
        if ($stmt->rowCount() > 0) {
            throw new Exception("Selected date is already booked");
        }

        // Insert booking
        $stmt = $pdo->prepare("INSERT INTO bookings 
            (client_name, email, event_date, guests, message)
            VALUES (?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data['client_name'],
            $data['email'],
            $data['event_date'],
            $data['guests'],
            $data['message']
        ]);

        $booking_id = $pdo->lastInsertId();

        // Create caterer alert
        $alert_message = "New booking received for {$data['event_date']} ({$data['guests']} guests)";
        $stmt = $pdo->prepare("INSERT INTO caterer_alerts 
            (booking_id, alert_message) VALUES (?, ?)");
        $stmt->execute([$booking_id, $alert_message]);

        // Send email notification
        $to = 'caterer@example.com';
        $subject = 'New Booking Notification';
        $body = "You have a new booking:\n\n" .
                "Date: {$data['event_date']}\n" .
                "Client: {$data['client_name']}\n" .
                "Guests: {$data['guests']}\n" .
                "Message: {$data['message']}";
        
        sendEmail($to, $subject, $body);

        echo "Booking successful! The caterer has been notified.";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}