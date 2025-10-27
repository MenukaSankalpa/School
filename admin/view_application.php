<?php
include '../db.php';
session_start();

$loggedAdminId = $_SESSION['admin_id'] ?? 0;
$loggedAdminRole = $_SESSION['role'] ?? 0;
$isSuperAdmin = ($loggedAdminRole == 1);

if (!isset($_GET['id'])) {
    die("Application ID missing");
}

$appId = intval($_GET['id']);

// Fetch application info
$sql = "SELECT a.*, u.username AS assigned_admin
        FROM application_info a
        LEFT JOIN users u ON a.assigned_admin_id = u.id
        WHERE a.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $appId);
$stmt->execute();
$result = $stmt->get_result();
$app = $result->fetch_assoc();

if (!$app) {
    die("Application not found");
}

$parentEmail = $app['applicant_email'] ?? '';
$canMark = ($app['assigned_admin_id'] == $loggedAdminId || $isSuperAdmin);

// Fetch communication logs
$logSql = "SELECT * FROM communication_logs WHERE application_id = ? ORDER BY created_at DESC";
$logStmt = $conn->prepare($logSql);
$logStmt->bind_param("i", $appId);
$logStmt->execute();
$logResult = $logStmt->get_result();
$logs = $logResult->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Application</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: #f8fafc;
}
.layout {
    display: flex;
    min-height: 100vh;
}
.sidebar {
    width: 240px;
    background: #1e293b;
    color: #fff;
    padding: 20px;
}
.sidebar h2 {
    font-size: 20px;
    margin-bottom: 30px;
    text-align: center;
}
.sidebar a {
    display: flex;
    align-items: center;
    color: #e2e8f0;
    text-decoration: none;
    padding: 10px 12px;
    border-radius: 8px;
    margin-bottom: 6px;
    transition: 0.3s;
}
.sidebar a:hover {
    background: #334155;
}
.sidebar .material-icons {
    font-size: 20px;
    margin-right: 10px;
}
.main {
    flex: 1;
    padding: 40px;
}
.card {
    background: #fff;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}
h1 {
    font-size: 26px;
    margin-bottom: 20px;
    color: #0f172a;
}
.section h2 {
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 12px;
}
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
.table td {
    padding: 10px;
    border-bottom: 1px solid #e2e8f0;
}
input, textarea, button {
    font-family: 'Poppins', sans-serif;
}
input, textarea {
    width: 100%;
    padding: 10px 12px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    margin-bottom: 12px;
    font-size: 15px;
}
button {
    border: none;
    border-radius: 6px;
    padding: 10px 16px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
}
.btn-success {
    background: #16a34a;
    color: #fff;
}
.btn-success:hover { background: #15803d; }
.btn-primary {
    background: #2563eb;
    color: #fff;
}
.btn-primary:hover { background: #1e40af; }
.toast {
    display: none;
    position: fixed;
    top: 20px;
    right: 20px;
    background: #22c55e;
    color: #fff;
    padding: 12px 20px;
    border-radius: 6px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    z-index: 9999;
}
.log-card {
    background: #f9fafb;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    margin-bottom: 10px;
}
.log-card p {
    margin: 4px 0;
    font-size: 14px;
}
.log-card span {
    font-weight: 600;
    color: #334155;
}
</style>
</head>
<body>
<div class="layout">

<!-- Sidebar -->
<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="layout.php?page=super_admin_dashboard"><span class="material-icons">dashboard</span>Dashboard</a>
    <a href="layout.php?page=admin_list"><span class="material-icons">people</span>Admins</a>
    <a href="layout.php?page=applicant_list"><span class="material-icons">person</span>Applicants</a>
    <a href="layout.php?page=view_schools"><span class="material-icons">school</span>School Details</a>
    <a href="layout.php?page=parent_complaints"><span class="material-icons">report</span>Parent Complaints</a>
    <a href="../login.php"><span class="material-icons">logout</span>Logout</a>
</div>

<div class="main">

<div class="card">
    <h1>Application: <?= htmlspecialchars($app['child_full_name']) ?></h1>

    <div class="section">
        <h2>Applicant Details</h2>
        <table class="table">
            <tr><td><strong>Child Name:</strong></td><td><?= htmlspecialchars($app['child_full_name']) ?></td></tr>
            <tr><td><strong>DOB:</strong></td><td><?= htmlspecialchars($app['dob']) ?></td></tr>
            <tr><td><strong>District:</strong></td><td><?= htmlspecialchars($app['resident_district']) ?></td></tr>
            <tr><td><strong>Parent Name:</strong></td><td><?= htmlspecialchars($app['applicant_full_name']) ?></td></tr>
            <tr><td><strong>Email:</strong></td><td><?= htmlspecialchars($parentEmail) ?></td></tr>
            <tr><td><strong>Status:</strong></td><td><?= htmlspecialchars($app['status']) ?></td></tr>
            <tr><td><strong>Assigned Admin:</strong></td><td><?= htmlspecialchars($app['assigned_admin'] ?? '-') ?></td></tr>
            <tr><td><strong>Marks:</strong></td><td><?= htmlspecialchars($app['marks'] ?? '-') ?></td></tr>
            <tr><td><strong>Comments:</strong></td><td><?= htmlspecialchars($app['feedback'] ?? '-') ?></td></tr>
        </table>
    </div>
</div>

<?php if($canMark): ?>
<div class="card">
    <div class="section">
        <h2>Give Marks & Comments</h2>
        <form id="marksForm">
            <input type="hidden" name="app_id" value="<?= $appId ?>">
            <label>Marks</label>
            <input type="number" name="marks" min="0" max="100" required value="<?= htmlspecialchars($app['marks'] ?? '') ?>">
            <label>Comments</label>
            <textarea name="comments" rows="4"><?= htmlspecialchars($app['feedback'] ?? '') ?></textarea>
            <button type="submit" class="btn-success">Save Marks</button>
        </form>
    </div>
</div>
<?php endif; ?>

<div class="card">
    <div class="section">
        <h2>Notify Parent</h2>
        <form id="notifyForm">
            <input type="hidden" name="app_id" value="<?= $appId ?>">
            <button type="button" id="sendEmail" class="btn-primary">Send Email</button>
            <button type="button" id="sendMessage" class="btn-primary">Send Dashboard Message</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="section">
        <h2>Communication History</h2>
        <?php if(count($logs) > 0): ?>
            <?php foreach($logs as $log): ?>
                <div class="log-card">
                    <p><span>Type:</span> <?= htmlspecialchars($log['type']) ?></p>
                    <p><span>Message:</span> <?= htmlspecialchars($log['message']) ?></p>
                    <p><span>Sent By:</span> <?= htmlspecialchars($log['sent_by']) ?></p>
                    <p><span>Time:</span> <?= htmlspecialchars($log['created_at']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No communication records yet.</p>
        <?php endif; ?>
    </div>
</div>

<div class="toast" id="toast"></div>

</div>
</div>

<script>
function showToast(msg){
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.style.display = 'block';
    setTimeout(()=> t.style.display='none', 2500);
}

// Save marks/comments
document.getElementById('marksForm')?.addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    fetch('process_marks.php', { method:'POST', body: formData })
    .then(r=>r.json())
    .then(res=>{
        showToast(res.success ? '✅ Marks saved successfully!' : '⚠️ ' + res.message);
    });
});

// Send Email
document.getElementById('sendEmail').addEventListener('click', function(){
    fetch('send_email.php', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({ app_id: <?= $appId ?> })
    }).then(r=>r.json()).then(res=>{
        showToast(res.success ? '✅ Email sent to parent!' : '⚠️ ' + res.message);
    });
});

// Send Dashboard Message
document.getElementById('sendMessage').addEventListener('click', function(){
    fetch('send_message.php', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({ app_id: <?= $appId ?> })
    }).then(r=>r.json()).then(res=>{
        showToast(res.success ? '✅ Message sent to dashboard!' : '⚠️ ' + res.message);
    });
});
</script>
</body>
</html>
