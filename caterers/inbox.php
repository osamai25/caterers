<?php
session_start();
require 'db_config.php';

// Get logged-in caterer ID (example)
$caterer_id = $_SESSION['user_id'];

// Mark messages as read when opened
if(isset($_GET['thread_id'])) {
    $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE thread_id = ? AND recipient_id = ?");
    $stmt->execute([$_GET['thread_id'], $caterer_id]);
}

// Get all conversations
$stmt = $pdo->prepare("
    SELECT 
        t.thread_id,
        u.name AS client_name,
        m.message,
        m.created_at,
        COUNT(CASE WHEN m.is_read = 0 THEN 1 END) AS unread_count
    FROM threads t
    JOIN messages m ON t.thread_id = m.thread_id
    JOIN users u ON t.client_id = u.user_id
    WHERE t.caterer_id = ?
    GROUP BY t.thread_id
    ORDER BY MAX(m.created_at) DESC
");
$stmt->execute([$caterer_id]);
$conversations = $stmt->fetchAll();

// Get messages for selected thread
$messages = [];
if(isset($_GET['thread_id'])) {
    $stmt = $pdo->prepare("
        SELECT m.*, u.name AS sender_name
        FROM messages m
        JOIN users u ON m.sender_id = u.user_id
        WHERE thread_id = ?
        ORDER BY created_at ASC
    ");
    $stmt->execute([$_GET['thread_id']]);
    $messages = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Caterer Inbox</title>
    <style>
        .inbox-container { display: flex; height: 100vh; }
        .conversation-list { width: 300px; border-right: 1px solid #ddd; }
        .conversation-item { padding: 15px; border-bottom: 1px solid #eee; cursor: pointer; }
        .conversation-item:hover { background: #f5f5f5; }
        .unread { font-weight: bold; background: #e3f2fd; }
        .message-area { flex: 1; padding: 20px; }
        .message { margin-bottom: 15px; padding: 10px; border-radius: 5px; }
        .client-message { background: #e3f2fd; margin-right: 20%; }
        .caterer-message { background: #f5f5f5; margin-left: 20%; }
        .timestamp { font-size: 0.8em; color: #666; }
        .reply-box { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="inbox-container">
        <!-- Conversation List -->
        <div class="conversation-list">
            <h2>Conversations</h2>
            <?php foreach ($conversations as $conv): ?>
                <div class="conversation-item <?= $conv['unread_count'] > 0 ? 'unread' : '' ?>"
                     onclick="location.href='?thread_id=<?= $conv['thread_id'] ?>'">
                    <h3><?= htmlspecialchars($conv['client_name']) ?></h3>
                    <p><?= substr($conv['message'], 0, 50) ?>...</p>
                    <div class="timestamp">
                        <?= date('M j, g:i a', strtotime($conv['created_at'])) ?>
                        <?php if($conv['unread_count'] > 0): ?>
                            <span class="unread-count">(<?= $conv['unread_count'] ?> new)</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Message Thread -->
        <div class="message-area">
            <?php if(isset($_GET['thread_id'])): ?>
                <div class="message-thread">
                    <?php foreach ($messages as $msg): ?>
                        <div class="message <?= $msg['sender_id'] == $caterer_id ? 'caterer-message' : 'client-message' ?>">
                            <div class="message-header">
                                <strong><?= htmlspecialchars($msg['sender_name']) ?></strong>
                                <span class="timestamp">
                                    <?= date('M j, g:i a', strtotime($msg['created_at'])) ?>
                                </span>
                            </div>
                            <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Reply Form -->
                <form class="reply-box" method="POST" action="send_message.php">
                    <input type="hidden" name="thread_id" value="<?= $_GET['thread_id'] ?>">
                    <textarea name="message" rows="4" placeholder="Type your reply..." required></textarea>
                    <button type="submit">Send</button>
                </form>
            <?php else: ?>
                <p>Select a conversation to view messages</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Auto-refresh every 30 seconds
        setInterval(() => {
            if(!document.hidden) location.reload();
        }, 30000);

        // Scroll to bottom of message thread
        const thread = document.querySelector('.message-thread');
        if(thread) thread.scrollTop = thread.scrollHeight;
    </script>
</body>
</html>