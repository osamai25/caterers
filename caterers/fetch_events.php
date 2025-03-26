<?php
include 'db_connection.php';

$sql = "SELECT * FROM events ORDER BY event_date DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$row['event_date']."</td>
                <td>".$row['event_time']."</td>
                <td>".$row['location']."</td>
                <td>".$row['menu']."</td>
                <td>".$row['booking_info']."</td>
                <td>
                    <button class='edit-btn' onclick='openEditModal(
                        \"".$row['id']."\",
                        \"".$row['event_date']."\",
                        \"".$row['event_time']."\",
                        \"".addslashes($row['location'])."\",
                        \"".addslashes($row['menu'])."\",
                        \"".addslashes($row['booking_info'])."\"
                    )'>Edit</button>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No events found</td></tr>";
}

$conn->close();
?>