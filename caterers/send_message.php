<?php
session_start();
require 'db_config.php';
require 'notification.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caterer_id = $_SESSION['user_id'];
    $thread_id = $_POST['thread_id'];
    $message = $_POST['message'];

    try {
        // Get thread details
        $stmt = $pdo->prepare("SELECT client_id FROM threads WHERE thread_id = ?");
        $stmt->execute([$thread_id]);
        $thread = $stmt->fetch();

        // Insert message
        $stmt = $pdo->prepare("
            INSERT INTO messages 
            (thread_id, sender_id, recipient_id, message)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $thread_id,
            $caterer_id,
            $thread['client_id'],
            $message
        ]);

        // Send notification to client
        sendNotification(
            $thread['client_id'],
            "New message from caterer",
            substr($message, 0, 50) . '...'
        );

        header("Location: inbox.php?thread_id=$thread_id");
        exit();
    } catch (Exception $e) {
        die("Error sending message: " . $e->getMessage());
    }
}