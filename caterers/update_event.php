<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['eventId'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $menu = mysqli_real_escape_string($conn, $_POST['menu']);
    $booking = mysqli_real_escape_string($conn, $_POST['booking_info']);

    $sql = "UPDATE events SET 
            event_date = '$date',
            event_time = '$time',
            location = '$location',
            menu = '$menu',
            booking_info = '$booking'
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Event updated successfully";
    } else {
        echo "Error updating event: " . $conn->error;
    }
}

$conn->close();
?>