// caterer_dashboard.php
$stmt = $pdo->query("SELECT * FROM caterer_alerts WHERE is_read = 0 ORDER BY created_at DESC");
$alerts = $stmt->fetchAll();

// Mark alerts as read when viewed
$pdo->exec("UPDATE caterer_alerts SET is_read = 1");