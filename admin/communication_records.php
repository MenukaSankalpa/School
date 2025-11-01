<?php
include '../db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$notification = "";

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $msgId = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $msgId);
    if ($stmt->execute()) {
        $notification = "Message deleted successfully!";
    } else {
        $notification = "Failed to delete message.";
    }
}

// Fetch all messages
$sql = "SELECT m.id AS message_id, m.sender, m.receiver, m.message, m.subject, m.created_at,
               m.applicant_id,
               a.child_full_name, a.applicant_full_name, a.applicant_phone, a.marks, a.feedback
        FROM messages m
        LEFT JOIN application_info a ON m.applicant_id = a.id
        ORDER BY m.applicant_id ASC, m.created_at ASC";

$result = $conn->query($sql);
$messages = [];
while ($row = $result->fetch_assoc()) {
    $applicantId = $row['applicant_id'] ?? 'no_applicant_' . $row['message_id']; // handle messages with no applicant
    $messages[$applicantId][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Communication Records</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body { margin:0; font-family:'Poppins',sans-serif; background:#f8fafc; }
.main { padding:40px; }
.card { background:#fff; border-radius:10px; padding:25px; box-shadow:0 4px 16px rgba(0,0,0,0.08); margin-bottom:40px; }
h1 { font-size:26px; margin-bottom:20px; }
.applicant-header { font-weight:600; font-size:16px; margin-bottom:10px; color:#1e40af; }
.message-box { background:#f1f5f9; padding:10px 12px; border-radius:8px; margin-bottom:8px; display:flex; justify-content:space-between; align-items:center; }
.message-content { max-width:90%; }
.sender-admin { border-left:4px solid #2563eb; padding-left:10px; }
.sender-parent { border-left:4px solid #16a34a; padding-left:10px; }
.btn-delete { background:#ef4444; color:#fff; border:none; padding:6px 10px; border-radius:6px; cursor:pointer; font-size:14px; transition:0.3s; }
.btn-delete:hover { background:#b91c1c; }
.blur-bg { position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.4); display:none; justify-content:center; align-items:center; z-index:1000; }
.popup { background:#fff; padding:25px; border-radius:12px; text-align:center; width:320px; box-shadow:0 5px 20px rgba(0,0,0,0.3); }
.popup h3 { margin-bottom:20px; font-size:18px; }
.popup button { padding:8px 14px; border:none; border-radius:6px; margin:0 8px; cursor:pointer; font-weight:500; }
.popup .confirm { background:#ef4444; color:#fff; }
.popup .cancel { background:#94a3b8; color:#fff; }
.toast { position:fixed; top:20px; right:20px; background:#16a34a; color:#fff; padding:12px 20px; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.2); display:none; z-index:2000; }
</style>
</head>
<body>

<div class="main">
    <h1><i class="fas fa-comments"></i> Communication Records</h1>

    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $applicantId => $msgs): ?>
            <div class="card">
                <?php 
                $first = $msgs[0];
                $applicantName = $first['applicant_full_name'] ?? 'Unknown Applicant';
                $childName = $first['child_full_name'] ?? '-';
                $phone = $first['applicant_phone'] ?? '-';
                $marks = $first['marks'] ?? '-';
                $feedback = $first['feedback'] ?? '-';
                ?>
                <div class="applicant-header">
                    Applicant: <?= htmlspecialchars($applicantName) ?> | Child: <?= htmlspecialchars($childName) ?> | Phone: <?= htmlspecialchars($phone) ?><br>
                    Marks: <?= htmlspecialchars($marks) ?> | Feedback: <?= htmlspecialchars($feedback) ?>
                </div>
                <?php foreach ($msgs as $msg): ?>
                    <div class="message-box <?= ($msg['sender']=='admin') ? 'sender-admin' : 'sender-parent' ?>">
                        <div class="message-content">
                            <strong><?= htmlspecialchars($msg['sender']) ?>:</strong> <?= htmlspecialchars($msg['message']) ?><br>
                            <small><?= htmlspecialchars($msg['created_at']) ?></small>
                        </div>
                        <button class="btn-delete" data-id="<?= $msg['message_id'] ?>"><i class="fas fa-trash"></i></button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No messages found.</p>
    <?php endif; ?>
</div>

<!-- Confirmation Popup -->
<div class="blur-bg" id="blur-bg">
    <div class="popup">
        <h3>Are you sure you want to delete this message?</h3>
        <form method="post" id="deleteForm">
            <input type="hidden" name="delete_id" id="delete_id">
            <button type="submit" class="confirm">Delete</button>
            <button type="button" class="cancel" id="cancelBtn">Cancel</button>
        </form>
    </div>
</div>

<div class="toast" id="toast"><?= htmlspecialchars($notification) ?></div>

<script>
const deleteButtons = document.querySelectorAll('.btn-delete');
const blurBg = document.getElementById('blur-bg');
const deleteIdInput = document.getElementById('delete_id');
const cancelBtn = document.getElementById('cancelBtn');
const toast = document.getElementById('toast');

deleteButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        deleteIdInput.value = btn.getAttribute('data-id');
        blurBg.style.display = 'flex';
    });
});

cancelBtn.addEventListener('click', () => {
    blurBg.style.display = 'none';
});

// Show toast if notification exists
<?php if(!empty($notification)): ?>
toast.style.display = 'block';
setTimeout(() => { toast.style.display = 'none'; }, 3000);
<?php endif; ?>
</script>

</body>
</html>
