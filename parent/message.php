<?php
session_start();
include '../db.php';

// Only logged-in parent
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch the application ID based on the logged-in parent
$stmt = $conn->prepare("SELECT id FROM application_info WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($appId);
$stmt->fetch();
$stmt->close();

if (!$appId) {
    echo "Application ID not found. Please login again.";
    exit;
}

// Fetch messages for this application
$stmt = $conn->prepare("SELECT * FROM messages WHERE applicant_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $appId);
$stmt->execute();
$result = $stmt->get_result();
$messages = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$hasMessages = count($messages) > 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Messages</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
body {
  font-family: 'Poppins', sans-serif;
  background: #f1f5f9;
  margin: 0;
  padding: 0;
}
.container {
  max-width: 800px;
  margin: 40px auto;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  padding: 30px;
}
h2 {
  text-align: center;
  color: #1f2937;
  margin-bottom: 25px;
}
.message {
  border: 1px solid #e5e7eb;
  border-left: 6px solid #2563eb;
  border-radius: 8px;
  padding: 15px 20px;
  margin-bottom: 15px;
  background: #f9fafb;
  transition: 0.2s;
}
.message:hover {
  background: #f3f4f6;
}
.message p {
  margin: 6px 0;
  color: #1e293b;
}
.message small {
  color: #6b7280;
  font-size: 13px;
}
.no-msg {
  text-align: center;
  color: #6b7280;
  font-weight: 500;
  padding: 40px 0;
}
.topbar {
  background: #2563eb;
  color: #fff;
  padding: 15px 25px;
  font-size: 18px;
  font-weight: 600;
  border-radius: 0 0 12px 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.btn-logout {
  background: #ef4444;
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 8px 14px;
  cursor: pointer;
  font-weight: 500;
}
.btn-logout:hover {
  background: #dc2626;
}
.status-box {
  text-align: center;
  padding: 15px;
  margin-bottom: 20px;
  border-radius: 8px;
  font-weight: 600;
}
.status-yes {
  background: #d1fae5;
  color: #065f46;
}
.status-no {
  background: #fee2e2;
  color: #991b1b;
}
</style>
</head>
<body>
<div class="topbar">
  <div>📩 Parent Messages</div>
  <form action="../logout.php" method="post" style="margin:0;">
    <button class="btn-logout" type="submit">Logout</button>
  </form>
</div>

<div class="container">
  <?php if ($hasMessages): ?>
    <div class="status-box status-yes">Message Status: ✅ Yes, admin has sent messages</div>
  <?php else: ?>
    <div class="status-box status-no">Message Status: ❌ No messages from admin yet</div>
  <?php endif; ?>

  <h2>Messages from Admin</h2>

  <?php if ($hasMessages): ?>
    <?php foreach ($messages as $msg): ?>
      <div class="message">
        <p><?= htmlspecialchars($msg['message']) ?></p>
        <small>🕒 <?= htmlspecialchars(date("d M Y, h:i A", strtotime($msg['created_at']))) ?></small>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="no-msg">No messages from admin yet.</div>
  <?php endif; ?>
</div>
</body>
</html>
