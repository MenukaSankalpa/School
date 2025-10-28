<?php
session_start();
include '../db.php';

// Simple auth check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$appId = intval($_GET['id'] ?? 0);
if (!$appId) {
    die("Invalid Applicant ID");
}

// Fetch applicant info
$stmt = $conn->prepare("SELECT a.*, u.username AS applicant_name, u.email AS applicant_email
                        FROM application_info a 
                        LEFT JOIN users u ON a.user_id = u.id
                        WHERE a.id=?");
$stmt->bind_param("i", $appId);
$stmt->execute();
$app = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$app) {
    die("Applicant not found!");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Applicant</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
body {
  font-family: 'Poppins', sans-serif;
  background: #f3f4f6;
  margin: 0;
  padding: 0;
}
.container {
  max-width: 700px;
  margin: 50px auto;
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
table {
  width: 100%;
  border-collapse: collapse;
}
td {
  padding: 12px 8px;
  border-bottom: 1px solid #e5e7eb;
  font-size: 15px;
}
td:first-child {
  font-weight: 600;
  color: #374151;
  width: 180px;
}
textarea, input, button {
  font-family: inherit;
  font-size: 15px;
}
textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  resize: vertical;
  margin-top: 5px;
}
button {
  margin-top: 15px;
  padding: 10px 16px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  color: #fff;
  font-weight: 600;
  transition: 0.3s;
}
.btn-save {
  background: #10b981;
}
.btn-save:hover {
  background: #059669;
}
.btn-message {
  background: #2563eb;
}
.btn-message:hover {
  background: #1e40af;
}
.alert {
  margin-top: 15px;
  padding: 10px;
  border-radius: 6px;
  text-align: center;
  font-weight: 500;
  display: none;
}
.alert.success {
  background: #d1fae5;
  color: #065f46;
}
.alert.error {
  background: #fee2e2;
  color: #991b1b;
}
</style>
</head>
<body>
<div class="container">
  <h2>Applicant Details</h2>
  <table>
    <tr><td>Applicant Name</td><td><?= htmlspecialchars($app['applicant_name']) ?></td></tr>
    <tr><td>Email</td><td><?= htmlspecialchars($app['applicant_email']) ?></td></tr>
    <tr><td>Child Full Name</td><td><?= htmlspecialchars($app['child_full_name']) ?></td></tr>
    <tr><td>School Name</td><td><?= htmlspecialchars($app['school_name'] ?? '-') ?></td></tr>
    <tr><td>Marks</td><td><?= htmlspecialchars($app['marks'] ?? '-') ?></td></tr>
  </table>

  <form id="feedbackForm">
    <input type="hidden" name="app_id" value="<?= $appId ?>">
    <label for="feedback"><strong>Feedback:</strong></label>
    <textarea name="feedback" id="feedback" rows="4"><?= htmlspecialchars($app['feedback'] ?? '') ?></textarea>
    <button type="submit" class="btn-save">💾 Save Feedback</button>
    <button type="button" class="btn-message" onclick="sendMessage()">📩 Send Message to Parent</button>
  </form>

  <div id="alertBox" class="alert"></div>
</div>

<script>
function showAlert(type, text) {
  const box = document.getElementById('alertBox');
  box.className = 'alert ' + type;
  box.textContent = text;
  box.style.display = 'block';
  setTimeout(()=> box.style.display='none', 3000);
}

// Save feedback
document.getElementById('feedbackForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  fetch('update_feedback.php', {
    method: 'POST',
    body: formData
  }).then(r => r.json()).then(d => {
    if (d.status === 'success') showAlert('success', 'Feedback updated successfully!');
    else showAlert('error', d.message);
  });
});

// Send message to parent
function sendMessage() {
  fetch('send_message.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ app_id: <?= $appId ?> })
  })
  .then(r => r.json())
  .then(d => {
    if (d.status === 'success') showAlert('success', 'Message sent to parent successfully!');
    else showAlert('error', d.message);
  })
  .catch(err => showAlert('error', 'Error sending message'));
}
</script>
</body>
</html>
