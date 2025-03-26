<?php
session_start();
require 'db_config.php';

// Get booked dates
$booked_dates = [];
$stmt = $pdo->query("SELECT event_date FROM bookings");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $booked_dates[] = $row['event_date'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Catering Service</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .container { max-width: 800px; margin: 2rem auto; padding: 20px; }
        .calendar { margin: 20px 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Book Catering Services</h1>
        
        <form id="bookingForm" method="POST">
            <div class="form-group">
                <label>Select Date</label>
                <input type="text" id="calendar" class="calendar" name="event_date" readonly>
            </div>
            
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" required>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Number of Guests</label>
                <input type="number" name="guests" min="1" required>
            </div>
            
            <div class="form-group">
                <label>Special Requests</label>
                <textarea name="message" rows="4"></textarea>
            </div>
            
            <button type="submit">Submit Booking</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        const bookedDates = <?php echo json_encode($booked_dates); ?>;
        
        flatpickr("#calendar", {
            minDate: "today",
            dateFormat: "Y-m-d",
            disable: [
                function(date) {
                    return bookedDates.includes(date.toISOString().split('T')[0]);
                }
            ]
        });
    </script>
</body>
</html>